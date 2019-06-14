<?php
namespace app\common\model;
/**
 * 权限管理
 * Class appHelp
 * @package app\common\model
 */
class AuthGroup extends BaseModel
{
    protected $name = 'auth_group';
	
	public function getAll($role_id)
	{
		$data=App::getAll();
		foreach($data as $key =>$val){
			$val['selected']=$this->where(['role_id'=>$role_id,'app_id'=>$val['app_id']])->find();
		}
		return $data;
	}
	
     /**
     * 删除记录
     * @param $where
     * @return int
     */
    public static function deleteAll($data)
    {	
		
      return self::destroy($data);
    }
}
