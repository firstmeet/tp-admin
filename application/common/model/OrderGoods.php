<?php
namespace app\common\model;
/**
 * 订单商品模型
 * Class OrderGoods
 * @package app\common\model
 */
class OrderGoods extends BaseModel
{
    protected $name = 'order_goods';
	protected $pk = 'order_goods_id';
    /**
     * 订单商品列表
     * @return \think\model\relation\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo('UploadFile', 'image_id', 'id');
    }
    /**
     * 关联商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Item');
    }
    /**
     * 关联商品sku表
     * @return \think\model\relation\BelongsTo
     */
    public function sku()
    {
        return $this->belongsTo('ItemSku', 'spec_sku_id', 'spec_sku_id');
    }
    /**
     * 关联订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderM()
    {
        return $this->belongsTo('Order');
    }
	/**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'content',
        'app_id',
        'create_time',
    ];
    /**
     * 获取未评价的商品
     * @param $order_id
     * @return OrderGoods[]|false
     * @throws \think\exception\DbException
     */
    public static function getNotCommentGoodsList($order_id)
    {
        return self::all(['order_id' => $order_id, 'is_comment' => 0], ['orderM', 'image']);
    }
}