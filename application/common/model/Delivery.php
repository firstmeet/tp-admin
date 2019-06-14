<?php
namespace app\common\model;
use think\facade\Request;
/**
 * 配送模板模型
 * Class Delivery
 * @package app\common\model
 */
class Delivery extends BaseModel
{
    protected $naem='delivery';
	protected $pk = 'delivery_id';
    /**
     * 关联配送模板区域及运费
     * @return \think\model\relation\HasMany
     */
    public function rule()
    {	
        return $this->hasMany('DeliveryRule','delivery_id','delivery_id');
    }
    /**
     * 计费方式
     * @param $value
     * @return mixed
     */
    public function getMethodAttr($value)
    {
        $method = [10 => '按件数', 20 => '按重量'];
        return ['text' => $method[$value], 'value' => $value];
    }
    /**
     * 获取全部
     * @return mixed
     */
    public static function getAll()
    {
        $model = new static;
        return $model->order(['sort' => 'asc'])->select();
    }
    /**
     * 获取列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->with(['rule'])
            ->order(['sort' => 'asc'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }
    /**
     * 运费模板详情
     * @param $delivery_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($delivery_id)
    {
		if($delivery_id){
          return self::get($delivery_id, ['rule']);
        }
    }
	    /**
     * 添加新记录
     * @param $data
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function add($data)
    {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }
        $data['app_id'] = self::$app_id;
		
        if ($this->allowField(true)->save($data)) {
            return (new DeliveryRule)->createDeliveryRule($data['rule'],$this->delivery_id);
        }
        return false;
    }
    /**
     * 添加物流
     * @param $data
     * @return false|int
     */
    public function addExpress($data)
    {
        $data['app_id'] = self::$app_id;
        return $this->allowField(true)->save($data);
    }
    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function edit($data) {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }
        $data['app_id'] = self::$app_id;
        $where=['delivery_id','=',$data['delivery_id']];
        if ($this->allowField(true)->save($data,$where)) {
           return (new DeliveryRule)->createDeliveryRule($data['rule'],$data['delivery_id']);
        }
        return false;
    }
  
    /**
     * 删除记录
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function remove()
    {
        // 判断是否存在商品
        if ($goodsCount = (new Item)->where(['delivery_id' => $this['delivery_id']])->count()) {
            $this->error = '该模板被' . $goodsCount . '个商品使用，不允许删除';
            return false;
        }
        $this->rule()->delete();
        return $this->delete();
    }
	 /**
     * 计算配送费用
     * @param $total_num
     * @param $total_weight
     * @param $city_id
     * @return float|int|mixed
     */
    public function calcTotalFee($total_num, $total_weight, $city_id)
    {
        $rule = [];  // 当前规则
        foreach ($this['rule'] as $item) {
            if (in_array($city_id, explode(',',$item['region']))) {
                $rule = $item;
                break;
            }
        }
        // 商品总数量or总重量
        $total = $this['method']['value'] === 10 ? $total_num : $total_weight;
        if ($total <= $rule['first']) {
            return number_format($rule['first_fee'], 2);
        }
        // 续件or续重 数量
        $additional = $total - $rule['first'];
        if ($additional <= $rule['additional']) {
            return number_format($rule['first_fee'] + $rule['additional_fee'], 2);
        }
        // 计算续重/件金额
        if ($rule['additional'] < 1) {
            // 配送规则中续件为0
            $additionalFee = 0.00;
        } else {
            $additionalFee = bcdiv($rule['additional_fee'], $rule['additional'], 2) * $additional;
        }
        return number_format($rule['first_fee'] + $additionalFee, 2);
    }
    /**
     * 验证用户收货地址是否存在运费规则中
     * @param $city_id
     * @return bool
     */
    public function checkAddress($city_id)
    {
        $cityIds = explode(',', implode(',', array_column($this['rule']->toArray(), 'region')));
        return in_array($city_id, $cityIds);
    }
    /**
     * 根据运费组合策略 计算最终运费
     * @param $allExpressPrice
     * @return float|int|mixed
     */
    public static function freightRule($allExpressPrice)
    {
        $freight_rule = Setting::getItem('trade')['freight_rule'];
        $expressPrice = 0.00;
        switch ($freight_rule) {
            case '10':    // 策略1: 叠加
                $expressPrice = array_sum($allExpressPrice);
                break;
            case '20':    // 策略2: 以最低运费结算
                $expressPrice = min($allExpressPrice);
                break;
            case '30':    // 策略3: 以最高运费结算
                $expressPrice = max($allExpressPrice);
                break;
        }
        return $expressPrice;
    }
}