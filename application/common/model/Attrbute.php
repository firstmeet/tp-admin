<?php
namespace app\common\model;
use think\Db;
/**
 * 属性(组)模型
 * Class Spec
 * @package app\common\model
 */
class Attrbute extends BaseModel
{
    protected $name = 'item_attribute';
    protected $pk = 'attr_id';
    protected $updateTime = false;
	/**
	*新增属性
	*/
    public function addattrbute($data,$id)
    {
        $app_id = self::$app_id;
		$data['app_id']=$app_id;
         // 开启事务
        Db::startTrans();
		 try {
            // 添加修改属性
			$where=array();
			!empty($id)?$where[]=['attr_id','=',$id]:''; 
            $result = $this->allowField(true)->save($data,$where);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
        return false;
    }
	 /**
	 * 获取列表
	 * Class Spec
	 * @package app\common\model
	 */
	public function getlist($type){
		$where[]= $type ?  ['type_id','=',$type] : ['type_id','>',$type] ; 
		$list=$this->order("attr_id desc")->where($where)->paginate(10,false,[
		'path'=>'index.php?s=/user/item/attrbute',
		]);
		return $list;
	}
}