<?php
namespace app\common\model;
use think\facade\Request;
use think\facade\Cache;
use app\common\library\wechat\WxUser;
use app\common\exception\BaseException;
use app\common\model\Setting as DealerSettingModel;
/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    private $token;
    protected $name = 'user';
	protected $pk = 'user_id';
    // 性别
    private $gender = ['未知', '男', '女'];
	  /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'app_id',
        'create_time',
        'update_time'
    ];
    /**
     * 关联收货地址表
     * @return \think\model\relation\HasMany
     */
    public function address()
    {
        return $this->hasMany('UserAddress');
    }
	/**
     * 关联上级信息
     * @return \think\model\relation\HasMany
     */
    public function users()
    {
        return $this->belongsTo('user','pid');
    }
    /**
     * 关联收货地址表 (默认地址)
     * @return \think\model\relation\BelongsTo
     */
    public function addressDefault()
    {
        return $this->belongsTo('UserAddress', 'address_id');
    }
    /**
     * 显示性别
     * @param $value
     * @return mixed
     */
    public function getGenderAttr($value)
    {
        return $this->gender[$value];
    }
    /**
     * 获取用户信息
     * @param $where
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {	
       $filter = ['is_delete' => 0];
        if (is_array($where)) {
            $filter = array_merge($filter, $where);
        } else {
            $filter = ['user_id','=',(int)$where];
        }
        return self::get($filter, ['address', 'addressDefault']);
    }
	/**
     * 删除
     * @return false|int
     */
    public function removeDelete()
    {
        return $this->delete();
    }
	/**
	*恢复冻结
	*/
	public function uefreezing(){
		 return $this->save(['is_delete' =>0]);
	}
	    /**
     * 获取用户列表
     * @param string $nickName
     * @param null $gender
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($nickName = '', $gender = null,$pid = null, $isdelete = '',$listRows = 15)
    {	
		if(!empty($nickName))
			$where[]=['nickName', 'like', "%$nickName%"];
		if(!empty($pid))
			$where[]=['pid', '=', $pid];
		if($gender > -1&&$gender)
			$where[]=['gender', 'in', (int)$gender];
		$where[]=['is_delete','=',$isdelete];
        return $this->where($where)
            ->order('create_time','=','desc')
			->with('users')
            ->paginate($listRows, false, [
                 'query' => Request::instance()->request()
            ]);
    }
	/**
	*获取我的团队。
	*/
	public function teamlist($userid,$level){
		$data=[];
		$list=$this->where('find_in_set(:user_id,path)',['user_id'=>$userid])->select();
		foreach($list as $k=>$v){
			$array=array_filter(explode(',',$v['path']));
			$key=array_search($userid,$array);
			 if(isset($array[$key+$level])){
				switch($v['user_id']){
					case $array[$key+$level]:
					  $data[]= $v;
					 break;
				} 
			}
		 }
		return $data;
	}
	/**
     * 获取当前用户总数
     * @param $day
     * @return int|string
     */
    public function getUserTotal($day = null)
    {
        if (!is_null($day)) {
            $startTime = strtotime($day);
            $this->where('create_time', '>=', $startTime)
                ->where('create_time', '<', $startTime + 86400);
        }
        return $this->count();
    }
    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }
	/***
	*会员卡列表
	*/
	public function getlevelList($listRows = 15){
		$data =(new Level)->all();		
		foreach($data  as $k=>$v){
			$data[$k]['values']=unserialize($v['values']);
		}
		return $data;
	}
		    /**
     * 保存会员级别设置项
     * @param $key
     * @param $values
     * @return false|int
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function saveValues($key, $val)
    {
		$where['key']=$key;
		$values=serialize($val);
		return (new Level)->save([
            'key' => $key,
            'name' => $val['name'],
			'values' => $values,
            'integral' => $val['integral'],
			'agio' => $val['agio'],
            'app_id' => self::$app_id,
        ],$where); 
    }
	 /**
     * 设置项描述
     * @var array
     */
    private $describe = [
        'first' => '普通会员',
        'two' => '高级会员',
        'three' => '至尊会员',
    ];
	  /**
     * 签到
     * @var array
     */ 
	public static function sign($user_id)
	{
	    $model=self::detail($user_id);	
        $data= (new DealerSettingModel)->getall();
		return $model->save([
		'integral'=>$model['integral']+ $data['jifen']['values']['values']['jifen'],
		]);
	}
	/**
     * 获取用户信息
     * @param $token
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function getUser($token)
    {	
        return self::detail(['open_id' => Cache::get($token)['open_id']]);
    }
	/*
	 * 网页用户登录
     * @param array $post
     * @return string
     * @throws BaseException
	*/
	public function appLogin($post){
		$user = self::useGlobalScope(false)->where([
            'phone' => $post['phone'],
            'password' => wymall_pass($post['password'])
        ])->find();
		if(!$user){
            return false;
        }else{
			// 生成token (session3rd)
			$this->token = $this->token($user['phone']);
			// 记录缓存, 7天
			Cache::set($this->token, $user, 86400 * 7);
			return $user['user_id'];
		}
	}
    /**
     * 用户登录
     * @param array $post
     * @return string
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login($post)
    {
        // 微信登录 获取session_key
        $session = $this->wxlogin($post['code'],$post['app_id']);
		$session['open_id']=$session['openid'];
        // 自动注册用户
        $referee_id = isset($post['referee_id']) ? $post['referee_id'] : null;
        $userInfo = json_decode(htmlspecialchars_decode($post['user_info']), true);
        $user_id = $this->register($session['open_id'], $userInfo, $referee_id);
        // 生成token (session3rd)
        $this->token = $this->token($session['open_id']);
        // 记录缓存, 7天
        Cache::set($this->token, $session, 86400 * 7);
        return $user_id;
    }
    /**
     * 获取token
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * 微信登录
     * @param $code
     * @return array|mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function wxLogin($code,$appid)
    {			
        // 获取当前小程序信息

        $wxConfig = App::getAppCache($appid);
        // 微信登录 (获取session_key)
        $WxUser = new WxUser($wxConfig['appkey'], $wxConfig['app_secret']);
        if (!$session = $WxUser->sessionKey($code))
            throw new BaseException(['msg' => 'session_key 获取失败']);
        return $session;
    }
    /**
     * 生成用户认证的token
     * @param $openid
     * @return string
     */
    private function token($openid)
    {
        return md5($openid . self::$app_id . 'token_salt');
    }
	/*
	*app等注册入口
	* @param $phone
    * @param $data
	*/
	public function reg($data){
		$user = self::useGlobalScope(false)->where([
            'phone' => $data['phone']
        ])->find();
		if($user){
			 return false;
		}
		// 生成token (session3rd)
        $this->token = $this->token($data['phone']);
		return $model->allowField(true)->save($data);
	}
    /**
     * 自动注册用户
     * @param $open_id
     * @param $data
     * @param int $referee_id
     * @return mixed
     * @throws \Exception
     * @throws \think\exception\DbException
     */
    private function register($open_id, $data, $referee_id = null)
    {
        // 查询用户是否已存在
        $user = self::detail(['open_id'=>$open_id]);
        $model = $user ?: $this;
        $data['open_id'] = $open_id;
        $data['app_id'] = self::$app_id;
		$data['referee_id'] = $referee_id;
        // 用户昵称
        $data['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nickName']);
        try {
            $this->startTrans();
			if ($referee_id > 0)
				$data['path'] = $this->path($model['user_id'], $referee_id);
            // 保存/更新用户记录
            if (!$model->allowField(true)->save($data))
                throw new BaseException(['msg' => '用户注册失败']);
            // 记录推荐人关系
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
        return $model['user_id'];
    }
	/**
     * 关系树
     * @return array
     */
	public function path($user_id,$referee_id)
	{
		$referee = self::getRefereeUserId($referee_id);
		return $referee['path'].','.$user_id;
	}
	/**
     * 获取上级用户id
     * @param $user_id
     * @param bool $is_dealer 必须是分销商
     * @return bool|mixed
     * @throws \think\exception\DbException
     */
    public static function getRefereeUserId($user_id)
    {
        $user = (new self)->where(compact('user_id'))->find();
        return $user;
    }
	/**
     * 累积用户总消费金额
     * @param $money
     * @return int|true
     * @throws \think\Exception
     */
    public function cumulateMoney($money)
    {
        return $this->setInc('money', $money);
    }
	/**
     * 累积用户积分
     * @param $integral
     * @return int|true
     * @throws \think\Exception
     */
    public function integral($integral)
    {
        return $this->setInc('integral', $integral);
    }
	    /**
     * 资金冻结
     * @param $money
     * @return false|int
     */
    public  function freezeMoney($money)
    {
        return $this->save([
            'money' => $this['money'] - $money
        ]);
    }
	/**
     * 提现打款成功：累积提现佣金
     * @param $user_id
     * @param $money
     * @return false|int
     * @throws \think\exception\DbException
     */
	public static function totalMoney($user_id, $money)
    {
        $model = self::detail($user_id);
        return $model->save([
            'freeze_money' => $model['money'] - $money,
        ]);
    }
    /**
     * 累计分销商成员数量
     * @param $dealer_id
     * @param $level
     * @return int|true
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public static function setMemberInc($dealer_id, $level)
    {
        $fields = [1 => 'first_num', 2 => 'second_num', 3 => 'third_num'];
        $model = static::detail($dealer_id);
        return $model->setInc($fields[$level]);
    }
	/**
     * 提现驳回：解冻分销商资金
     * @param $user_id
     * @param $money
     * @return false|int
     * @throws \think\exception\DbException
     */
    public static function backFreezeMoney($user_id, $money)
    {
        $model = self::detail($user_id);
        return $model->save([
            'money' => $model['money'] + $money,
        ]);
    }
	/*
	 * 网页用户登录
     * @param array $post
     * @return string
     * @throws BaseException
	*/
	public function app_login($post){
		$user = self::useGlobalScope(false)->where([
            'phone' => $post['phone'],
            'password' => wymall_pass($post['password'])
        ])->find();
		if(!$user){
            return false;
        }else{
			// 生成token (session3rd)
			$this->token = $this->token($user['phone']);
			// 记录缓存, 7天
			Cache::set($this->token, $user, 86400 * 7);
			return $user['user_id'];
		}
	}
}