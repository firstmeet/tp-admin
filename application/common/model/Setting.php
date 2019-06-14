<?php
namespace app\common\model;
use app\common\model\Express as Expressmodel;
use think\facade\Cache;
use app\common\exception\BaseException;
use think\facade\Request;
/**
 * 系统设置模型
 * Class Setting
 * @package app\common\model
 */
class Setting extends BaseModel
{
    protected $name = 'setting';
		/**
     * 设置项描述
     * @var array
     */
    private $describe = [
		'store' => '商城设置',
		'trade' => '交易设置',
		'storage' => '上传设置',
		'sms' => '短信通知',
		'tplMsg' => '模板消息',
        'basic' => '基础设置',
        'condition' => '分销商条件',
		'jifen'=>'签到积分',
        'commission' => '佣金设置',
        'settlement' => '结算',
        'words' => '自定义文字',
        'license' => '申请协议',
        'background' => '页面背景图',
        'template_msg' => '模板消息',
        'qrcode' => '分销海报',
		 'market' => '营销设置',
    ];
	   /**
     * 设置项描述
     * @var array
     */
	public $group_list = array(
		'store' => '网站信息',
		'basic' => '基本设置',
		'sms' => '短信设置',
		'shopping' => '购物流程设置',
		'smtp' => '邮件设置',
		'water' => '水印设置',
		'distribut' => '分销设置'
	);
    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return mixed
     */
    public function getValuesAttr($value)
    {
        return json_decode($value, true);
    }
    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value)
    {
        return json_encode($value);
    }
	   /**
     * 是否开启分销功能
     * @param null $app_id
     * @return mixed
     */
    public static function isOpen($app_id = null)
    {
        return static::getItem('basic', $app_id)['is_open'];
    }
	 /**
     * 分销中心页面名称
     * @param null $app_id
     * @return mixed
     */
    public static function getDealerTitle($app_id = null)
    {
        return static::getItem('words', $app_id)['index']['title']['value'];
    }
    /**
     * 获取指定项设置
     * @param $key
     * @param $app_id
     * @return array
     */
    public static function getItem($key, $app_id = null)
    {
        $data = static::getAll($app_id);
		if(!isset($data[$key])){
			$data=self::defaultData();
		}
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }
    /**
     * 获取设置项信息
     * @param $key
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($key)
    {
        return self::get(compact('key'));
    }
    /**
     * 全局缓存: 系统设置
     * @param null $app_id
     * @return array|mixed
     */
    public static function getAll()
    {
        $self = new static;
        //if (!$data = Cache::get('setting_' . $app_id)) {
            $data = array_column($self::all()->toArray(), null, 'key');
			
			foreach( $data as $key => $val){
						$data[$key]['values']=	unserialize($val['values']);	
						
			}
            Cache::set('setting_' . self::$app_id, $data);
       // }
		return $data;
    }
	/**
     * 更新系统设置
     * @param $key
     * @param $values
     * @return bool
     * @throws \think\exception\DbException
     */
     public function edit($data)
    {	 //删除已存在的文件
        $this->startTrans();
        try {
            foreach ($data as $key => $values)
                $this->saveValues($key, $values);
            $this->commit();
			//更换图片同时删除已有的二维码
			$tempPath = WEB_PATH . 'uploads' . '/' .self::$app_id . '/';
			$dirPath = WEB_PATH  . 'uploads' . '/image' . '/' . self::$app_id.'/';
			$this->deldir($tempPath);
			$this->deldir($dirPath); 
            // 删除系统设置缓存
            Cache::rm('dealer_setting_' . self::$app_id);
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }
	/**
     * 获取列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return (new Delivery)->alias('delivery')
            ->order(['sort' => 'asc'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }
		    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delivery($data,$deliveryid) {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }
		$data['app_id']=self::$app_id;
		$data['create_time']=time();
		$datas=$data;
		unset($datas['rule']);
		$model=new Delivery;
		if($deliveryid){
			$model->where('delivery_id',$deliveryid)->update($datas);
		}else{
			$deliveryid=$model->insertGetId($datas,true);	
		}
        if ($deliveryid) {
            return $this->createDeliveryRule($data['rule'],$deliveryid);
        }
        return false;
    }
		    /**
     * 添加模板区域及运费
     * @param $data
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function createDeliveryRule($data,$deliveryid)
    {	
        $save = [];
        $connt = count($data['region']);
		$model=new DeliveryRule;
        for ($i = 0; $i < $connt; $i++) {
            $save[] = [
                'region' => $data['region'][$i],
                'first' => $data['first'][$i],
                'first_fee' => $data['first_fee'][$i],
                'additional' => $data['additional'][$i],
                'additional_fee' => $data['additional_fee'][$i],
                'app_id' => self::$app_id,
				'delivery_id' => $deliveryid,
            ];
        }
        $model->where(array('delivery_id'=>$deliveryid))->delete();
        return $model->insertAll($save);
    }
	/**
     * 添加物流
     * @param $data
     * @return false|int
     */
    public function addExpress($data)
    {
            $model=new Expressmodel;
			$data['app_id'] = self::$app_id;
			return $model->allowField(true)->save($data);
    }
	    /**
     * 更新系统设置
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function editAgent($data)
    {
		$array=[];
		foreach ($data as $key => $values){
			$this->saveValues($key, $values);
		}
		return true;
    }
	    /**
     * 保存设置项
     * @param $key
     * @param $values
     * @return false|int
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    function saveValues($key,$val)
    {
	    // 数据验证
        /* if (!$this->validValues($key, $val)) {
            throw new BaseException(['msg' => $this->error]);			
        } */
	  $model = self::detail($key) ?: new self;
        // 数据验证 
       return $model->save([
            'key' => $key,
            'describe' => $this->describe[$key],
            'values' => serialize($val),
            'app_id' => self::$app_id,
        ]);
    }
	    /**
     * 数据验证
     * @param $key
     * @param $values
     * @return bool
     */
     function validValues($key, $values)
    {
        if ($key === 'settlement') {
            // 验证结算方式
            return $this->validSettlement($values);
        }
        return true;
    }
    /**
     * 验证结算方式
     * @param $values
     * @return bool
     */
     function validSettlement($values)
    {
        if (!isset($values['pay_type']) || empty($values['pay_type'])) {
            $this->error = '请设置 结算-提现方式';
            return false;
        }
        return true;
    }
	   //清空文件夹函数和清空文件夹后删除空文件夹函数的处理
    function deldir($path){
         //如果是目录则继续
         if(is_dir($path)){
             //扫描一个文件夹内的所有文件夹和文件并返回数组
            foreach(scandir($path) as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($path.$val)){
                        //子目录中操作删除文件夹和文件
                        deldir($path.$val.'/');
                        //目录清空后删除空文件夹
                        @rmdir($path.$val.'/');
                    }else{
                        //如果是文件直接删除
                        unlink($path.$val);
                    }
                }
            }
        }
     }
	   /**
     * 默认配置
     * @return array
     */
    public static function defaultData()
    {
        return [
            'qrcode' => [
                'key' => 'template_msg',
                'describe' => '分销海报',
                'values' => [
                    'backdrop' => [
                        'src' => self::$base_url . 'assets/store/img/dealer/backdrop.png',
                    ],
                    'nickName' => [
                        'fontSize' => 14,
                        'color' => '#000000',
                        'left' => 150,
                        'top' => 99
                    ],
                    'avatar' => [
                        'width' => 70,
                        'style' => 'circle',
                        'left' => 150,
                        'top' => 18
                    ],
                    'qrcode' => [
                        'width' => 100,
                        'style' => 'circle',
                        'left' => 136,
                        'top' => 128
                    ]
                ],
            ]
        ];
    }
}