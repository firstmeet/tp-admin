<?php
namespace app\common\model;
use think\facade\Cache;
/**
 * 商品分类模型
 * Class Category
 * @package app\common\model
 */
class Category extends BaseModel
{
    protected $name = 'category';
	protected $pk = 'id';
    static public $treeList = array();
	 /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'app_id',
//        'create_time',
        'update_time'
    ];
    /**
     * 分类图片
     * @return \think\model\relation\HasOne
     */
    public function images()
    {	
        return $this->hasOne('uploadFile', 'id', 'image');
    }
    /**
     * 所有分类
     * @return mixed
     */
    public  function getALL()
    {
		$model=new static;
       if (!$data=Cache::get('category_' . self::$app_id)) {
			$data = $model->order(['sort' => 'asc'])->field("id,pid,name,sort,create_time")->select();
            Cache::set('category_' . self::$app_id,$data);
		}
        return $data;
    }
	/**
	*清除缓存
	*/
	public function cache(){
		Cache::set('category_' . self::$app_id,null);
	}
    /**
     * 获取所有分类(树状结构)
     * @return mixed
     */
    public function getlist(){
		return self::getALL();
    }
	  /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['app_id'] = self::$app_id;
        $this->deleteCache();
		return $this->allowField(true)->save($data);
    }
    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {	
		$where=['id','=',$data['id']];
        $this->deleteCache();
        return $this->allowField(true)->save($data,$where);
    }
    /**
     * 删除商品分类
     * @param $category_id
     * @return bool|int
     */
    public function remove($category_id)
    {
        // 判断是否存在商品
        if ($goodsCount = (new Item)->getGoodsTotal(['id' => $category_id])) {
            $this->error = '该分类下存在' . $goodsCount . '个商品，不允许删除';
            return false;
        }
        // 判断是否存在子分类
        if ((new self)->where(['pid' => $category_id])->count()) {
            $this->error = '该分类下存在子分类，请先删除';
            return false;
        }
        $this->deleteCache();
        return $this->delete();
    }
    /**
     * 删除缓存
     * @return bool
     */
    private function deleteCache()
    {
        return Cache::rm('category_' . self::$app_id);
    }
	 /**
     * 获得指定分类下的子分类的数组     
     * @access  public
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @return  mix
     */
    public function goodsCatList($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
    {
		global $goods_category, $goods_category2;                
		$sql = "SELECT * FROM  wy_category ORDER BY pid , id ASC";
		$goods_category = $this->query($sql);
		$goods_category = convert_arr_key($goods_category, 'id');
		$res=self::tree($goods_category);
		 foreach ($goods_category AS $key => $value)
		{
			$this->get_cat_tree($value['id']);                                
		} 
		return $res;               
    }
	/**
     * 获取所有分类(树状结构)
     * @return mixed
     */
    public static function getCacheTree()
    {	
		$model = new static;
		$data = $model->with(['images'])->order(['id' => 'asc'])->select();
		return self::gettree($data);
    }
	/**
     * 获取指定分类下的所有子分类id
     * @param $pid
     * @param array $all
     * @return array
     */
    public static function getTree($all = [],$pid = 0,$level = '1')
    {
        static $arrIds = [];
        foreach ($all as $key => $item) {
            if ($item['pid'] == $pid) {
				$item['level']=$level;
				$arrIds[]=$item;
				unset($all[$key]);
                self::gettree($all,$item['id'],$level+1);
            }
        }
        return $arrIds;
    }
	/**
     * 获取权限列表 jstree格式
     * @param int $role_id 当前角色id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getJsTree($list = null)
    {
		foreach ($list as $k=>$v) {
			if ($v['pid'] != 0)
				$list[$v['pid']]['list'][$v['id']] = &$list[$k];
			else
			$parent[] = &$list[$k];
		}
		return $parent;
    }
   /**
     * 获取指定id下的 所有分类      
     * @global type $goods_category 所有商品分类
     * @param type $id 当前显示的 菜单id
     * @return 返回数组 Description
     */
    public function getCatTree($id)
    {
        global $goods_category, $goods_category2;          
        $goods_category2[$id] = $goods_category[$id];    
        foreach ($goods_category AS $key => $value){
             if($value['pid'] == $id)
             {                 
                $this->get_cat_tree($value['id']);  
                $goods_category2[$id]['have_son'] = 1; // 还有下级
             }
        }               
    }
     /**
     *  获取排好序的分类列表     
     */
    function getSortCategory()
    {
        $categoryList =  $this->Field('id,name,pid')->select();
        $nameList = array();
        foreach($categoryList as $k => $v)
        {		
            $nameList[] = $v['name']; 
            $categoryList[$k] = $v;
        } 
        return $categoryList;
    }
	 /**
     * 获取分类详情
     * @param $id
     */
    public static function detail($id)
    {	
		$model = new static;
		return  $model->where('id', '=', $id)->with(['images'])->find();
    }
}