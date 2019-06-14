<?php
namespace app\user\controller;
use app\common\model\WebUser;
use app\common\model\Open;
/**
 * 商户平台登陆
 * Class Passport
 * @package app\user\controller
 */
class Login extends  Controller
{
    /**
     * 商户后台登录
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login()
    {
//        echo wymall_pass("123456");
        if ($this->request->isAjax()) {
            $model = new WebUser;
			$data = $this->postData('login');

			if($data['type']=='login'){
				if ($model->login($data)) {
					return $this->renderSuccess('登录成功', url('user/index/index'));
				}
				return $this->renderError($model->getError() ?: '登录失败');
			}
        }
        return $this->fetch('login');
    }
    /**
     * 退出登录
     */
    public function logout()
    {
       (new WebUser)->logout();
        $this->redirect('login/login');
    }
	
}