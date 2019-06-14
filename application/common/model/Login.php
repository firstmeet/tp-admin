<?php
namespace app\common\model;
use think\model\relation\RelationShip;
use think\facade\Session;
/**
 * 商家用户模型
 * Class StoreUser
 * @package app\common\model
 */
class Login extends BaseModel
{
    protected $name = 'web_user';
    /**
     * 关联微信小程序表
     * @return \think\model\relation\BelongsTo
     */
    public function app() {
        return $this->belongsTo('App','','app_id');
    }
    /**
     * 新增默认商家用户信息
     * @param $app_id
     * @return false|int
     */
    public function insertDefault($app_id)
    {
        return $this->save([
            'user_name' => 'wymall_' . $app_id,
            'password' => md5(uniqid()),
            'app_id' => $app_id,
        ]);
    }

    /**
     * 商户信息
     * @param $user_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($user_id)
    {
        return self::get($user_id);
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
        Session::set('wymall_store.user', [
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
    public function add_on($app_id)
    {
        // 更新app_id
        return $this->save(['app_id' => $app_id]);
    }
}