<?php
namespace app\common\model;
use think\facade\Request;
use think\facade\Cache;
/**
 * 商品模型
 * Class Item
 * @package app\common\model
 */
class Item extends BaseModel
{
    protected $name = 'item';
    protected $pk = 'goods_id';
	 /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'sales_initial',
        'sales_actual',
        'app_id',
        'create_time',
        'update_time'
    ];
	/**
     * 商品详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }
    /**
     * 根据商品id集获取商品列表
     * @param $goodsIds
     * @param null $status
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByIds($goodsIds, $status = null)
    {
        $filter[] = ['goods_id','in', $goodsIds];
        $status > 0 && $filter[] = ['is_on_sale','=',$status];
        return $this->with(['category', 'image.file','price','delivery'])
            ->where($filter)
            ->select();
    }
	/**
     * 更新商品库存销量
     * @param $goodsList
     * @throws \Exception
     */
    public function updateStockSales($goodsList)
    {
        // 整理批量更新商品销量
        $goodsSave = [];
        // 批量更新商品规格：sku销量、库存
        $goodsSpecSave = [];
        foreach ($goodsList as $Item) {
            $goodsSave[] = [
                'goods_id' => $Item['item_id'],
                'sales_sum' => ['inc', $Item['total_num']]
            ];
            // 付款减库存
            if ($Item['type'] === 20) {
				// 更新商品规格库存
				(new SpecItemPrice)->where('key','=',$Item['spec_sku_id'])->setDec('store_count',$Item['total_num']);
            }
        }
        // 更新商品总销量
        $this->allowField(true)->isUpdate()->saveAll($goodsSave);
    }
	/**
     * 关联商品图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('UploadType','link_id')->order(['id' => 'asc']);
    }
	/**
     * 关联运费模板表
     * @return \think\model\relation\BelongsTo
     */
    public function delivery()
    {
        return $this->BelongsTo('Delivery','','delivery_id');
    }
	   /**
     * 关联商品分类表
     * @return \think\model\relation\BelongsTo
     */
	public function category()
    {
       return $this->belongsTo('Category','cat_id');
    }
	/**
	*获取商品价格。
	*/
	public function price(){
		return $this->BelongsTo('SpecItemPrice','goods_id','goods_id');
	}
	/**
     * 关联商品规格关系表
     * @return \think\model\relation\BelongsToMany
     */
    public function specRel()
    {
        return $this->hasMany('Spec','type_id','spec_type')->with('specItem');
    }
	 /**
     * 关联商品规格表
     * @return \think\model\relation\HasMany
     */
    public function sku()
    {
        return $this->hasMany('SpecItemPrice','goods_id','goods_id');
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
        $this->image()->delete();
        $data = array_map(function ($image_id) {
            return [
                'image_id' => $image_id,
				'type'=>'item',
				'link_id'=>$this['goods_id'],
                'app_id' => self::$app_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
   }
   	 /**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $goods_id 商品id
     */
	function saveAfter($sku,$goods_id)
    {
        var_dump($sku);
        foreach ($sku as $k=>$v){
            $v['key']=$k;
            $key_split=explode("_",$k);
            $spec=SpecItem::whereIn("id",$key_split)->select();
            $key_name=implode(",",array_column($spec,"item"));
            $v['key_name']=$key_name;
            $v['app_id']=self::$app_id;
            $v['goods_id']=$goods_id;
            $list[]=$v;
        }
        dump($list);
        $SpecItemPrice= new SpecItemPrice;
        $SpecItemPrice->where('goods_id','=',$goods_id)->delete();
        $SpecItemPrice->allowField(true)->insertAll($list);
	}
	/**
	 * 获取某个商品分类的 儿子 孙子  重子重孙 的 id
	 * @param type $cat_id
	 */
	function getCatGrandson ($cat_id)
	{
		$GLOBALS['catGrandson'] = array();
		$GLOBALS['category_id_arr'] = array();
		// 先把自己的id 保存起来
		$GLOBALS['catGrandson'][] = $cat_id;
		// 把整张表找出来
		$GLOBALS['category_id_arr'] = (new Category)->column('id,pid');
		// 先把所有儿子找出来
	   $son_id_arr = (new Category)->where('pid', '=' ,$cat_id)->column('id');
		foreach($son_id_arr as $k => $v)
		{	
			getCatGrandson2($v);
		} 
		return $GLOBALS['catGrandson'];
	}
	 /**
     * 获取商品列表
     * @param int $status
     * @param int $category_id
     * @param string $search
     * @param string $sortType
     * @param bool $sortPrice
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
	public function getList($status = 0, $categoryId = 0, $search = '',$market = '',$goodsId = '', $class = 1, $sortType = 'all', $sortPrice = false, $listRows = 15)
    {
        // 筛选条件
        $filter = [];
		if($categoryId > 0)
        { 
           $filter[] =['cat_id','=',$categoryId];
        }
        $status > 0 && $filter[] = ['is_on_sale','=', $status];
        !empty($search) && $filter[] = ['goods_name','like', '%' . trim($search) . '%'];
		!empty($market) && $filter[] = ['prom_type','=', 0];
		!empty($goodsId) && $filter[] = ['goods_id','notin', $goodsId];
        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['sort', 'goods_id' => 'desc'];
        } elseif ($sortType === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($sortType === 'price') {
            $sort = $sortPrice ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
		$list = $this
            ->where($filter)
            ->order($sort)
			->with(['sku','image.file','category'])
            ->paginate($listRows, false, [
                'query' => Request::instance()->request()
            ]);
        return $list;
    }
	/**
     * 获取商品详情
     * @param $goods_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($goods_id)
    {
        if($goods_id){
            $model = new static;						
            return $model->with([
                'specRel',
                'image.file',
                'delivery.rule',
				'sku',
            ])->where('goods_id', '=', $goods_id)->find();
        }
    }
	/**
     * 修改商品状态
     * @param $state
     * @return false|int
     */
    public function setStatus($state)
    {
        return $this->save(['is_on_sale' => $state==2? 1 : 2]) !== false;
    }
	/**
     * 删除商品
     * @return false|int
     */
    public function setDelete()
    {
        return $this->delete();
    }
    /**
     *  获取排好序的品牌列表
     */
    public function getSortBrands()
    {
        return (new Brand)->all();
    }
	public function add(array $data){
		 // 商品图片
		if (!isset($data['goods_images']) || empty($data['goods_images'])) {
			$this->error = '请上传商品图片';
			return false;
		}	
			//商品规格
		if (!isset($data['sku']) || empty($data['sku'])) {
			$this->error = '请设置商品规格';
			return false;
		}
//		if (!isset($data['attr']) || empty($data['attr'])) {
//            $this->error = '请添加商品属性';
//            return false;
//        }
		$data['app_id'] = self::$app_id;
		// 开启事务
		$this->allowField(true)->save($data); // 写入数据到数据库
		// 商品图片
		$this->addGoodsImages($data['goods_images']);
		// 处理商品图片 规格
		$this->saveAfter($data['sku'],$this['goods_id']);
		if (isset($data['attr'])&&!empty($data['attr']))
		$this->saveGoodsAttr($this['goods_id'],$data['attr']); // 处理商品 属性
        return true;
	}
	  /**
     * 编辑商品
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
		if (!isset($data['goods_images']) || empty($data['goods_images'])) {
			$this->error = '请上传商品图片';
			return false;
		}
        //商品规格
        if (!isset($data['sku']) || empty($data['sku'])) {
            $this->error = '请添加商品规格';
            return false;
        }
//		if (!isset($data['attr']) || empty($data['attr'])) {
//            $this->error = '请添加商品属性';
//            return false;
//        }
        $data['app_id'] = self::$app_id;
        $where=['goods_id','=',$data['goods_id']];
		// 保存商品
		$this->allowField(true)->save($data,$where);
		// 处理商品图片 规格
		$this->addGoodsImages($data['goods_images']);
		$this->saveAfter($data['sku'],$this['goods_id']);
		if (isset($data['attr'])){
            $this->saveGoodsAttr($this['goods_id'],$data['attr']); // 处理商品 属性
        }

		return true;
    }
	/**
     *  给指定商品添加属性
     * @param int $goods_id  商品id
     * @param int $goods_type  商品类型id
     */
    public function saveGoodsAttr($goods_id,$dataitem)
    {
        $GoodsAttr = new ItemAttr();
        $GoodsAttr->where('goods_id = '.$goods_id)->delete();
        foreach ($dataitem as $k=>$v){
            $data['goods_id'] = $goods_id;
            $data['app_id']=self::$app_id;
            $data['attr_id']=$k;
            $data['attr_value']=$v;
            $addAll[]=$data;
        }
        $GoodsAttr->saveAll($addAll);
    }
	  /**
     * 修改商品活动类型
     * @param array $where
     * @return int|string
     */
	public function saveItems($goods_id = '', $type = '',$activity_id = '',$discount = ''){
		if(is_array($goods_id)){
			$goods_id = implode(',',$goods_id);
		}
			$where[]=['goods_id','in',$goods_id];
		  return $this->where($where)->update(['prom_type'=>$type,'prom_id'=>$activity_id,'discount'=>$discount]);
	}
	/**
     * 获取当前商品总数
     * @param array $where
     * @return int|string
     */
    public function getGoodsTotal($where = [])
    {
        //$this->where('is_delete', '=', 0);
        return $this->where($where)->count();
        return $this->count();
    }
	/**
     * 获取模版商品列表
     * @param int $status
     * @param int $category_id
     * @param $type
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
	public function tplgetList($status = 0, $type = 0, $listRows = 15)
    {
        // 筛选条件
        $filter = [];
        $status > 0 && $filter[] = ['is_on_sale','=', $status];
		switch ($type)
		{
			case "assemble":
				$prom_type=1;
				break;
			case "spike":
				$prom_type=2;
				break;
			case "robbuy":
				$prom_type=3;
				break;
			case "promgoods":
				$prom_type=4;
				break;
			default:
				$prom_type=0;
	   }
		$filter[] = ['prom_type','=', $prom_type];
        // 多规格商品 最高价与最低价
            $ItemSku = new Item;
		return $this
            ->with('price,image.file')->where($filter)
            ->order("goods_id desc")
            ->paginate($listRows, false, [
                'query' => Request::instance()->request()
            ]);
    }
	public static function VipGoods($user_id){
			$model=new static;
			$data = $model->order('create_time desc')
			->where('goods_name','neq','')->with("image.file,price")
			->field('goods_name,give_integral,goods_id')
			->limit(4)
			->select();
			Cache::set($user_id.'items',$data,86400);
		return $data;
	}
	/**
     * 商品多规格信息
     * @param $item_sku_id
     * @return array|bool
     */
    public function getGoodsSku($item_sku_id)
    {
        $goodsSkuData = array_column($this['sku']->toArray(), null, 'key');
		if (!isset($goodsSkuData[$item_sku_id])) {
            return false;
        }
        $goods_sku = $goodsSkuData[$item_sku_id];
        // 多规格文字内容
        $goods_sku['goods_attr'] = '';
        if ($this['spec_type'] > 0) {
			$goods_sku['goods_attr'] = $goods_sku['key_name'];
        }
        return $goods_sku;
    }
}