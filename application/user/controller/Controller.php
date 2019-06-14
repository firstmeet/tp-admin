<?php
/**
 * web
 * ============================================================================
 * 版权所有 20158-1108 武汉市微易科技有限公司，并保留所有权利。
 * 网站地址: https://ccd.iirr5.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: angner
 * Date: 2015-09-09
 */
namespace app\user\controller;
use think\facade\Request;
use think\facade\Session;
use app\common\model\Menu as MenuModel;
use app\common\model\Index;
use app\common\model\Auth as AuthModel;
/**
 * 后台控制器基类
 * Class BaseController
 * @package app\user\controller
 */
class Controller extends \think\Controller
{
    /* @var array $store 商家登录信息 */
    protected $store;
	/* @var string $app_id 小程序信息 */
	protected $app_id;
	/* @var string $is_super 用户关联的主账号 */
	protected $is_super;
    /* @var string $route 当前控制器名称 */
    protected $controller = '';
    /* @var string $route 当前方法名称 */
    protected $action = '';
    /* @var string $route 当前路由uri */
    protected $routeUri = '';
    /* @var string $route 当前路由：分组名称 */
    protected $group = '';
    /* @var array $allowAllAction 全局白名单 */
    protected $allowAllAction= [
        'login/login'
    ];
    /* @var array $notLayoutAction 无需全局layout */
    protected $notLayoutAction = [
        // 登录页面
        'login/login',
    ];
    /**
     * 后台初始化
     */
    public function initialize()
    {
        // 当前路由信息
        $this->getRouteinfo();
		// 商家登录信息
        $this->store = Session::get('wymall_store');
		//用户关联的主账号
		$this->is_super=!empty($this->store['is_super'])? $this->store['is_super']:$this->store['user_id'];
		//小程序信息
		$this->app_id = $this->store['app_id'];
		 // 验证登录
        $this->checkLogin();
        // 全局layout
        $this->layout();
    }
     /*
     *取当前控制器并进行验证
     */
    public function auth(){
        $url=Request::module().'/'.$this->routeUri;
        $auth = new AuthModel;
        if($auth->check($url,$this->store)===false){
            if (Request::isGet() && Request::isPost()){
				return $this->renderError('权限不足');
            }
			$this->error('权限不足');
        }
    }
    /**
     * 全局layout模板输出
     */
    private function layout()
    {
        // 验证当前请求是否在白名单
        if (!in_array($this->routeUri, $this->notLayoutAction)) {
            // 输出到view
            $request= Request::instance();
            $this->assign([
                'base_url' => base_url(),                      // 当前域名
                'store_url' => url('/user/'),              // 后台模块url
                'group'     => $this->group,    //
                'url'		=> $request->path(),
                'menu'      => $this->menu(),                     // 后台菜单
                'store'     => $this->store,                       // 商家登录信息
                'request'   => Request::instance()               // Request对象
            ]);
        }else{
			$this->view->engine->layout(false);
		}
    }
    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteinfo()
    {
        // 控制器名称
        $this->controller = toUnderScore($this->request->controller());
        // 方法名称
        $this->action = $this->request->action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = $this->controller . '/' . $this->action;
    }
    /**
     * 后台菜单配置
     * @return array
     */
    private  function menu()
    {
        $list = (new MenuModel)->getJsTree();
        foreach ($list as $k=>$v){
            if($v["pid"]=='0'){
                $list[$v["model"]]=$v;
                unset($list[$k]);
            }
        }
        return $list;
    }
    /**
     * 验证登录状态
     * @return bool
     */
    private function checkLogin()
    {
        // 验证当前请求是否在白名单
        if (in_array($this->routeUri, $this->allowAllAction)) {
            return true;
        }
		// 验证登录状态
        if (empty($this->store)) {
            $this->redirect('Login/login',302);
            return false;
        }
		//验证auth
        $this->auth();
        return true;
    }
    /**
     * 获取当前app_id
     */
    protected function getAppId()
    {
        return $this->store['app']['id'];
    }
    /**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $msg
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function renderJson($code = 1, $msg = '', $url = '', $data = [])
    {
        return compact('code', 'msg', 'url', 'data');
    }
    /**
     * 返回操作成功json
     * @param string $msg
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function renderSuccess($msg = 'success', $url = '', $data = [])
    {
        return $this->renderJson(1, $msg, $url, $data);
    }
    /**
     * 返回操作失败json
     * @param string $msg
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $url = '', $data = [])
    {
        return $this->renderJson(0, $msg, $url, $data);
    }
    /**
     * 获取post数据 (数组)
     * @param $key
     * @return mixed
     */
    protected function postData($key)
    {
        return $this->request->post($key . '/a');
    }
}