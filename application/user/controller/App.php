<?php

namespace app\user\controller;
use app\common\model\Index;
use app\common\model\App as AppModel;
use app\common\model\AppNavbar as AppNavbarModel;

/**
 * 小程序管理
 * Class app
 * @package app\user\controller
 */
class App extends Controller
{
    /**
     * 小程序设置
     * @return mixedS
     * @throws \think\exception\DbException
     */
    public function setting()
    {
        $app = AppModel::detail();
        if ($this->request->isAjax()) {
            $data = $this->postData('app');
            if ($app->edit($data)) return $this->renderSuccess('更新成功');
            return $this->renderError('更新失败');
        }
        return $this->fetch('setting', compact('app'));
    }

    /**
     * 导航栏设置
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function tabbar() {
        $model = AppNavbarModel::detail();
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
