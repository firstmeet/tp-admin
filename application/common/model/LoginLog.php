<?php
namespace app\common\model;
use think\facade\Request;
/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class LoginLog extends BaseModel
{
    protected $name = 'login_log';

    
	public function addlog()
	{
		$request = Request::instance();
		$data['user_id']=self::$user_id;
		$data['ip']=$request->ip();
		$this->save($data);
	}
	public function user()
	{
	
		return $this->hasOne('WebUser','user_id','user_id');
	}
	public function getAll()
	{
		return $this->with('user.role')
		->where('user_id','=',self::$user_id)
		->whereOr('user_id','=',self::$is_super)
		->order('id desc')
		->paginate(10, false, [
                'query' => Request::instance()->request()]);
	}
}