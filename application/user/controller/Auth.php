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
use app\common\model\Menu as MenuModel;
use app\common\model\Auth as AuthModel;
use app\common\model\WebUser as WebModel;
use app\common\model\UserRole;
use app\common\model\LoginLog as LoginLogModel;
/**
 * 商家用户控制器
 * Class StoreUser
 * @package app\store\controller
 */
class Auth extends Controller
{
    /**
     * 用户列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function userIndex()
    {
        $model = new WebModel;
        $list = $model->getList();
        $page=	$list->render();
        $tatal=$list->total();
        return $this->fetch('auth/user/index', compact('list','page','tatal'));
    }
	/**
     * 登录日志
     * @param 
     * @return array|mixed
     * @throws \think\Exception
     */
	  public function loginLog()
    {
		$model = new LoginLogModel;
        $list = $model->getAll();
        return $this->fetch('loginlog', compact('list'));
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
    public function editUser($user_id='')
    {
        // 管理员详情
        $model=new WebModel;		  
        if (!$this->request->isAjax()){
            $detail =$model::get($user_id);
            $roleList= (new AuthModel)->getList();
            return $this->fetch('auth/user/edit',compact('detail','roleList'));
        }
        $data=$this->postData('user');
        if ($user_id ? $model->edit($data) : $model->add($data)) {
            return $this->renderSuccess('更新成功', url('auth/userIndex'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
    /**
     * 删除管理员
     * @param $user_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function deleteUser($user_id)
    { 
        $model = new WebModel;
        if (!$model->setDelete($user_id)) {
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
        $model = WebModel::detail($this->store['user']['user_id']);
        if ($this->request->isAjax()) {
            if ($model->renew($this->postData('user'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->fetch('renew', compact('model'));
    }
	 /**
     * 权限列表
     * @return array
     * @throws \think\exception\DbException
     */
    public function roleIndex()
    {
        $model = new AuthModel;
        $list = $model->getList();
        return $this->fetch('auth/role/index', compact('list'));
    }
    /**
     * 角色获取
     * @param $role_id
     * @return array|mixed
     */
    public function role(){
        $accessList = (new MenuModel)->getJsTree($this->postData('role_id'));
        return $this->renderJson(0, '获取成功','',compact('accessList'));
    }
    /**
     * 更新角色
     * @param $role_id
     * @return array
     */
    public function editRole($role_id='')
    {
        $role=new AuthModel;
        if (!$this->request->isAjax()) {
            $model = $role::detail($role_id);
            return $this->fetch('auth/role/edit',compact('model'));
        }
        if ($role->edit($this->postData('role'))) {
            return $this->renderSuccess('更新成功', url('auth/roleIndex'));
        }
        return $this->renderError($role->getError() ?: '更新失败');
    }
    /**
     * 删除角色
     * @param $role_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function deleteRole($role_id)
    {
        $model = AuthModel::detail($role_id);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
	   /**
     * 菜单列表
     * @return array
     * @throws \think\exception\DbException
     */
    public function menuIndex(){
        $model = new AuthModel;
        $list = $model->getList('menu');
        return $this->fetch('role_index', compact('list'));
    }
}