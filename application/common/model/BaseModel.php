<?php
namespace app\common\model;
use think\Model;
use think\facade\Request;
use think\facade\Session;
/**
 * 模型基类
 * Class BaseModel
 * @package app\common\model
 */
class BaseModel extends Model
{
    public static $app_id;
	public static $user_id;
    public static $is_super;
    public static $base_url;
    /* @var array $queryAllAction  查询user_id白名单 */
	protected $queryAction= [
		'app',
		'upload_file',
		'web_user',
		'auth',
        'auth_list',
		'login_log'
	];
	/* @var array $allowAllAction  权限白名单 */
	protected $allowAllAction= [
        'user/login/login',
		'user/index/index',
		'user/app/applist',
		'user/login/logout',
		'user/app/addon'
    ];
    /**
     * 模型基类初始化
     */
    public static function init()
    {
        parent::init();
        // 获取当前域名
        self::$base_url = base_url();
		//验证小程序是否存在
		self::checkApp();
        // 后期静态绑定app_id
        self::bindAppId();
    }
	/**
     * 验证小程序是否存在
     */
    public static function checkAPP(){
		$user = Session::get('wymall_store');
		if($user['user_id']){
			self::$is_super =!empty($user['is_super'])? $user['is_super']:$user['user_id'];
			self::$user_id =$user['user_id'];
			if(empty((new App)->getAll())){
                Session::set('is_app','0');
		    }
	   }
	}
    /**
     * 后期静态绑定类名称
     * 用于定义全局查询范围的app_id条件
     * 子类调用方式:
     *   非静态方法:  self::$app_id
     *   静态方法中:  $self = new static();   $self::$app_id
     * @param $calledClass
     */
    private static  function bindAppId()
    {
        $request = Request::instance();
		//self::$app_id ? $request->param('app_id') : Session::get('app_id');
        if(!(self::$app_id = $request->param('app_id'))){
            self::$app_id = Session::get('app_id');
        }
    }
    /**
     * 定义全局的查询范围
     * @param \think\db\Query $query
     */
    protected function base($query)
    {
        if ((in_array(str_replace(config('database.prefix'),'',$query->getTable()), $this->queryAction))===false) {
            $query->where($query->getTable() . '.app_id', self::$app_id);
        }
    }
	 /**
     * 删除记录
     * @param $where
     * @return int
     */
    public static function deleteAll($data)
    {
      return self::destroy($data);
    }
}