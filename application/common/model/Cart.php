<?php
namespace app\common\model;
use think\facade\Cache;
/**
 * 购物车管理
 * Class Cart
 * @package app\api\model
 */
class Cart
{
    /* @var string $error 错误信息 */
    public $error = '';
    /* @var int $user_id 用户id */
    private $user_id;
    /* @var array $cart 购物车列表 */
    private $cart = [];
    /* @var bool $clear 是否清空购物车 */
    private $clear = false;
	 /**
     * 模型基类初始化
     */
    /**
     * 构造方法
     * Cart constructor.
     * @param $user_id
     */
	public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->cart = Cache::get('cart_' . $this->user_id) ?: [];
    }
    /**
     * 购物车列表
     * @param \think\Model|\think\Collection $user
     * @param string $cart_ids
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($user, $cart_ids = null)
    {
        // 购物车列表 （$cart_goods_ids为null时则获取全部）
        if (is_null($cart_ids)) {
            $cartList = $this->cart;
        } else {
            $cartList = [];
            $indexArr = strpos($cart_ids, ',') !== false
                ? explode(',', $cart_ids) : [$cart_ids];
            foreach ($indexArr as $index) isset($this->cart[$index]) && $cartList[$index] = $this->cart[$index];
        }
        // 商品列表
        $goodsIds = array_unique(array_column($cartList, 'item_id'));
        $goodsData = [];
        foreach ((new Item)->getListByIds($goodsIds) as $Item) {
            $goodsData[$Item['goods_id']] = $Item;
        }
        // 当前用户收货城市id
        $cityId = $user['address_default'] ? $user['address_default']['city_id'] : null;
        // 是否存在收货地址
        $exist_address = !empty($user['address']);
        // 商品是否在配送范围
        $intraRegion = true;
        // 购物车商品列表
        $goodsList = [];
		$shopList=[];
		$agio_count=0;
        foreach ($cartList as $key => $cart) {
            // 判断商品不存在则自动删除
            if (!isset($goodsData[$cart['item_id']])) continue;
            /* @var Item $Item */
            $Item = $goodsData[$cart['item_id']];
            // 商品sku信息
            $Item['item_sku_id'] = $cart['item_sku_id'];
			 $Item['level'] = $user['level'];
            // 商品sku不存在则自动删除
            if (!$Item['goods_sku'] = $Item->getGoodsSku($cart['item_sku_id'])) {
                $this->delete($cart['item_id'], $cart['item_sku_id']);
                continue;
            } 
			$Item['goods_sku'] = $Item->getGoodsSku($cart['item_sku_id']);
            // 判断商品是否下架 item_sku_id
			if ($Item['is_on_sale'] !== 1) {
                $this->setError('很抱歉，商品 [' . $Item['goods_name'] . '] 已下架');
            }
            // 判断商品库存
            if ($cart['goods_num'] > $Item['goods_sku']['store_count'] && $cart['goods_num'] > $Item['store_count']) {
                $this->setError('很抱歉，商品 [' . $Item['goods_name'] . '] 库存不足');
            }
            // 商品单价
            $Item['goods_price'] = $Item['goods_sku']['shop_price'];
            // 商品总价
            $Item['total_num'] = $cart['goods_num'];
			$Item['total_price'] = $total_price =bcmul($Item['goods_price'], $cart['goods_num'], 2);      
            // 商品总重量
            $Item['goods_total_weight'] = bcmul($Item['weight'], $cart['goods_num'], 2);
            // 验证用户收货地址是否存在运费规则中
            if ($intraRegion = $Item['delivery']->checkAddress($cityId)) {
                $Item['express_price'] = $Item['delivery']->calcTotalFee(
                    $cart['goods_num'], $Item['goods_total_weight'], $cityId);
            } else {
                $exist_address && $this->setError("很抱歉，您的收货地址不在商品 [{$Item['goods_name']}] 的配送范围内");
            }
			if($Item['coupon']>0){
				$shopList[] = $Item->toArray();
			}
            $goodsList[] = $Item->toArray();
        }
        // 商品总金额
        $orderTotalPrice = array_sum(array_column($goodsList, 'total_price'));
		//统计参与优惠券的商品的总金额
		$conponTotalPrice = array_sum(array_column($shopList, 'total_price'));
        // 所有商品的运费金额
        $allExpressPrice = array_column($goodsList, 'express_price');
        // 订单总运费金额
        $expressPrice = $allExpressPrice ? Delivery::freightRule($allExpressPrice) : 0.00;
        // 订单总金额 (含运费)
        $orderPayPrice = bcadd($orderTotalPrice, $expressPrice, 2);
        //可用优惠券列表
        $couponList = UserCoupon::getUserCouponList($user['user_id'], $conponTotalPrice);
        return [
            'goods_list' => $goodsList,                       // 商品列表
            'order_total_num' => $this->getTotalNum(),       // 商品总数量
            'order_total_price' => sprintf('%.2f', $orderTotalPrice),  // 商品总金额 (不含运费)
            'order_pay_price' => $orderPayPrice,          // 实际支付金额
            'coupon_list' => array_values($couponList),   // 优惠券列表
            'address' => $user,  // 默认地址
            'exist_address' => $exist_address,      // 是否存在收货地址
            'express_price' => $expressPrice,       // 配送费用
            'intra_region' => $intraRegion,         // 当前用户收货城市是否存在配送规则中
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
        ];
    }
    /**
     * 添加购物车
     * @param $item_id
     * @param $goods_num
     * @param $item_sku_id
	 * @param $user
     * @return bool
     * @throws \think\exception\DbException
     */
    public function add($item_id, $goods_num, $item_sku_id,$user)
    {
        // 购物车商品索引
        $index = $item_id . '_' . $item_sku_id;
        // 商品信息
        $Item = Item::detail($item_id);
        // 判断商品是否下架
        if (!$Item ||  $Item['is_on_sale'] !== 1) {
            $this->setError('很抱歉，商品信息不存在或已下架');
            return false;
        }
        // 商品sku信息
        $Item['goods_sku'] = $Item->getGoodsSku($item_sku_id);
        // 判断商品库存
        $cartGoodsNum = $goods_num + (isset($this->cart[$index]) ? $this->cart[$index]['goods_num'] : 0);
        if ($cartGoodsNum > $Item['goods_sku']['store_count'] && $cartGoodsNum >  $Item['store_count']) {
            $this->setError('很抱歉，商品库存不足');
            return false;
        }
        $create_time = time();
        $data = compact('item_id', 'goods_num', 'item_sku_id', 'create_time');
        if (empty($this->cart)) {
            $this->cart[$index] = $data;
            return true;
        }
        isset($this->cart[$index]) ? $this->cart[$index]['goods_num'] = $cartGoodsNum : $this->cart[$index] = $data;
        return true;
    }
    /**
     * 减少购物车中某商品数量
     * @param $item_id
     * @param $item_sku_id
     */
    public function sub($item_id, $item_sku_id)
    {
        $index = $item_id . '_' . $item_sku_id;
        $this->cart[$index]['goods_num'] > 1 && $this->cart[$index]['goods_num'];
    }
    /**
     * 删除购物车中指定商品
     * @param $cart_ids (支持字符串ID集)
     */
    public function delete($cart_ids)
    {
        $indexArr = strpos($cart_ids, ',') !== false
            ? explode(',', $cart_ids) : [$cart_ids];
        foreach ($indexArr as $index) {
            if (isset($this->cart[$index])) unset($this->cart[$index]);
        }
    }
    /**
     * 获取当前用户购物车商品总数量
     * @return int
     */
    public function getTotalNum()
    {
        return array_sum(array_column($this->cart, 'goods_num'));
    }
    /**
     * 析构方法
     * 将cart数据保存到缓存文件
     */
     public function __destruct()
    {
        $this->clear !== true && Cache::set('cart_' . $this->user_id, $this->cart, 86400 * 15);
    }
    /**
     * 清空当前用户购物车
     * @param null $cart_ids
     */
    public function clearAll($cart_ids = null)
    {
        if (is_null($cart_ids)) {
            $this->clear = true;
            Cache::rm('cart_' . $this->user_id);
        } else {
            $this->delete($cart_ids);
        }
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
    private function hasError()
    {
        return !empty($this->error);
    }
    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}