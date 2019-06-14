<?php
namespace app\common\model;
/**
 * 商品分类模型
 * Class Category
 * @package app\common\model
 */
class Type extends BaseModel
{
    protected $name = 'item_type';
	protected $pk = 'id';
	/**
     * 商品类型  用于设置商品的属性
     */
    public function typeList(){
		 $model = new static;
		return $model->order(['id' => 'desc'])->paginate(10,false,[
			'path'=>'index.php?s=/user/item/itemtype',
		]);
	}
	/*
	*添加商品类型
	*/
	public function saveType($data)
    {
		$data['app_id'] = self::$app_id;
		return $this->allowField(true)->save($data);
	}
    /*
*编辑商品类型
*/
    public function editType($data){
        $data['app_id'] = self::$app_id;
        $where=['id','=',$data['id']];
        return $this->allowField(true)->save($data,$where);
    }
    /**
     * 删除类型
     * @param $category_id
     * @return bool|int
     */
    public function remove($id)
    {
        return $this->delete();
    }
    /**
     * 获取类型详情
     * @param $id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail()
    {
        $model = new static;
        return $model->column('name','id');
    }
}