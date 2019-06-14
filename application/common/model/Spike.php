<?php
namespace app\common\model;
use think\facade\Request;
use think\Db;
/**
 * 商品模型
 * Class Item
 * @package app\common\model
 */
class Spike extends BaseModel
{
    protected $name = 'spike';
    protected $pk = 'spike_id';
 /**
     * 关联拼团商品
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
	 public function items()
	 {
		return $this->belongsTo('Item','item_id');
	 }
    /**
     * 获取秒杀活动列表
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
            $sort = ['sort', 'spike_id' => 'desc'];
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
     * 获取活动详情
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($spike_id)
    {	
        $model = new static;
        return $model->with('items')->where('spike_id', '=', $spike_id)->find();
    }
	 /**
     * 添加秒杀活动
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $data['app_id'] = self::$app_id;
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
        // 开启事务
        Db::startTrans();
        try {
			 $data['development_time']=implode(',',$data['development_time']);
            // 添加活动
            $result = $this->allowField(true)->save($data);
			//修改商品分类
			$spike_id=$this['spike_id'];
			$model = new Item;
			$model->saveitems($data['item_id'],2,$spike_id,$data['discount']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }
        return false;
    }
    /**
     * 修改活动
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        $data['app_id'] = self::$app_id;
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		$where=['spike_id','=',$data['spike_id']];
        // 开启事务
		/*
		*查看当前
		**/
		$model = new Item;
		 $list=$this->allowField(true)->find();
		 if($list['item_id']!=$data['item_id'])
		 $model->saveitems($list['item_id'],0,0);//将原商品恢复为普通商品
		 $data['development_time']=implode(',',$data['development_time']);
		//保存商品
		$this->allowField(true)->save($data,$where);
		//修改商品类别
		$activity_id=$data['spike_id'];
		$model->saveitems($data['item_id'],2,$activity_id,$data['discount']);
		return true;
    }
    /**
     * 删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->delete();
    }
	public function discounts($res)
	{
		for($i = 0; $i < count($res); $i++){
			$res[$i]['items']['sku'][0]['shop_price'] = number_format($res[$i]['items']['sku'][0]['shop_price'] * ($res[$i]['discount'] / 10),2);
		}
		return $res;
	}
	/**
     * 秒杀列表
     * @return false|int
     */
	public static function spikeList($time = '')
	{
		$model = new static;
		$res = $model
			->where("FIND_IN_SET({$time},development_time)")
			->whereBetweenTimeField('start_time','end_time')
			->with(['items'=>['image.file','sku']])
			->select();
		return $model->discounts($res);
	}
}