<?php
namespace app\common\model;
use think\Db;
use think\facade\Request;
use think\facade\Cache;
class PrizeList extends BaseModel
{
	protected $name = 'prize_list';
	public function user()
	{
		return $this->belongsTo('User','user_id','user_id');
	}
	public  function prize()
	{
		return $this->belongsTo('Prize','prize_id','id');
	}
	/**
     * 活动列表
     * @return mixed
     * @throws \think\exception\DbException
     */
	public function getList($id,$state)
	{
		$where[] = ['game_id','=',(int)$id];
		!$state ? '' :$where[] = ['state','=',$state];
		$model = new static;
		return $model
            ->where($where)
			->with(['user','prize'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
	}
	/**
     * 添加活动
     * @param array $data
     * @return bool
     */
    public function add($id,$user)
    {
		if(!$this->activityType($id,$user)){
			return '奖品都被您抽完了,请参加下次活动';
		}
		if($user['integral'] < 15){
			return '您的积分不足,请先去赚取积分吧';
		}
		$data = $this->isPrize($this->getPrize($id));
		if(!$data){
			return '哎呀!奖品被抽光了,再试试别的吧';
		}
        $data['app_id'] = self::$app_id;
		$data['user_id'] = $user['user_id'];
		$data['phone'] = $user['phone'];
        // 开启事务
        Db::startTrans();
        try {
            // 添加活动
			$user->setDec('integral', 15);
            $this->allowField(true)->save($data);
            Db::commit();
            return $data;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
        return false;
    }
	/**
     * 获取奖品列表
     * @param $id
     * @return bool
     */
	public function getPrize($id)
	{
		return (new Prize)->getList($id);
	}
	public function activityType($id,$user)
	{
		$activity = (new Game)->get($id);
		if($activity['setup']){
			if($nums = Cache::get('user_'.$user['id'])){
				$nums < $activity['setnum'] ? Cache::set('user_'.$user['id'],$nums+1) : false;
			}
			Cache::set('user_'.$user['id'],1);
			return true;
		}
		$where[] = ['state','=',1];
		$where[] = ['user_id','=',$user['user_id']];
		$nums = $this->where($where)->count();
		return $nums < $activity['setnum'] ? true : false;
	}
	/**
     * 中奖算法
     * @param $prize_arr
     * @return static|false|\PDOStatement|string|\think\Model
     */
	public function isPrize($prize_arr)
	{
		$actor = 10000;
		foreach ($prize_arr as $v) {
			if($v['number'] < 1){
				return false;
			}else{
				$arr[] = $v['ratio']*$actor;
			}
		}
		$sum = array_sum($arr);   //总概率
		$str =md5(time(). mt_rand(1,$sum));
		if($strs = preg_replace('/[^\d]*/', '', $str)){
		   $rand = substr($strs, 0, 6);
		}
		$result = '';    //中奖产品id
		foreach ($arr as $k => $x)
		{
			if($rand <= $x)
			{
				$result = $k;
				break;
			}else{
				$rand -= $x;
			}
		}
		$res['prize_name'] = $prize_arr[$result]['name']; //中奖项
		$res['state'] = $prize_arr[$result]['is_state'];
		$res['prize_id'] = $prize_arr[$result]['id'];
		$res['game_id'] = $prize_arr[$result]['game_id'];
		$res['array'] = $arr;
		$res['rand'] = $rand;
		return $res;
	}
}