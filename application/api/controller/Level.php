<?php

namespace app\api\controller;

use app\common\model\Level as LevelModel;

/**
 * 个人中心主页
 * Class Index
 * @package app\api\controller\user
 */
class Level extends Controller
{
    /**
     * 获取当前用户信息
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function userLevel()
    {
		$model = new LevelModel;
		if(!$this->request->isPost()){
			$level = $model->judge($user = $this->getUser());
			return $this->renderSuccess(compact('level','user'));
		}
		if($model->Upgrade($user = $this->getUser(),$this->request->post())){
			return $this->renderSuccess(['user' => $user],'领取成功');
		}
		return $this->renderError($model->getError() ?: '积分不足');
	}

}
