<?php
namespace app\user\controller;
use app\common\model\AppPage as AppPageModel;
use app\common\model\App as AppCategoryModel;
use app\common\model\AppNavbar as AppNavbarModel;
/**
 * 小程序页面管理
 * Class Page
 * @package app\user\controller\App
 */
class Tpl extends Controller
{
    /**
     * 页面列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new AppPageModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }
    /**
     * 编辑页面
     * @param $page_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($page_id = '')
    {
		$page=new AppPageModel;
        if (!$this->request->isAjax()) {
            $model = $page::detail($page_id);
            $jsonData = $model['page_data']['json'];
			//dump($jsonData);die;
           // 商品分类
           return $this->fetch('tpl/edit', compact('jsonData'));
        }
        $post = $this->request->post('data');
        if ($page_id ? $page->edit(json_decode($post,true),$page_id) : $page->add(json_decode($post,true))) {
            return $this->renderSuccess('更新成功', url('tpl/index'));
        }
        return $this->renderError( '更新失败');
    }
    /**
     * 删除页面
     * @param $page_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($page_id)
    {
        // 帮助详情
        $model = AppPageModel::detail($page_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     * 设置默认首页
     * @param $page_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function setHome($page_id)
    {
        // 帮助详情
        $model = AppPageModel::detail($page_id);		
        if (!$model->setHome()) {
            return $this->renderError($model->getError() ?: '设置失败');
        }
        return $this->renderSuccess('设置成功');
    }
    /**
     * 分类模板
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function category()
    {   
        $model=AppCategoryModel::detail();
        if ($this->request->isAjax()) {
            if ($model->editCategory($this->postData('category'))){
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->fetch('category', compact('model'));
    }
    /**
     * 页面链接
     * @return mixed
     */
    public function links()
    {
        return $this->fetch('links');
    }
	 /**
     * 导航栏设置
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function tabbar($app_id='')
    {
        $model = AppNavbarModel::detail($app_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('tabbar', compact('model'));
        }
        $data = $this->postData('tabbar');
        if (!$model->edit($data)) {
            return $this->renderError('更新失败');
        }
        return $this->renderSuccess('更新成功');
    }
}