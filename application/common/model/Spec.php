<?php
namespace app\common\model;
/**
 * 规格/属性(组)模型
 * Class Spec
 * @package app\common\model
 */
class Spec extends BaseModel
{
    protected $name = 'spec';
    protected $pk = 'id';
    protected $updateTime = false;
	public function gettype(){
		return $this->belongsTo('type','type_id','id');
	}
	 public function specItem()
    {
		$request=request()->get();
		$where=array();
		if(isset($request['item_id'])&&!empty($request['item_id'])){
			$keys =str_replace('_',',',db('spec_item_price')->where(array('goods_id'=>$request['item_id']))->value("GROUP_CONCAT(`key` SEPARATOR '_') "));
			$where[]=['id','in',$keys];
		}
        return $this->hasMany('SpecItem','spec_id','id')->where($where);
    }
    /**
     * 根据规格组名称查询规格id
     * @param $spec_name
     * @return mixed
     */
    public function getSpecIdByName($spec_name)
    {
        return self::where(compact('spec_name'))->value('spec_id');
    }
     /**
     * 增加
     * @param $spec_name
     * @return mixed
     */
    public function add($data)
        {
            $data['app_id'] = self::$app_id;
            $this->allowField(true)->save($data);
           return (new SpecItem())->saveAfter($this->id,$data['items']);
        }
	/**
     * 获取
     * @param $spec_name
     * @return mixed
     */
    public function getAll($type){
        $model = new static();
        return $model->where('type_id ='.$type)->with('specItem')->select();
    }
	/**
	*
	*规格列表
	*/
	public function getList($type_id = ''){
		$where=array();
		!empty($type_id)?$where[]=['type_id','=',$type_id]:'';
		$speclist=$this->where($where)->with('gettype')->order("id desc")->paginate(10,false,[
		'path'=>'index.php?s=/user/item/speclist',
		]);
		foreach($speclist as $k => $v)
        {      
			// 获取规格项     
            $arr = (new SpecItem())->getSpecItem($v['id']);
            $speclist[$k]['spec_item'] = implode(' , ', $arr);
        }
		return $speclist;
	}
	/**
	*商品类型
	*/
	public function type(){
		return (new Type())->select();
	}
		  /**
     * 获取 规格的 笛卡尔积
     * @param $goods_id 商品 id     
     * @param $spec_arr 笛卡尔积
     * @return string 返回表格字符串
     */
    public function getSpecInput($goods_id, $arr)
    {
        $spec_arr = $this->combineDika($arr); //  获取 规格的 笛卡尔积
		$spec = $this->column('id,name'); // 规格表
		$specItem =(new SpecItem)->column('id,item,spec_id');//规格项 
		if($goods_id)
		$keySpecGoodsPrice = (new SpecItemPrice)->where('goods_id = '.$goods_id)->column('key,price,shop_price,store_count,sku');//规格项
	    foreach($spec_arr as $k=>$v){
		    $name=array();
		    $list[$k]['id']=implode('_', array_values($v));
		    foreach($v as $i){
			   $name[]=$specItem[$i]['item'];
		    }
		    $list[$k]['name'] = $name;
		    $list[$k]['form'] = isset($keySpecGoodsPrice[$list[$k]['id']]) ? $keySpecGoodsPrice[$list[$k]['id']]  : 0;
	    }
		$data['list']=$list;
		foreach(array_keys($arr) as $s){
			$title[] = $spec[$s];
		}
		$data['title'] = $title;
	    return $data;
    }
	/**
	 * 多个数组的笛卡尔积
	*
	* @param unknown_type $data
	*/
	function combineDika() {
		$data = func_get_args();
		$data = current($data);
		$cnt = count($data);
		$result = array();
		$arr1 = array_shift($data);
		foreach($arr1 as $key=>$item)
			$result[] = array($item);
		foreach($data as $key=>$item)                             
			$result = $this->combineArray($result,$item);
		return $result;
	}
	/**
	 * 两个数组的笛卡尔积
	 * @param unknown_type $arr1
	 * @param unknown_type $arr2
	*/
	function combineArray($arr1,$arr2) {		 
		$result = array();
		foreach ($arr1 as $item1) 
		{
			foreach ($arr2 as $item2) 
			{
				$temp = $item1;
				$temp[] = $item2;
				$result[] = $temp;
			}
		}
		return $result;
	}
	  /**
     * 动态获取商品属性输入框 根据不同的数据返回不同的输入框类型
     * @param int $goods_id 商品id
     * @param int $type_id 商品属性类型id
     */
	public function getAttrInput($goods_id,$type_id){
		$attr=(new ItemAttribute)->getAll($goods_id,$type_id);
		foreach($attr as $key => $val){
			$attr[$key]['values'] = explode(PHP_EOL, $val['values']);
			$attr[$key]['count']=count($attr[$key]['values']);
		}
		return $attr;
	}
	/**
	*规格详情
	*/
	public function detail($id){
		$model = new static;
		$result=$model->where('id', '=', $id)->find();
		$result['items']=implode(PHP_EOL,(new SpecItem())->where(array('spec_id'=>$result['id']))->column('item'));
		return $result;
	}
	/**
	*添加修改规格
	*/
	public function savespec($data){
	    $data['app_id'] = self::$app_id;
	    $where=['id','=',$data['id']];
	    $this->allowField(true)->save($data,$where);
		(new SpecItem())->saveAfter($data['id'],$data['items']);
		return true;
	}
}