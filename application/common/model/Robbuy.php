<?php
namespace app\common\model;
use think\facade\Request;
use think\Db;
/**
 * 抢购活动模型
 * Class Robbuy
 * @package app\common\model
 */
class Robbuy extends BaseModel
{
    protected $name = 'robbuy';
    protected $pk = 'robbuy_id';
 /**
     * 关联拼团商品
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
	 public function items(){
		return $this->belongsTo('item','item_id');
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
        // 筛选条件
        $filter = [];
        $category_id > 0 && $filter[] = ['category_id','IN', Category::getSubCategoryId($category_id)];
        $status > 0 && $filter[] = ['status','=', $status];
        !empty($search) && $filter[] = ['name','like', '%' . trim($search) . '%'];
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['sort', 'robbuy_id' => 'desc'];
        } elseif ($sortType === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($sortType === 'price') {
            $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
        $list = $this
            ->where($filter)
            ->order($sort)
            ->paginate($listRows, false, [
                'query' => Request::instance()->request()
            ]);
        return $list;
    }
      /**
     * 获取商品详情
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($robbuy_id)
    {
        $model = new static;
        return $model->where('robbuy_id', '=', $robbuy_id)->find();
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
            // 添加商品
            $result = $this->allowField(true)->save($data);
				//修改商品分类
			$activity_id=$this['robbuy_id'];
			$model = new Item;
			$model->saveitems($data['item_id'],3,$activity_id);
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
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)->update($data);
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