<?php
namespace app\common\model;
use think\facade\Request;
use think\Db;
/**
 * 模型
 * Class Item
 * @package app\common\model
 */
class Assemble extends BaseModel
{
    protected $name = 'assemble';
    protected $pk = 'assemble_id';
 /**
     * 关联拼团商品
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
	 public function items(){
		return $this->belongsTo('item','item_id')->where('prom_type',1);
	 }
	 /**
     * 添加拼团活动
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['sales_actual'] = 0;
        // 开启事务
        Db::startTrans();
        try {
            // 添加商品
            $this->allowField(true)->save($data);
			//修改商品分类
			$activity_id=$this['assemble_id'];
			$model = new Item;
			$model->saveitems($data['item_id'],1,$activity_id,$data['rebate']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
        return false;
    }
	 /**
     * 编辑拼团活动
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        $data['app_id'] = $data['sku']['app_id'] = self::$app_id;
        $where=['assemble_id','=',$data['assemble_id']];
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)->save($data,$where);
			//修改商品类别
			$activity_id=$this['assemble_id'];
			$model = new Item;
			$model->saveitems($data['item_id'],1,$activity_id,$data['rebate']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
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
        $category_id > 0 && $filter[] = ['id','IN', Category::getSubCategoryId($category_id)];
        $status > 0 && $filter[] = ['status','=', $status];
        !empty($search) && $filter[] = ['name','like', '%' . trim($search) . '%'];
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['sort', 'assemble_id' => 'desc'];
        } elseif ($sortType === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($sortType === 'price') {
            $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
        $list = $this
            ->with(['items'])
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
    public static function detail($assemble_id)
    {
        if($assemble_id)
        {
            $model = new static;
            return $model->with([
                'items',
            ])->where('assemble_id', '=', $assemble_id)->find();
        }
    }
}