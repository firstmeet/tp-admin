<?php
namespace app\user\controller;
use app\common\model\Category as CategoryModel;
use app\common\model\Item as ItemModel;
use app\common\model\Type as TypeModel;
use app\common\model\Spec as SpecModel;
use app\common\model\Attrbute as AttrbuteModel;
use app\common\model\Brand as BrandModel;
use app\common\model\Delivery as DeliveryModel;
use app\common\model\SpecItemPrice as SpecItemPriceModel;
use app\common\model\Comment as CommentModel;
/**
 * 商品管理控制器
 * Class Goods
 * @package app\user\controller
 */
class Item extends Controller
{
   /**
     * 商品列表(出售中)
     * @param null $status
     * @param null $category_id
     * @param string $name
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($status = null, $category_id = null, $name = '', $market = '')
    {
        // 商品分类
        $catgory = (new CategoryModel)->getSortCategory();
        // 商品列表
        $model = new ItemModel;
        $list = $model->getList($status, $category_id, $name,$market);
        if(empty($market)){
			return $this->fetch('index', compact('list','catgory'));
		}
		return $this->fetch('goodslist', compact('list','catgory'));
    }
	 /**
     * 商品编辑
     * @param $goods_id
     * @return array|mixed
     */
    public function edit($goods_id='')
    {
        $model = new ItemModel;	
        if (!$this->request->isAjax()) {
			$goodsInfo=$model::detail($goods_id);
            // 商品分类
		    $itemType = (new TypeModel)->All();
		    $brandList = (new ItemModel)->getSortBrands();//获取品牌
			
            $delivery = DeliveryModel::getAll();
			//sku和分类
            return $this->fetch('edit', compact('goodsInfo','itemType','brandList','delivery'));
        }
        $data=$this->postData('goods');
        if ($goods_id?$model->edit($data):$model->add($data)) {
            return $this->renderSuccess('更新成功', url('item/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }
	/**
     * 修改商品状态
     * @param $goods_id
     * @param boolean $state
     * @return array
     */
    public function state($goods_id)
    {
        $model = ItemModel::detail($goods_id);
        if (!$model->setStatus($model['is_on_sale'])) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }
	/**
     * 删除数据
     * @param $id 
     * @param $table
     * @return array
     */
	public function delete($id='',$table='')
	{
		$model= "app\\common\\model\\".$table;
		if($table=='item'){
			$detail = ItemModel::detail($goods_id);
			if($detail['prom_type']>0)return $this->renderSuccess('该商品下有营销分类,请先删除');
		}
		if($table=='Category'){
			$goodsCount = (new ItemModel())->getGoodsTotal(['goods_id' => $id]);
			if ($goodsCount>0){	
				return $this->renderError ('该分类下存在' . $goodsCount . '个商品，不允许删除');
			}
            // 判断是否存在子分类
			if ((new CategoryModel)->where('pid','=',$id)->count())return  $this->renderError('该分类下存在子分类，请先删除');		
		}
		if (!(new $model)->destroy($id)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }else{
			(new CategoryModel)->cache();
		}
        return $this->renderSuccess('删除成功');
	}
	/**
     *动态获取商品规格选择框 根据不同的数据返回不同的选择框
     * @param $goods_id 
     * @param $spec_type
     * @return array|mixed
     */
	public function specSelect($goods_id = 0,$spec_type = '')
	{
		$specList['list'] =(new SpecModel)->getAll($spec_type);
		if($goods_id){
			$items_id =(new SpecItemPriceModel)->where('goods_id = '.$goods_id)->value("GROUP_CONCAT(`key` SEPARATOR '_') AS items_id");
			$specList['items_ids']=explode("_",$items_id);
		}
		$this->view->engine->layout(false);
		return $this->renderSuccess('','',$specList);
    }
     /**
     *返回规格型号
     * @param $spec_arr 
     * @param $goods_id
     * @return array
     */
	public function getSpecInput($spec_arr = [],$goods_id = 0){
		$spec=new SpecModel;
		$str = $spec->getSpecInput($goods_id,$spec_arr);
        return $this->renderSuccess('','',$str);
	}
	 /**
     *返回商品分类
     * @param $role_id
     * @return array
     */
	public function getJsTree($role_id = null)
    {
		$list = (new CategoryModel)->field("pid,id,name")->column("*",'id');
		$parent=[];
		foreach ($list as $k=>$v) {
			if ($v['pid'] != 0)
				$list[$v['pid']]['children'][] = &$list[$k];
			else
			    $parent[] = &$list[$k];
		}
		return $this->renderSuccess('','',$parent);
    }
	/**
     *动态获取商品属性输入框 根据不同的数据返回不同的输入框类型
     * @param $goods_id
	 * @param $type_id
     * @return array
     */
    public function getAttrInput($goods_id = null,$type_id = null)
	{
		$spec = new SpecModel();
		$str = $spec->getAttrInput($goods_id,$type_id);
		return $this->renderSuccess('','',$str);	
    }
	/**
     * 商品分类列表
     * @return mixed
     */
    public function categoryList()
    {
		$model=new CategoryModel;
		$list = $model->getlist();
        return $this->fetch('item/category/index',compact('list'));
    }
	/**
     * ajax分类列表
     * @return mixed
     */
	public function categoryAjax(){
		$model=new CategoryModel;
		$list = $model->getlist();
		return $this->renderSuccess('','',$list);
	}
	/**
     * 编辑商品分类
     * @param $id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function editCategory($id = '')
    {
        $CategoryModel = new CategoryModel;
		$category = $CategoryModel::detail($id);
        if (!$this->request->isAjax()) {
            $list = (new CategoryModel)->getlist();
            return $this->fetch('item/category/edit', compact('category', 'list','id'));
        }
        $data=$this->postData('category');
        if ($id ? $CategoryModel->edit($data) : $CategoryModel ->add($data)) {
            return $this->renderSuccess('更新成功', url('item/categoryList'));
        }
        return $this->renderError($category->getError() ?: '更新失败');
    }
	 /**
     * 商品类型列表
     * @return mixed
     */
    public function itemType()
    {
        $model = new TypeModel;
        $list = $model->typelist();
		$page = $list->render();
        return $this->fetch('item/type/index', compact('list','page'));
    }
	/**
     * 添加商品类型
	 * @param $id
     * @return array|mixed
     */
    public function saveType($id = '')
    {
        $type=new TypeModel;
        if (!$this->request->isAjax()) {
			$model = $type::get($id);
            return $this->fetch('item/type/save',compact('model'));
        }
         $data=$this->postData('type');
        if ($id?$type->edittype($data):$type->savetype($data)) {
            return $this->renderSuccess('操作成功', url('item/itemtype'));
        }
        return $this->renderError($type->getError() ?: '添加失败');
    }
	/**
	*规格组列表
	* @param $type_id
	* @return array
	*/
	public function specList($type_id = '')
	{
        $model = new SpecModel;
        $list = $model->getList($type_id);
		$type = $model->type();
        return $this->fetch('item/spec/index', compact('list','ItemTypeList','type'));
	}
	/**
	*添加修改规格
	* @param $type_id
	* @return array
	*/
	public function saveSpec($id = ''){
		$model = new SpecModel;
		if (!$this->request->isAjax()) {
			$spec=$model->detail($id);
			$type = $model->type();
			return $this->fetch('item/spec/save', compact('type','spec'));
		}
		// 新增记录
            $data=$this->postData('spec');
          if ($data['id'] ? $model->saveSpec($data):$model->add($data)) {
            return $this->renderSuccess('操作成功', url('item/speclist'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
	}
	/**
	*属性组列表
	* @param $type_id
	* @return array
	*/
    public function attrbute($type_id=0)
	{
        $model = new AttrbuteModel;
        $list = $model->getList($type_id);
        $type = (new SpecModel)->type();
        $typelist= TypeModel::detail();
        return $this->fetch('item/attrbute/index', compact('list','typelist','type'));
    }
	/**
	*添加修改规格
	* @param $attr_id
	* @return mixed
	*/
	public function saveAttrbute($attr_id = null){
		 $model = new AttrbuteModel;
		if (!$this->request->isAjax()) {
			$detail=$model::get($attr_id);
			$type=(new TypeModel())->field('name,id')->select();
			return $this->fetch('item/attrbute/save', compact('detail','type'));
		}
        if ($model->addattrbute($this->postData('attrbute'),$attr_id)) {
            return $this->renderSuccess('操作成功', url('item/Attrbute'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
	}
		/**
     * 品牌列
     * @param null $status
     * @param null $category_id
     * @param string $name
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function brand($status = null, $category_id = null, $name = '')
    {
        $list =  BrandModel::getAll($name);
		//dump($list);die;
        return $this->fetch('item/brand/index',compact('list'));
    }
	/**
	*编辑修改品牌
	* @param id
    * @return mixed
	**/
	public function saveBrand($id = ''){
		$Brand=new BrandModel;
        if (!$this->request->isAjax()) {
            // 获取所有地区
			$list=$Brand->detail($id);
			
            return $this->fetch('item/brand/save', compact("list"));
        }
         $data=$this->postData('brand');
		 if ($data['id']?$Brand->edit($data):$Brand->add($data)) {
            return $this->renderSuccess('编辑成功', url('item/brand'));
        }
        return $this->renderError($Brand->getError() ?: '添加失败');
	}
	/**
     * 评价列表
	 * @param item_id
	 * @param user_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function comment($item_id = null,$user_id = null)
    {
        $model = new CommentModel;
        $list = $model->getList($item_id,$user_id);
        return $this->fetch('comment', compact('list'));
    }
	 /**
     * 评价详情
     * @param $comment_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function detail($comment_id)
    {
        $model = CommentModel::detail($comment_id);
        if (!$this->request->isAjax()) {
           return $this->fetch('detail', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('comment'))) {
            return $this->renderSuccess('更新成功', url('comment'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
	/**
     * 模型商品选择列表
     * @param null $status
	 * @param $category_id
	 * @param $name
	 * @param $item_id
	 * @param $market
     * @return mixed
     * @throws \think\exception\DbException
     */
	public function search($status = null, $category_id = null, $name = '',$market = '',$item_id = '')
	{
        $catgory  = (new CategoryModel)->getSortCategory();
        // 商品列表
        $model = new ItemModel;
        $list = $model->getList($status,$category_id,$name,$market,$item_id);
        return $this->fetch('search', compact('list', 'catgory'));
	}
	 /**
     * 模型商品选择列表
     * @param null $status
	 * @param $type
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function lists($status = null,$type = '')
    {	
		$this->view->engine->layout(false);
		$model = new ItemModel;
        $list = $model->tplgetList($status,$type);
        return $this->fetch('lists', compact('list'));
    }
}