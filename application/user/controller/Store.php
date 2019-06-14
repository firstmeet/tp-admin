<?php
// +----------------------------------------------------------------------
// | User
// +----------------------------------------------------------------------
// | 版权所有 2015-2020 武汉市微易科技有限公司，并保留所有权利。
// +----------------------------------------------------------------------
// | 网站地址: https://www.kdfu.cn
// +----------------------------------------------------------------------
// | 这不是一个自由软件！您只能在不用于商业目的的前提下使用本程序
// +----------------------------------------------------------------------
// | 不允许对程序代码以任何形式任何目的的再发布
// +----------------------------------------------------------------------
// | Author: 微小易    Date: 2018-12-01
// +----------------------------------------------------------------------
namespace app\user\controller;
use app\common\model\Role as RoleModel;
use app\common\model\Store as StoreModel;
use app\common\model\UserRole;
use app\common\model\Menu as MenuModel;
/**
/**
 * 商家用户控制器
 * Class StoreUser
 * @package app\store\controller
 */
class Store extends Controller
{
    /**
     * 用户列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new StoreModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }
    /**
     * 添加管理员
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        $model = new StoreModel;
        if (!$this->request->isAjax()) {
            // 角色列表
            $roleList = (new RoleModel)->getList();
            return $this->fetch('add', compact('roleList'));
        }
        // 新增记录
        if ($model->add($this->postData('user'))) {
            return $this->renderSuccess('添加成功', url('storeuser/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }
    /**
     * 更新管理员
     * @param $user_id
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($user_id)
    {
        // 管理员详情
        $model = StoreModel::detail($user_id);
        $model['roleIds'] = UserRole::getRoleIds($model['user_id']);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', [
                'model' => $model,
                // 角色列表
                'roleList' => (new RoleModel)->getList(),
                // 所有角色id
                'roleIds' => UserRole::getRoleIds($model['user_id']),
            ]);
        }
        // 更新记录
        if ($model->edit($this->postData('user'))) {
            return $this->renderSuccess('更新成功', url('storeuser/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
    /**
     * 删除管理员
     * @param $user_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($user_id)
    {
        // 管理员详情
        $model = StoreModel::detail($user_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     * 更新当前管理员信息
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function renew()
    {
        // 管理员详情
        $model = StoreModel::detail($this->store['user']['user_id']);
        if ($this->request->isAjax()) {
            if ($model->renew($this->postData('user'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->fetch('renew', compact('model'));
    }
	/*
	*菜单列表
	*/
	public function menu(){
		$accessList = (new MenuModel)->getJsTree($this->postData('role_id'));
		return $this->renderJson(0, '获取成功','',compact('accessList'));
	}
	 /**
     * 角色列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function role()
    {
        $model = new RoleModel;
        $list = $model->getList();
        return $this->fetch('role', compact('list'));
    }
	    /**
     * 添加管理员
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adduser()
    {
        $model = new StoreModel;
        if (!$this->request->isAjax()) {
            // 角色列表
            $roleList = (new RoleModel)->getList();
            return $this->fetch('store/user/add', compact('roleList'));
        }
        // 新增记录
        if ($model->add($this->postData('user'))) {
            return $this->renderSuccess('添加成功', url('storeuser/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }
	    /**
     * 更新管理员
     * @param $user_id
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editUser($user_id)
    {
        // 管理员详情
        $model = StoreModel::detail($user_id);
        $model['roleIds'] = UserRole::getRoleIds($model['user_id']);
        if (!$this->request->isAjax()) {
            return $this->fetch('store/user/edit', [
                'model' => $model,
                // 角色列表
                'roleList' => (new RoleModel)->getList(),
                // 所有角色id
                'roleIds' => UserRole::getRoleIds($model['user_id']),
            ]);
        }
        // 更新记录
        if ($model->edit($this->postData('user'))) {
            return $this->renderSuccess('更新成功', url('storeuser/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
	  /**
     * 删除管理员
     * @param $user_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function deleteuser($user_id)
    {
        // 管理员详情
        $model = StoreModel::detail($user_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
}