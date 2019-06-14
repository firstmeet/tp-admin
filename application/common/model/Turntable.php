<?php
namespace app\common\model;
use think\facade\Request;
use think\db;
use app\common\model\Prize as PrizeModel;
class Turntable extends BaseModel
{
	protected $name = 'game';
	public  function prize()
	{
		return $this->hasMany('Prize','game_id','id')->order('ratio asc');
	}
	public  function lists()
	{
		return $this->hasMany('PrizeList','game_id','id');
	}
	/**
     * 活动列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getList($type)
    {
        return db('game')
            ->where('type','=',$type)
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }
	/**
     * 添加活动
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $data['app_id'] = self::$app_id;
		$times= explode(' - ',$data['section']);
		$data['start_time'] = strtotime($times[0]);
		$data['end_time'] = strtotime($times[1]);
        // 开启事务
        Db::startTrans();
        try {
            // 添加活动
            $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
        return false;
    }
	/**
     * 编辑活动
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        $data['app_id'] = self::$app_id;
		$times= explode(' - ',$data['section']);
		$data['start_time'] = strtotime($times[0]);
		$data['end_time'] = strtotime($times[1]);
        $where['app_id']=$data['app_id'];
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)
                 ->save($data,$where);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }
	/**
     * 获取活动详情
     * @param $id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($id)
    {	
        $model = new static;
        return $model->where('id', '=', $id)->find();
    }
	/**
     * 修改活动状态
     * @param $id
     * @return static|false|\PDOStatement|string|\think\Model
     */
	public function updates($id,$type,$status)
	{
		if($this->prizes($id) <  activity()[$type]['number'] && (int)$status === 0){
			return false;
		}
		$where[] = ['type','=',$type];
		$status = (int)$status ? 0 : 1;
		Db::startTrans();
        try {
            // 保存商品
            $this->where($where)->update(['status' => 0]);
            $this->where('id','=',$id)->update(['status' => $status]);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
	}
	/**
     * 获取活动
     * @param $type
     * @return static|false|\PDOStatement|string|\think\Model
     */
	public function getPrize($type)
	{
		$where[] = ['status','=',1];
		$where[] = ['type','=',$type];
		return $this->where($where)->whereBetweenTimeField('start_time','end_time')->with(['prize','lists'])->find();
	}
	/**
     * 获取活动奖品总数
     * @param $type
     * @return static|false|\PDOStatement|string|\think\Model
     */
	public function prizes($type)
	{
		return (new PrizeModel)->where('game_id','=',$type)->count();
	}
	/**
     *活动详情
     * @param $id
     * @return static|false|\PDOStatement|string|\think\Model
     */
	public static function numturntable($id)
	{
		return db('game')->where('id','=',$id)->find();
	}
	
	
	public function activity()
{
	return [
		1=>[
			'name' => '大转盘',
			'type' => 1,
			'number' => 6
		],
		2=>[
			'name' => '水果机',
			'type' => 2,
			'number' => 8
		],
		3=>[
			'name' => '九宫格',
			'type' => 3,
			'number' => 8
		]
	];
}
}