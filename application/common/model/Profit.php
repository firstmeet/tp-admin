<?php
namespace app\common\model;
use think\facade\Request;
/**
 * 分销商订单模型
 * Class Apply
 * @package app\common\model\dealer
 */
class Profit extends BaseModel
{
    protected $name = 'profit_log';
    /**
     * 订单所属用户
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
    /**
     * 订单详情信息
     * @return \think\model\relation\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('Order');
    }
    /**
     * 订单详情
     * @param $order_id
     * @return Order|null
     * @throws \think\exception\DbException
     */
    public static function detail($order_id)
    {
        return static::get(['order_id' => $order_id]);
    }
	public static function getlist($uid = ''){
		return self::where(array('user_id'=>$uid))->with(['user','order.goods'])->paginate(15, false, [
                 'query' => Request::instance()->request()
            ]);;
	}
}