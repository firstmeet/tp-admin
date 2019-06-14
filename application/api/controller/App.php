<?php
namespace app\api\controller;
use app\common\model\App as AppModel;
use app\common\model\AppHelp;
use app\common\model\Sign as SignModel;

/**
 * 微信小程序
 * Class App
 * @package app\api\controller
 */
class App extends Controller
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
	
	/**
     * 用户签到
     * @throws \think\exception\DbException
     */
	public function signs()
	{
		$model = new SignModel;
		if(!$this->request->isPost()){
				
			$list = $model->getAlls($this->getUser());
			return $this->renderSuccess(compact('list'));	
		}
		if($model->checks($this->getUser(),$this->request->post())){
				return $this->renderSuccess('签到成功');
		}
		return $this->renderError($model->getError('签到失败') ?: '已签到');
	}

}
