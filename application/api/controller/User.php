<?php

namespace app\api\controller;

use app\common\model\User as UserModel;

class User extends Controller
{
	/**
     * app/web/等手机号登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
	public function app_login()
    {
		
        $model = new UserModel;
		$user=$model->app_login($this->request->post());
		
		if($user){
			return $this->renderSuccess([
				'user_id' => $user,
				'token' => $model->getToken()
			]);
		}
		return $this->renderError('登录失败');
    }
	/*
	*app/web/等手机号注册
	* @return array
	*/
	public function app_reg(){
		$model = new UserModel;
        return $this->renderSuccess([
            'user_id' => $model->reg($this->request->post()),
            'token' => $model->getToken()
        ]);
	
	}
    /*
     * 用户自动登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login()
    {
		
        $model = new UserModel;
		
        return $this->renderSuccess([
            'user_id' => $model->login($this->request->post()),
            'token' => $model->getToken()
        ]);
    }

}
