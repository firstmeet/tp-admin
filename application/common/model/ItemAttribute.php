<?php
namespace app\common\model;
/**
 * 商品SKU模型
 * Class GoodsSku
 * @package app\common\model
 */
class ItemAttribute extends BaseModel
{
    protected $name = 'item_attribute';
    protected $pk = 'attr_id';
	public static $goods;
	public function itemAttr()
    {
		$goods = self::$goods;
		$where=[];
		if(self::$goods)$where['goods_id']=self::$goods;
        return $this->hasMany('ItemAttr','attr_id','attr_id')->where($where);
    }
	public function getAll($goods_id,$type_id){
		self::$goods=$goods_id;
		$model = new static;
		$where[]=['type_id','=',$type_id];
		$list = $model->with('itemAttr')->where($where)->select();
		return $list;
	}
}