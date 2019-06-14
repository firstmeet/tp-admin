<?php
namespace app\api\controller;
use think\facade\Cache;
use app\common\model\Category as CategoryModel;
use app\common\model\App as AppCategoryModel;
/**
 * 商品分类控制器
 * Class Item
 * @package app\api\controller
 */
class Category extends Controller
{
    /**
     * 分类页面
     * @return array
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 分类模板
		$app_id = $this->app_id;
        $templet = AppCategoryModel::getAppCache($app_id);
        // 商品分类列表
		if(!$list = Cache::get('category_'.$app_id)){
			$list = array_values(CategoryModel::getCacheTree());
			Cache::set('category_'.$app_id,$list);
		}
        return $this->renderSuccess(compact('templet', 'list'));
    }
}