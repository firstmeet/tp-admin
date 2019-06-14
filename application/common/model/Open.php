<?php
namespace app\common\model;
use Think\Db;
use app\common\service\Message;
use think\facade\Hook;
use think\facade\Request;
use app\common\model\Orderagent as DealerOrderModel;
use app\common\exception\BaseException;
use app\common\model\Level as levelModel;
use app\common\model\AppPrepayId as AppPrepayIdModel;
/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends BaseModel
{
    protected $name = 'order';
    protected $pk = 'order_id';
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'app_id',
        'update_time'
    ];
    /**
     * 订单确认-立即购买
     * @param User $user
     * @param $item_id
     * @param $goods_num
     * @param $item_sku_id
     * @return array
     * @throws \think\exception\DbException
     * @throws \app\common\exception\BaseException
     */
    public function getBuyNow($user, $item_id, $goods_num, $item_sku_id,$prom,$other)
    {
        // 商品信息
        /* @var Item $Item */
        $Item = Item::detail($item_id);
        $agio=10;
        if($user['level']&&$Item['vip']>0){
            $agio=(new levelModel)->where(array('key'=>$user['level']))->value('agio');
        }
        // 判断商品是否下架
        if (!$Item || $Item['is_on_sale'] !== 1) {
            throw new BaseException(['msg' => '很抱歉，商品信息不存在或已下架']);
        }
        // 商品sku信息
        $Item['goods_sku'] = $Item->getGoodsSku($item_sku_id);
        // 判断商品库存
        if ($goods_num > $Item['goods_sku']['store_count'] && $goods_num > $Item['store_count']) {
            $this->setError('很抱歉，商品库存不足');
        }
        // 商品单价
        $Item['goods_price'] = $Item['goods_sku'] ? $Item['goods_sku']['shop_price'] :'';
        // 商品总价
        $Item['agio'] = $agio;
        $Item['level'] = $user['level'];
        $Item['total_num'] = $goods_num;
        //是拼团商品修改折扣价格
        if($prom == 1){
            $assemble=(new Assemble)->where(array('item_id'=>$Item['goods_id']))->find();
            $Item['goods_price']= $Item['goods_price']*$assemble['rebate']/10;
            $Item['shop_price']= $Item['goods_sku']['shop_price']*$assemble['rebate']/10;
        }elseif($prom==2){
            $assemble=(new Spike)->where(array('item_id'=>$Item['goods_id']))->find();
            $Item['goods_price']= $Item['goods_price']*$assemble['discount']/10;
            $Item['shop_price']= $Item['goods_sku']['shop_price']*$assemble['discount']/10;
        }
        $Item['total_price'] = $totalPrice = sprintf("%1\$.2f",bcmul($Item['goods_price'], $goods_num, 2)*$agio/10);
        $agio_count =sprintf("%1\$.2f",bcmul($Item['goods_price'], $goods_num, 2)-bcmul($Item['goods_price'], $goods_num, 2)*$agio/10);
        // 商品总重量
        $goods_total_weight = bcmul($Item['weight'], $goods_num, 2);
        // 当前用户收货城市id
        $cityId = $user['address_default'] ? $user['address_default']['city_id'] : null;
        // 是否存在收货地址
        $exist_address = !empty($user['address']);
        // 验证用户收货地址是否存在运费规则中
        if (!$intraRegion = $Item['delivery']->checkAddress($cityId)) {
            $exist_address && $this->setError('很抱歉，您的收货地址不在配送范围内');
        }
        // 计算配送费用
        $expressPrice = $intraRegion ?
            $Item['delivery']->calcTotalFee($goods_num, $goods_total_weight, $cityId) : 0;
        // 订单总金额 (含运费)
        $orderPayPrice = bcadd($totalPrice,$expressPrice, 2);
        // 当前商品是否参与优惠券
        $couponprice=$Item['coupon']>0?bcmul($Item['goods_price'], $goods_num, 2):0;
        // 可用优惠券列表
        $couponList = UserCoupon::getUserCouponList($user['user_id'], $couponprice);
        return [
            'goods_list' => [$Item],               // 商品详情
            'order_total_num' => $goods_num,        // 商品总数量
            'order_total_price' => $totalPrice,     // 商品总金额 (不含运费)
            'order_pay_price' => $orderPayPrice,    // 订单总金额 (含运费)
            'coupon_list' => array_values($couponList),   // 优惠券列表
            'agio_count'=>$agio_count,
            'address' => $user,  // 默认地址
            'exist_address' => $exist_address,      // 是否存在收货地址
            'express_price' => $expressPrice,       // 配送费用
            'intra_region' => $intraRegion,         // 当前用户收货城市是否存在配送规则中
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
            'prom' => $prom,
            'other' => $other,
        ];
    }
    /**
     * 创建新订单
     * @param $user_id
     * @param $order
     * @param $coupon_id
     * @param string $remark
     * @return bool
     * @throws \Exception
     */
    public function createOrder($user_id,$order, $coupon_id = null, $remark = '',$prom = '',$item_id = '',$other = '')
    {
        if (empty($order['address'])) {
            $this->error = '请先选择收货地址';
            return false;
        }
        // 设置订单优惠券信息
        $this->setCouponPrice($order, $coupon_id);
        Db::startTrans();
        try {
            // 记录订单信息
            $this->add($user_id, $order, $remark,$prom,$item_id,$other);
            // 保存订单商品信息
            $this->saveOrderGoods($user_id, $order);
            // 更新商品库存 (针对下单减库存的商品)
            $this->updateGoodsStockNum($order['goods_list']);
            // 记录收货地址
            $this->saveOrderAddress($user_id, $order['address']);
            // 事务提交
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }
    /**
     * 设置订单优惠券信息
     * @param $order
     * @param $coupon_id
     * @return bool
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function setCouponPrice(&$order, $coupon_id)
    {
        if ($coupon_id > 0 && !empty($order['coupon_list'])) {
            // 获取优惠券信息
            $couponInfo = [];
            foreach ($order['coupon_list'] as $coupon)
                $coupon['user_coupon_id'] == $coupon_id && $couponInfo = $coupon;
            if (empty($couponInfo)) throw new BaseException(['msg' => '未找到优惠券信息']);
            // 计算订单金额 (抵扣后)
            $orderTotalPrice = bcsub($order['order_total_price'], $couponInfo['reduced_price'], 2);
            $orderTotalPrice <= 0 && $orderTotalPrice = '0.01';
            // 记录订单信息
            $order['coupon_id'] = $coupon_id;
            $order['coupon_price'] = $couponInfo['reduced_price'];
            $order['order_pay_price'] = bcadd($orderTotalPrice, $order['express_price'], 2);
            // 设置优惠券使用状态
            $model = UserCoupon::detail($coupon_id);
            $model->setIsUse();
            return true;
        }
        $order['coupon_id'] = 0;
        $order['coupon_price'] = 0.00;
        return true;
    }
    /**
     * 新增订单记录
     * @param $user_id
     * @param $order
     * @param string $remark
     * @return false|int
     */
    private function add($user_id, &$order, $remark = '',$prom ='',$item_id = '',$other = '')
    {
        $prom_statis=1;
        //当前为拼团时
        if($prom==1){
            $assemble = (new Assemble)->get(['item_id','=',$item_id]);
            $endtime=date('Y-m-d H:i:s',(time()+$assemble['start_time']*24*60*60));
            if($other){//当对方用户存在.修改对方的拼团数量。
                $where[]=['user_id','=',$other];
                $where[]=['item_id','=',$item_id];
                $where[]=['prom_type','=',1];
                $otherorder=$this->where($where)->find();
                if(($otherorder['prom_num']+1)==$assemble['group_num']){
                    //将同一商品下的所有拼团订单状态为已完成。
                    $save[]=['item_id','=',$item_id];
                    $save[]=['prom_type','=',1];
                    $save[]=['user_id','=',$other];
                    $this->where($save)->update(['prom_statis'=>2,'prom_num'=>($otherorder['prom_num']+1)]);
                    $prom_statis=2;
                }
            }
        }
        return $this->save([
            'user_id' => $user_id,
            'app_id' => self::$app_id,
            'order_no' => $this->orderNo(),
            'total_price' => $order['order_total_price'],
            'coupon_id' => $order['coupon_id'],
            'coupon_price' => $order['coupon_price'],
            'pay_price' => $order['order_pay_price'],
            'express_price' => $order['express_price'],
            'buyer_remark' => trim($remark),
            'prom_type' => $prom,
            'prom_statis' => $prom_statis,
            'item_id' => $item_id,
            'prom_num' =>isset($otherorder['prom_num'])?$otherorder['prom_num']+1:1,
            'prom_time' =>isset($assemble['start_time'])?$assemble['start_time']:'',
            'give_integral' =>isset($order['goods_list'][0]["give_integral"])?$order['goods_list'][0]["give_integral"]:0,
            'rebate' =>isset($assemble['rebate'])?$assemble['rebate']:'',
            'end_time' =>isset($endtime)?$endtime:'',
        ]);
    }
    /**
     * 保存订单商品信息
     * @param $user_id
     * @param $order
     * @return int
     */
    private function saveOrderGoods($user_id, &$order)
    {
        // 订单商品列表
        $goodsList = [];
        // 订单商品实付款金额 (不包含运费)
        $realTotalPrice = bcsub($order['order_pay_price'], $order['express_price'], 2);
        foreach ($order['goods_list'] as $Item) {
            /* @var Item $Item */
            // 计算商品实际付款价
            $total_pay_price = $realTotalPrice * $Item['total_price'] / $order['order_total_price'];
            $goodsList[] = [
                'user_id' => $user_id,
                'app_id' => self::$app_id,
                'item_id' => $Item['goods_id'],
                'name' => $Item['goods_name'],
                'image' => $Item  ["image"][0]['file_path'],
                'spec_type' => $Item['spec_type'],
                'type' => 20,
                'spec_sku_id' => $Item['goods_sku'] ? $Item['goods_sku']['key'] : $Item['goods_id']+'_1',
                'item_sku_id' => $Item['spec_type'],
                'goods_attr' => $Item['goods_sku'] ? $Item['goods_sku']['goods_attr'] : $Item['goods_remark'],
                'content' => $Item['goods_content'],
                'goods_no' => time(),
                'goods_price' =>$Item['goods_sku']['shop_price'],
                'line_price' => $Item['goods_sku'] ? $Item['goods_sku']['price'] : $Item['market_price'],
                'goods_weight' => $Item['weight'],
                'total_num' => $Item['total_num'],
                'total_price' => $Item['total_price'],
                'total_pay_price' => sprintf('%.2f', $total_pay_price),
            ];
        }
        return $this->goods()->saveAll($goodsList);
    }
    /**
     * 更新商品库存 (针对下单减库存的商品)
     * @param $goods_list
     * @throws \Exception
     */
    private function updateGoodsStockNum($goods_list)
    {
        $deductStockData = [];
        foreach ($goods_list as $Item) {
            // 下单减库存
            $deductStockData[] = [
                'spec_type' => $Item['spec_type'],
                'store_count' => ['dec', $Item['total_num']]
            ];
        }
        !empty($deductStockData) && (new Item)->saveAll($deductStockData);
    }
    /**
     * 记录收货地址
     * @param $user_id
     * @param $address
     * @return false|\think\Model
     */
    private function saveOrderAddress($user_id, $address)
    {
        return $this->address()->save([
            'user_id' => $user_id,
            'app_id' => self::$app_id,
            'name' => $address["address_default"]['name'],
            'phone' => $address["address_default"]['phone'],
            'province_id' => $address["address_default"]['province_id'],
            'city_id' => $address["address_default"]['city_id'],
            'region_id' => $address["address_default"]['region_id'],
            'detail' => $address["address_default"]['detail'],
        ]);
    }
    /**
     * 用户中心订单列表
     * @param $user_id
     * @param string $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['order_status'] = 30;
                $filter['is_comment'] = 0;
                break;
        }
        return $this->with(['goods'])
            ->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->order(['create_time' => 'desc'])
            ->select();
    }
    /**
     * 取消订单
     * @return bool|false|int
     * @throws \Exception
     */
    public function cancel()
    {
        if ($this['pay_status']['value'] === 20) {
            $this->error = '已付款订单不可取消';
            return false;
        }
        // 回退商品库存
        $this->backGoodsStock($this['goods']);
        return $this->save(['order_status' => 20]);
    }
    /**
     * 回退商品库存
     * @param $goodsList
     * @return array|false
     * @throws \Exception
     */
    private function backGoodsStock(&$goodsList)
    {
        $goodsSpecSave = [];
        foreach ($goodsList as $Item) {
            // 下单减库存
            if ($Item['type'] === 10) {
                $goodsSpecSave[] = [
                    'item_sku_id' => $Item['item_sku_id'],
                    'stock_num' => ['inc', $Item['total_num']]
                ];
            }
        }
        // 更新商品规格库存
        return !empty($goodsSpecSave) && (new ItemSku)->isUpdate()->saveAll($goodsSpecSave);
    }
    /**
     * 确认收货
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function receipt($user)
    {
        // 验证订单是否合法
        if ($this['delivery_status']['value'] === 10 || $this['receipt_status']['value'] === 20) {
            $this->error = '该订单不合法';
            return false;
        }
        $this->startTrans();
        try {
            // 更新订单状态
            $this->save([
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 发放分销商佣金
            DealerOrderModel::grantMoney($this['order_id'],$user);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }
    /**
     * 获取订单总数
     * @param $user_id
     * @param string $type
     * @return int|string
     */
    public function getCount($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['order_status'] = 30;
                $filter['is_comment'] = 0;
                break;
        }
        return $this->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->count();
    }
    /**
     * 订单详情
     * @param $order_id
     * @param null $user_id
     * @return null|static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        if (!$order = self::get([
            'order_id' => $order_id,
            'user_id' => $user_id
        ], ['goods' => ['image', 'goods'],'user','address', 'express'])) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }
    /**
     * 判断商品库存不足 (未付款订单)
     * @param $goodsList
     * @return bool
     */
    public function checkStatusFromOrder(&$goodsList)
    {
        foreach ($goodsList as $Item) {
            // 判断商品是否下架
            if (!$Item['goods'] || $Item['goods']['is_on_sale'] !== 1) {
                $this->setError('很抱歉，商品 [' . $Item['name'] . '] 已下架');
                return false;
            }
            // 付款减库存
            if ($Item['type'] === 20 && $Item['goods']['store_count'] < 1) {
                $this->setError('很抱歉，商品 [' . $Item['name'] . '] 库存不足');
                return false;
            }
        }
        return true;
    }
    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }
    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }
    /**
     * 待支付订单详情
     * @param $order_no
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function payDetail($order_no)
    {
        return self::get(['order_no' => $order_no, 'pay_status' => 20], ['goods', 'user']);
    }
    /**
     * 订单支付成功业务处理
     * @param $transaction_id
     * @throws \Exception
     * @throws \think\Exception
     */
    public function paySuccess($transaction_id)
    {
        // 更新付款状态
        $this->updatePayStatus($transaction_id);
        // 发送消息通知
        $Message = new Message;
        $Message->payment($this);
    }
    /**
     * 更新付款状态
     * @param $transaction_id
     * @return false|int
     * @throws \Exception
     */
    private function updatePayStatus($transaction_id)
    {
        Db::startTrans();
        try {
            // 更新商品库存、销量
            $ItemModel = new Item;
            $ItemModel->updateStockSales($this['goods']);
            // 更新订单状态
            $this->save([
                'pay_status' => 20,
                'pay_time' => time(),
                'transaction_id' => $transaction_id
            ]);
            $this->dealer($this['user_id']);
            // 累积用户总消费金额
            $user = User::detail($this['user_id']);
            $user->cumulateMoney($this['pay_price']);
            $user->integral($this['integral']);
            // 更新prepay_id记录
            $prepayId = AppPrepayIdModel::detail($this['order_id']);
            $prepayId->updatePayStatus();
            // 事务提交
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
            return false;
        }
    }
    private function commissions($user_id, $goods_list, $level)
    {
        // 佣金设置
        $setting = Setting::getItem('commission');
        $data = [
            'first_money' => 0.00,  // 一级分销佣金
            'second_money' => 0.00, // 二级分销佣金
            'third_money' => 0.00   // 三级分销佣金
        ];
        // 计算分销佣金
        foreach ($goods_list as $Item) {
            $level >= 1 && $data['first_money'] += ($Item['total_pay_price'] * ($setting['first_money'] * 0.01));
            $level >= 2 && $data['second_money'] += ($Item['total_pay_price'] * ($setting['second_money'] * 0.01));
            $level == 3 && $data['third_money'] += ($Item['total_pay_price'] * ($setting['third_money'] * 0.01));
        }
        // 记录分销商用户id
        $data['first_user_id'] = $level >= 1 ? Referee::getRefereeUserId($user_id, 1, true) : 0;
        $data['second_user_id'] = $level >= 2 ? Referee::getRefereeUserId($user_id, 2, true) : 0;
        $data['third_user_id'] = $level == 3 ? Referee::getRefereeUserId($user_id, 3, true) : 0;
        return $data;
    }
    private function dealer($user_id,$level = 0)
    {
        // 分销商基本设置
        $setting = Setting::getItem('basic');
        $user = User::get($user_id);
        if($user['pid']){
        }
    }
    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('order', $static);
    }
    /**
     * 订单详情信息
     * @return \think\model\relation\BelongsTo
     */
    public function orderMaster()
    {
        return $this->belongsTo('app\common\model\Order');
    }
    /**
     * 订单商品列表
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany('OrderGoods');
    }
    /**
     * 关联物流列表
     * @return \think\model\relation\HasMany
     */
    public function OrderDelivery()
    {
        return $this->hasMany('OrderDelivery','order_no','order_no');
    }
    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderAddress');
    }
    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
    /**
     * 关联物流公司表
     * @return \think\model\relation\BelongsTo
     */
    public function express()
    {
        return $this->hasMany('Express','express_id');
    }
    /**
     * 改价金额（差价）
     * @param $value
     * @return array
     */
    public function getUpdatePriceAttr($value)
    {
        return [
            'symbol' => $value < 0 ? '-' : '+',
            'value' => sprintf('%.2f', abs($value))
        ];
    }
    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        $status = [10 => '待付款', 20 => '已付款'];
        return ['text' => $status[$value], 'value' => $value];
    }
    /**
     * 发货状态
     * @param $value
     * @return array
     */
    public function getDeliveryStatusAttr($value)
    {
        $status = [10 => '待发货', 20 => '已发货'];
        return ['text' => $status[$value], 'value' => $value];
    }
    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getReceiptStatusAttr($value)
    {
        $status = [10 => '待收货', 20 => '已收货'];
        return ['text' => $status[$value], 'value' => $value];
    }
    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getOrderStatusAttr($value)
    {
        $status = [10 => '进行中', 20 => '取消', 30 => '已完成'];
        return ['text' => $status[$value], 'value' => $value];
    }
    /**
     * 生成订单号
     * @return string
     */
    protected function orderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    /**
     * 订单详情
     * @param $where
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        is_array($where) ? $filter = $where : $filter['order_id'] = (int)$where;
        return self::get($filter, ['goods.image', 'address','user','OrderDelivery']);
    }
    /**
     * 	分佣列表
     * @param string $dataType
     * @param array $query
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getGold($dataType,$fen=0)
    {
        $data= $this->getLists($dataType,[],'');
        foreach($data as $key =>$vo)
        {
            $data[$key]['agent']=$this->path($vo['item_id'],$vo['pay_price'],$vo['user']['path'],$fen);
        }
        return $data;
    }
    /*查询层级并计算分佣
    * @param string $item_id 商品ID
    * @param string $pay_price 实付金额
    * @param string $path
    * @throws \think\exception\DbException
    */
    public function path($item_id,$pay_price,$path,$fen=0)
    {
        $data=Item::detail($item_id);
        //计算分成
        if($data['agent_type']==1){
            $data['agent_price'] = $pay_price*($data['agent_price']/100);
        }
        $setting=Setting::detail('commission');
        $path=array_filter(explode(',',$path));
        if(!empty($path)){
            if(isset($path[0])){
                $agent['first']=$data['agent_price']*$setting['values']['first_money']/100;
                if($fen==1)$this->apple($path[0],1,$agent['first']);
            }
            if(isset($path[1])){
                $agent['second']=$data['agent_price']*$setting['values']['second_money']/100;
                if($fen==1) $this->apple($path[1],2,$agent['second']);
            }
            if(isset($path[2])){
                $agent['third']=$data['agent_price']*$setting['values']['third_money']/100;
                if($fen==1) $this->apple($path[2],3,$agent['third']);
            }
            return $agent;
        }
    }
    public function apple($user_id,$level,$price){
        $model=User::detail($user_id);
        $model->cumulateMoney($price);
        (new AgentCapital)->save([
            'user_id' =>$user_id,
            'money'   =>$price,
            'app_id'=>self::$app_id,
            'describe' =>$level.'层分佣',
            'flow_type'=>10
        ]);
    }
    /**
     * 订单列表
     * @param string $dataType
     * @param array $query
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getLists($dataType,$query = [],$user_id)
    {
        $where=$this->transferDataType($dataType);
        if(!empty($user_id)){
            $where['user_id']=$user_id;
        }
        // 检索查询
        $this->setWhere($query);
        // 获取数据列表
        return $this->with(['goods.image', 'address', 'user'])
            ->where($where)
            ->order(['create_time' => 'desc'])
            ->paginate(10, false, [
                'query' => Request::instance()->request()
            ]);
    }
    /**
     * 订单列表(全部)
     * @param $dataType
     * @param array $query
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListAll($dataType, $query = [])
    {
        // 检索查询
        $this->setWhere($query);
        // 获取数据列表
        return $this->with(['goods.image', 'address', 'user'])
            ->where($this->transferDataType($dataType))
            ->order(['create_time' => 'desc'])
            ->select();
    }
    /**
     * 数据导出到excel(csv文件)
     * @param $fileName
     * @param array $tileArray
     * @param array $dataArray
     */
    function export_excel($fileName,$tileArray = [],$dataArray = [])
    {
        $file_name = "order-".(date('Ymdhis',time())).".csv";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$file_name );
        header('Cache-Control: max-age=0');
        $file = fopen('php://output',"a");
        $limit = 1000;
        $calc = 0;
        foreach ($tileArray as $v){
            $tit[] = iconv('UTF-8', 'GB2312//IGNORE',$v);
        }
        fputcsv($file,$tit);
        foreach ($dataArray as $v){
            $calc++;
            if($limit == $calc){
                ob_flush();
                flush();
                $calc = 0;
            }
            foreach($v as $t){
                $tarr[] = iconv('UTF-8', 'GB2312//IGNORE',$t);
            }
            fputcsv($file,$tarr);
            unset($tarr);
        }
        unset($list);
        fclose($file);
        exit();
    }
    function tab1ss($tab1s){
        $tab=explode('-',$tab1s);
        return "充值满".$tab[0].',减'.$tab[1];
    }
    function coupon($coupon){
        $str='<select name="prom[expression]">';
        foreach($coupon as $k=>$v){
            $str.='<option value="'.$v['coupon_id'].'">'.$v['name'].'</option>';
        }
        $str.='</select>';
        return $str;
    }
    function catname($cat_id){
        return db('category')->where(array('id'=>$cat_id))->value('name');
    }
    /**
     * 订单导出
     * @param $dataType
     * @param $query
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportList($dataType, $query)
    {
        // 获取订单列表
        $list = $this->getListAll($dataType, $query);
        // 表格标题
        $tileArray = ['订单号', '商品名称', '单价', '数量', '付款金额', '运费金额', '下单时间',
            '买家', '买家留言', '收货人姓名', '联系电话', '收货人地址', '物流公司', '物流单号',
            '付款状态', '付款时间', '发货状态', '发货时间', '收货状态', '收货时间', '订单状态',
            '微信支付交易号', '是否已评价'];
        // 表格内容
        $dataArray = [];
        foreach ($list as $order) {
            /* @var OrderAddress $address */
            $address = $order['address'];
            foreach ($order['goods'] as $Item) {
                $dataArray[] = [
                    '订单号' => $this->filterValue($order['order_no']),
                    '商品名称' => $Item['name'],
                    '单价' => $Item['goods_price'],
                    '数量' => $Item['total_num'],
                    '付款金额' => $this->filterValue($order['pay_price']),
                    '运费金额' => $this->filterValue($order['express_price']),
                    '下单时间' => $this->filterValue($order['create_time']),
                    '买家' => $this->filterValue($order['user']['nickName']),
                    '买家留言' => $this->filterValue($order['buyer_remark']),
                    '收货人姓名' => $this->filterValue($order['address']['name']),
                    '联系电话' => $this->filterValue($order['address']['phone']),
                    '收货人地址' => $this->filterValue($address ? $address->getFullAddress() : ''),
                    '物流公司' => $this->filterValue($order['express']['express_name']),
                    '物流单号' => $this->filterValue($order['express_no']),
                    '付款状态' => $this->filterValue($order['pay_status']['text']),
                    '付款时间' => $this->filterTime($order['pay_time']),
                    '发货状态' => $this->filterValue($order['delivery_status']['text']),
                    '发货时间' => $this->filterTime($order['delivery_time']),
                    '收货状态' => $this->filterValue($order['receipt_status']['text']),
                    '收货时间' => $this->filterTime($order['receipt_time']),
                    '订单状态' => $this->filterValue($order['order_status']['text']),
                    '微信支付交易号' => $this->filterValue($order['transaction_id']),
                    '是否已评价' => $this->filterValue($order['is_comment'] ? '是' : '否'),
                ];
            }
        }
        // 导出csv文件
        $filename = 'order-' . date('YmdHis');
        return export_excel($filename . '.csv', $tileArray, $dataArray);
    }
    /**
     * 批量发货模板
     */
    public function deliveryTpl()
    {
        // 导出csv文件
        $filename = 'delivery-' . date('YmdHis');
        return export_excel($filename . '.csv', ['订单号', '物流单号']);
    }
    /**
     * 表格值过滤
     * @param $value
     * @return string
     */
    private function filterValue($value)
    {
        return "\t" . $value . "\t";
    }
    /**
     * 日期值过滤
     * @param $value
     * @return string
     */
    private function filterTime($value)
    {
        if (!$value) return '';
        return $this->filterValue(date('Y-m-d H:i:s', $value));
    }
    /**
     * 设置检索查询条件
     * @param $query
     */
    private function setWhere($query)
    {
        if (!empty($query['order_no']) && $query['order_type'] =='order')
            $this->where('order_no', 'like', '%' . trim($query['order_no']) . '%');
        if (!empty($query['order_no']) && $query['order_type'] =='user')
            $this->where('user_id', 'like', '%' . trim($query['order_no']) . '%');
        if (!empty($query['order_no']) && $query['order_type'] =='item')
            $this->where('item_id', 'like', '%' . trim($query['order_no']) . '%');
    }
    /**
     * 转义数据类型条件
     * @param $dataType
     * @return array
     */
    private function transferDataType($dataType)
    {
        // 数据类型
        $filter = [];
        switch ($dataType) {
            case 'delivery':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 10
                ];
                break;
            case 'receipt':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 20,
                    'receipt_status' => 10
                ];
                break;
            case 'pay':
                $filter = ['pay_status' => 10, 'order_status' => 10];
                break;
            case 'complete':
                $filter = ['order_status' => 30];
                break;
            case 'cancel':
                $filter = ['order_status' => 20];
                break;
            case 'all':
                $filter = [];
                break;
            case 'etcsalelist':
                $filter = ['sub_status' => 10];
                break;
            case 'inSaleList':
                $filter = ['sub_status' => 20];
                break;
            case 'gold':
                $data=(new Setting)->detail('trade');
                $filter = [];
                //$filter = ['order_status' => 30,'end_time'=>0,'update_time'=>['>',time()-$data['values']['order']['sub']*86400]];
                break;
        }
        return $filter;
    }
    /**
     * 确认发货
     * @param $data
     * @param bool $sendMsg 是否发送消息通知
     * @return bool|false|int
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function delivery($data, $sendMsg = true)
    {
        // 更新订单状态
        $status = $this->save([
            'delivery_status' => 20,
            'delivery_time' => time(),
        ]);
        $express=Express::detail($data['express_id']);
        $status=OrderDelivery::create([
            'express_id' => $data['express_id'],
            'order_no' => $this['order_no'],
            'company'=>$express['express_name'],
            'express_no' => $data['express_no'],
            'app_id'	=> self::$app_id,
            'type'       =>1
        ]);
        // 发送消息通知
        ($status && $sendMsg) && $this->deliveryMessage($this['order_id'],$express,$data['express_no']);
        return $status;
    }
    /**
     * 确认发货后发送消息通知
     * @param $order_id
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    private function deliveryMessage($order_id,$express,$express_no)
    {
        $orderIds = is_array($order_id) ? $order_id : [$order_id];
        // 发送消息通知
        $Message = new Message;
        foreach ($orderIds as $orderId) {
            $Message->delivery(self::detail($orderId),$express,$express_no);
        }
        return true;
    }
    /**
     * 确认发货
     * @param $data
     * @return bool
     * @throws \think\exception\DbException
     */
    public function batchDelivery($data)
    {
        // 获取csv文件中的数据
        $csvData = $this->getCsvData();
        if (count($csvData) <= 1) {
            $this->error = '模板文件中没有订单数据';
            return false;
        }
        // 删除csv标题
        unset($csvData[0]);
        // 批量发货
        try {
            $this->startTrans();
            $orderIds = [];
            foreach ($csvData as $item) {
                if (!isset($item[0])
                    || empty($item[0])
                    || !isset($item[1])
                    || empty($item[1])
                ) {
                    $this->error = '模板文件数据不合法';
                    return false;
                }
                $orderIds[] = $item[0];
                if (!$model = self::detail(['order_no' => $item[0]])) {
                    $this->error = '订单号 ' . $item[0] . ' 不存在';
                    return false;
                }
                if (!$status = $model->delivery([
                    'express_id' => $data['express_id'],
                    'express_no' => $item[1],
                ], false)) {
                    $this->error = ' 订单号：' . $item[0] . ' ' . $model->error;
                    return false;
                }
            }
            // 发送消息通知
            $this->deliveryMessage($orderIds);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }
    /**
     * 获取csv文件中的数据
     * @return array|bool
     */
    private function getCsvData()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('iFile');
        if (!$file) {
            $this->error = '请上传发货模板';
            return false;
        }
        setlocale(LC_ALL, 'zh_CN');
        $data = [];
        $csvFile = fopen($file->getInfo()['tmp_name'], 'r');
        while ($row = fgetcsv($csvFile)) {
            $data[] = $row;
        }
        return $data;
    }
    /**
     * 修改订单价格
     * @param $data
     * @return bool
     */
    public function updatePrice($data)
    {
        if ($this['pay_status']['value'] !== 10) {
            $this->error = '该订单不合法';
            return false;
        }
        // 实际付款金额
        $payPrice = bcadd($data['update_price'], $data['update_express_price'], 2);
        if ($payPrice <= 0) {
            $this->error = '订单实付款价格不能为0.00元';
            return false;
        }
        return $this->save([
                'order_no' => $this->orderNo(), // 修改订单号, 否则微信支付提示重复
                'pay_price' => $payPrice,
                'update_price' => $data['update_price'] - ($this['total_price'] - $this['coupon_price']),
                'express_price' => $data['update_express_price']
            ]) !== false;
    }
    /**
     * 获取已付款订单总数 (可指定某天)
     * @param null $day
     * @return int|string
     */
    public function getPayOrderTotal($day = null)
    {
        $filter['pay_status'] = 20;
        if (!is_null($day)) {
            $startTime = strtotime($day);
            $filter['pay_time'] = ['between',$startTime,$startTime + 86400];
        }
        return $this->getOrderTotal($filter);
    }
    /**
     * 获取订单总数量
     * @param array $filter
     * @return int|string
     */
    public function getOrderTotal($filter = [])
    {
        return $this->where($filter)->count();
    }
    /**
     * 获取某天的总销售额
     * @param $day
     * @return float|int
     */
    public function getOrderTotalPrice($day)
    {
        $startTime = strtotime($day);
        return $this->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', 20)
            ->sum('pay_price');
    }
    /**
     * 获取某天的下单用户数
     * @param $day
     * @return float|int
     */
    public function getPayOrderUserTotal($day)
    {
        $startTime = strtotime($day);
        $userIds = $this->distinct(true)
            ->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', 20)
            ->column('user_id');
        return count($userIds);
    }
}