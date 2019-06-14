<?php
namespace app\common\model;
use think\facade\Session;
/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class WebUser extends BaseModel
{
    protected $name = 'web_user';
	protected $pk = 'user_id';
	 /**
     * 关联角色
     * @return \think\model\relation\BelongsTo
     */
    public function role()
    {
        return $this->hasOne('auth', 'role_id')
            ->bind(['role_name']);
    }
    /**
     * 关联微信小程序表
     * @return \think\model\relation\BelongsTo
     */
    public function app()
    {
        return $this->hasOne('app','app_id');
    }
    /**
     * 商家用户详情
     * @param $where
     * @param array $with
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail($where, $with = [])
    {
        if(!is_array($where)) $where = ['user_id' => (int)$where];
        return static::get(array_merge(['is_delete' => 0], $where), $with);
    }
	 /**
     * 商家用户登录
     * @param $data
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($data)
    {
        $name = explode(":", $data['user_name']);
        $username = isset($name[1]) ? $name[1] : $name[0];
        if (!$user = self::useGlobalScope(false)->with('app')->where([
            'user_name' => $username,
            'password' => wymall_pass($data['password'])
        ])->find()) {
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        if($user['is_super']!=0 && count($name)===1){
        $this->error = '登录失败, 必须带主帐号登陆';
        return false;
    }
		if($user['app_id']!=0)
			Session::set('app_id', $user['app_id']);
		//存放用户登录信息
		Session::set('wymall_store', $user);
		(new LoginLog)->addlog();
        return true;
    }
    /**
     * 获取登录用户信息
     * @param $user_name
     * @param $password
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getLoginUser($user_name, $password)
    {
        return self::useGlobalScope(false)->with(['app'])->where([
            'user_name' => $user_name,
            'password' => wymall_pass($password),
            'is_delete' => 0
        ])->find();
    }
    /**
     * 获取用户列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->where('is_delete','=', '0')
			->where('user_id','=',self::$user_id)
			->whereOr('is_super','=',self::$user_id)
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }
    /**
     * 新增记录
     * @param $data
     * @return bool|false|int
     * @throws \think\exception\DbException
     */
    public function add($data)
    {
        if (self::detail(['user_name' => $data['user_name'],'user_id'=>self::$is_super])) {
            $this->error = '用户名已存在';
            return false;
        }
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (empty($data['role_id'])) {
            $this->error = '请选择所属角色';
            return false;
        }
		$data['password'] = wymall_pass($data['password']);
		$data['is_super'] =self::$app_id;
		$data['app_id']=self::$app_id;
		return $this->allowField(true)->save($data);
    }
    /**
     * 更新记录
     * @param array $data
     * @return bool
     * @throws \think\exception\DbException
     */
    public function edit($data)
    {
        if (!empty($data['password']) && ($data['password'] !== $data['password_confirm'])) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (empty($data['role_id'])) {
            $this->error = '请选择所属角色';
            return false;
        }
        if (!empty($data['password'])) {
            $data['password'] = wymall_pass($data['password']);
        } else {
            unset($data['password']);
        }
		$where['user_id']=$data['user_id'];
		$this->allowField(true)->save($data,$where);
        return true;
    }
    /**
     * 软删除
     * @return false|int
     */
    public function setDelete($user_id)
    {
        if (self::$is_super ==$user_id) {
            $this->error = '超级管理员不允许删除';
            return false;
        }
        // 删除对应的角色关系
		return self::destroy($user_id);
    }
    /**
     * 更新当前管理员信息
     * @param $data
     * @return bool
     */
    public function renew($data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        // 更新管理员信息
        if ($this->save([
                'user_name' => $data['user_name'],
                'password' => wymall_pass($data['password']),
            ]) === false) {
            return false;
        }
        // 更新session
        Session::set('user', [
            'user_id' => $this['user_id'],
            'user_name' => $data['user_name'],
        ]);
        return true;
    }
	/**
     * 更新当前管理员信息
     * @param $data
     * @return bool
     */
    public function addOn($app_id)
    {
        // 更新app_id
        return $this->save([
            'app_id'  => $app_id,
        ],['user_id' =>self::$user_id]);
    }
	/**
	*清理缓存
	*/
	public function logout(){
		Session::clear();
	}
}