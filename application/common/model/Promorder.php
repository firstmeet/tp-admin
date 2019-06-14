<?php
namespace app\common\model;
use think\facade\Request;
use think\Db;
/**
 * 商品模型
 * Class Item
 * @package app\common\model
 */
class Promorder extends BaseModel
{
    protected $name = 'prom_order';
	protected $pk= 'prom_id';
 /**
     * 关联拼团商品
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
	 public function items(){
		return $this->hasMany('item','prom_id')->where("prom_type=4")->with(['sku']);
	 }
    /**
     * 获取拼团商品列表
     * @param int $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($status = null, $category_id = 0, $search = '', $sortType = 'all', $sortPrice = false, $listRows = 15)
    {	
		$sort = ['prom_id' => 'desc'];
		return $this
            ->order($sort)
            ->paginate($listRows, false, [
                'query' => Request::instance()->request()
            ]);
    }
      /**
     * 获取商品详情
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($prom_id)
    {
        if ($prom_id){
            $model = new static;
             return $model->where('prom_id', '=', $prom_id)->find();
        }
    }
	    /**
     * 添加商品
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $data['app_id'] = self::$app_id;
        // 开启事务
        Db::startTrans();
        try {
			$data['start_time'] = strtotime($data['start_time']);
			$data['end_time'] = strtotime($data['end_time']);
			$data['group'] = implode(',', $data['group']);
            // 添加活动
            $result = $this->allowField(true)->save($data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
        return false;
    }
    /**
     * 添加商品图片
     * @param $images
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function addGoodsImages($images)
    {	
         $this->image()->where('assemble_id',"=",$this['assemble_id'])->delete();
         $data = array_map(function ($image_id) {
            return [
                'image_id' => $image_id,
                'assemble_id' => $this['assemble_id'],
                'app_id' => self::$app_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
    }
    /**
     * 编辑商品
     * @param $data
     * @return bool
     */
       public function edit($data)
    {
        $data['app_id'] = self::$app_id;
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        $data['group'] = implode(',', $data['group']);
        $where=['prom_id','=',$data['prom_id']];
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)->save($data, $where);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }
    /**
     * 删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->delete();
    }
}