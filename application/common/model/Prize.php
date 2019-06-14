<?php
namespace app\common\model;
use think\facade\Request;
use think\db;
class Prize extends BaseModel
{
	protected $name = 'prize';
	/**
     * 活动列表
     * @return mixed
     * @throws \think\exception\DbException
     */
	public function getList($id)
	{
		$where[] = ['game_id','=',$id];
		return $this
            ->where($where)
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
		if($this->checkRatio($data['game_id'],$data['ratio'])){
			return false;
		}
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
		 $where=['id','=',$data['id']];
		if($this->checkRatio($data['game_id'],$data['ratio'],$data['id'])){
			return false;
		}
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)->save($data,$where);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }
	/**
     * 判断奖品概率
     * @param $game_id
	 * @param $ratio
	 * @param $id
     * @return bool
     */
	public function checkRatio($game_id, $ratio = 0, $id = 0 )
	{
		!!$id && $where[] = ['id','neq',$id];
		$where[] = ['game_id','eq',$game_id];
		$res = $this->where($where)->sum('ratio');
		return ($res + (int)$ratio) > 100 ? true : false;
	}
	/**
     * 获取活动详情
     * @param $id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($id)
    {
        if ($id){
            $model = new static;
            return $model->where('id', '=', $id)->find();
        }
    }
}