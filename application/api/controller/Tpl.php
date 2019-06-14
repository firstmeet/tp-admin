<?php

namespace app\api\controller;

use app\common\model\App as AppModel;
use app\common\model\AppHelp;

/**
 * 微信小程序
 * Class App
 * @package app\api\controller
 */
class Tpl extends Controller
{
    /**
     * 小程序基础信息
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function base()
    {
        $app = AppModel::getAppCache();
		
        return $this->renderSuccess(compact('app'));
    }

    /**
     * 帮助中心
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function help()
    {
        $model = new AppHelp;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

}
