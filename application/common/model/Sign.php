<?php
namespace app\common\model;
use think\Db;
class Sign extends BaseModel
{
	protected $name = 'sign';
	protected $hidden = [
        'app_id',
    ];
	/**
     * 签到接口
     * @param $user
     * @param $app
     * @return false|object
     */
	public function checks($user,$app)
	{
		if($res = $this->isUser($user['user_id'])){
			return false;
		}else{
			return $this->isSign($user,$app);
		}
	}
	/**
     * 签到
	 * @param $user
	 * @param $app
     * @return false|true
     */
	private function isSign($user,$app)
	{
		$data['day'] = date('Ymd');
		$data['user_id'] = $user['user_id'];
		$data['app_id'] = $app['app_id'];	

		User::sign($user['user_id']) || false;
		Db::startTrans();
        try {
            $this->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
	}
	/**
     * 判断是否签到
     * @param $user_id
     * @return false|object
     */
	private function isUser($user_id)
	{
		$where['user_id'] = $user_id;
		$where['day'] = date('Ymd');
		return $this->where($where)->find();
	}
	/**
     * 获取签到信息
	 * @param $user
     * @return array
     */
	public function getAlls($user)
	{
		
		$day = date('Ymd');
		$where['user_id'] = $user['user_id'];
		$data = $this->where($where)->order('create_time','desc')->limit(4)->column('day');
		$start = time()-86400*3;
		$end = time()+86400*4;
		for($start ; $start < $end; ){
			$str['day'] = date('Ymd',$start) == $day ? '今天' : date('m/d',$start);
			$str['status'] = in_array((int)date('Ymd',$start),$data);
			$list['usersign'][]=$str;
			$start = $start+86400;
		}
		$list['sign_log'] = $this->signLog();
	
		return $list;
	}
	/**
     * 获取签到排行
     * @return array
     */
	private function signLog()
	{
		return (new User)->order('sign desc')->limit(10)->field('nickName,avatarUrl,sign')->select();
	}
}