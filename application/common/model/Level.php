<?php
namespace app\common\model;
use think\facade\Cache;
/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class Level extends BaseModel
{	
	/**
	 * 获取会员等级
	 * @param $status
	 * @return array
	 */
	private function getLevel($status)
	{        	 
		if(!$data = Cache::get($status.'level')){
			$data = $status ? $this->order('integral asc')->select() 
			: $this->order('integral asc')->column('*','key');
			Cache::set($status.'level',$data);
		}
		return $data;
	}
	/**
     * 获取会员卡
     * @param $user_id
     * @return array
     */
	public function cardNum($user_id)
	{
		$model=new Card;
		return $model->where('user_id','=',$user_id)->select();
	}
	/**
     * 获取会员参数
     * @param $userInfo
     * @return array
     */
	public function judge($userInfo)
	{
		$data['level'] = $this->getLevel(1);
		foreach($data['level'] as $k => $v){
			$data['level'][$k]['values'] = unserialize($v['values']);
			
		}
		$data['user_card'] = $this->cardNum($userInfo['user_id']);
		$data['goods'] = Item::VipGoods($userInfo['user_id']);
		$data['num'] = count($data['user_card']);
		return $data;
	}
	/**
     * 会员升级
     * @param $userInfo
     * @param $keys
     * @return array
     */
	public function upGrade($userInfo,$keys)
	{
		$res = $this->getLevel(0);
		$data = $res[$keys['key']];
		if($userInfo['level'] == 'three'){
			return false;
		}
		if($userInfo['integral'] < $data['integral']){
			return false;
		}
		$data['create_time'] = time();
		$data['user_id'] = $userInfo['user_id'];
		$data['nickName'] = $userInfo['nickName'];
		$data['card'] = rand(10000,99999).date('YmdHis');
		$datas['level'] =$data['key']; 
		$datas['agio'] =$data['agio']; 
		$userInfo->save($datas);
		$model=new Card;
		return  $model->strict(false)->insert($data);
	}
	/**
     * 会员列表
     * @return array
     */
	
	public function getList(){
		return (new Card())->paginate(15, false,[
            'query' => \request()->request()
        ]);
	}
}