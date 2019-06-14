<?php
namespace app\common\model;
/**
 * 商品SKU模型
 * Class GoodsSku
 * @package app\common\model
 */
class ItemAttr extends BaseModel
{
    protected $name = 'item_attr';
    protected $pk = 'goods_attr_id';
	
	public function getAll($goods){
		$model = new static;
		$where[]=['goods_id','=',$goods];
		$list = $model->where($where)->select();
		return $list;
	}
}