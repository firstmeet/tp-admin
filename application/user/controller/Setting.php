<?php
namespace app\user\controller;
use app\common\library\sms\Driver as SmsDriver;
use app\common\model\Express;
use app\common\model\Setting as SettingModel;
use app\common\model\Region;
use app\common\model\Delivery as DeliveryModel;
use think\cache\driver\file as Driver;
/**
 * 系统设置
 * Class Setting
 * @package app\user\controller
 */
class Setting extends Controller
{
    /**
     * 商城设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
		if (!$this->request->isAjax()) {
            $values = SettingModel::getAll();
			//dump($values );die;
            return $this->fetch('store', compact('values'));
        } 
        $model = new SettingModel;	
	   
        if ($model->editAgent($this->postData('setting'))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
    /**
     * 交易设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function trade()
    {
        return $this->updateEvent('trade');
    }
    /**
     * 短信通知
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function sms()
    {
        return $this->updateEvent('sms');
    }
    /**
     * 模板消息
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function tplmsg()
    {
        return $this->updateEvent('setting/tplmsg/tplmsg');
    }
    /**
     * 发送短信通知测试
     * @param $AccessKeyId
     * @param $AccessKeySecret
     * @param $sign
     * @param $msg_type
     * @param $template_code
     * @param $accept_phone
     * @return array
     * @throws \think\Exception
     */
    public function smsTest($AccessKeyId, $AccessKeySecret, $sign, $msg_type, $template_code, $accept_phone)
    {
        $SmsDriver = new SmsDriver([
            'default' => 'aliyun',
            'engine' => [
                'aliyun' => [
                    'AccessKeyId' => $AccessKeyId,
                    'AccessKeySecret' => $AccessKeySecret,
                    'sign' => $sign,
                    $msg_type => compact('template_code', 'accept_phone'),
                ],
            ],
        ]);
        $templateParams = [];
        if ($msg_type === 'order_pay') {
            $templateParams = ['order_no' => '2018071200000000'];
        }
        if ($SmsDriver->sendSms($msg_type, $templateParams, true)) {
            return $this->renderSuccess('发送成功');
        }
        return $this->renderError('发送失败 ' . $SmsDriver->getError());
    }
    /**
     * 上传设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function storage()
    {
        return $this->updateEvent('storage');
    }
    /**
     * 更新商城设置事件
     * @param $key
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    private function updateEvent($key)
    {	
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem($key);
            return $this->fetch($key, compact('values'));
        }
        $model = new SettingModel;
        if ($model->edit($key, $this->postData($key))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }
	  /**
     * 配送模板列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function delivery()
    {
        $model = new SettingModel;
        $list = $model->getList();
        return $this->fetch('setting/delivery/index', compact('list'));
    }
	public function edit($delivery_id = '')
    {
		 if (!$this->request->isAjax()) {
            // 获取所有地区
			// 模板详情
            $regionData = json_encode(Region::getCacheTree());
            $model = DeliveryModel::detail($delivery_id);
            return $this->fetch('setting/delivery/edit', compact('regionData','model','rule'));
        }
        // 新增记录
        $Setting = new DeliveryModel;
		 $data=$this->postData('delivery');
        if ($data['delivery_id'] ? $Setting->edit($data):$Setting->add($data)) {
            return $this->renderSuccess('操作成功', url('setting/delivery'));
        }
        $error = $Setting->getError() ?: '操作失败';
        return $this->renderError($error);
	}
	 /**
     * 物流公司列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function express()
    {
        $list = (new Express)->getList();
        return $this->fetch('setting/express/index', compact('list'));
    }
	public function addexpress($express_id = ''){
		if (!$this->request->isAjax()) {
			$express=(new Express)->get(['express_id',$express_id]);
            return $this->fetch('setting/express/edit',compact('express'));
        }
		// 新增记录
        $model = new Express;
        $data=$this->postData('express');
        if ($data['express_id']?$model->edit($data):$model->add($data)) {
            return $this->renderSuccess('操作成功', url('setting/express'));
        }
        return $this->renderError($model->getError() ?: '操作失败');
	}
	 /**
     * 分销设置
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function agent()
    {
        if (!$this->request->isAjax()){
            $data = SettingModel::getAll();	
            return $this->fetch('agent', compact('data'));
        }
		$model = new SettingModel;
        if ($model->editAgent($this->postData('setting'))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
	 /**
     * 清理缓存
     * @param bool $isForce
     * @return mixed
     */
    public function clear($isForce = false)
    {
        if ($this->request->isAjax()) {
            $data = $this->postData('cache');
            $this->rmCache($data['keys'], isset($data['isForce']) ? !!$data['isForce'] : false);
            return $this->renderSuccess('操作成功');
        }
        return $this->fetch('clear', [
            'cacheList' => $this->getItems(),
            'isForce' => !!$isForce ?: config('app_debug'),
        ]);
    }
	    /**
     * 数据缓存项目
     * @return array
     */
    private function getItems()
    {
        $wxapp_id = $this->app_id;
        return [
            'setting' => [
                'type' => 'cache',
                'key' => 'setting_' . $wxapp_id,
                'name' => '商城设置'
            ],
            'category' => [
                'type' => 'cache',
                'key' => 'category_' . $wxapp_id,
                'name' => '商品分类'
            ],
            'wxapp' => [
                'type' => 'cache',
                'key' => 'wxapp_' . $wxapp_id,
                'name' => '小程序设置'
            ],
            'temp' => [
                'type' => 'file',
                'name' => '临时图片',
                'dirPath' => [
                    'web' => WEB_PATH . 'temp' .'/'. $wxapp_id . '/',
                    'runtime' => RUNTIME_PATH .'/' . 'image' . '/' . $wxapp_id . '/'
                ]
            ],
        ];
    }
	 /**
     * 删除缓存
     * @param $keys
     * @param bool $isForce
     */
    private function rmCache($keys, $isForce = false)
    {	
        if ($isForce === true) {
        } else {
            $cacheList = $this->getItems();
            $keys = array_intersect(array_keys($cacheList), $keys);
            foreach ($keys as $key) {
                $item = $cacheList[$key];
                if ($item['type'] === 'cache') {
					$driver=new Driver;
                    $driver->has($item['key']) && $driver->rm($item['key']);
                } elseif ($item['type'] === 'file') {
                    $this->deltree($item['dirPath']);
                }
            }
        }
    }
    /**
     * 删除目录下所有文件
     * @param $dirPath
     * @return bool
     */
    private function deltree($dirPath)
    {
        if (is_array($dirPath)) {
            foreach ($dirPath as $path)
                $this->deleteFolder($path);
        } else {
            return $this->deleteFolder($dirPath);
        }
        return true;
    }
	    /**
     * 递归删除指定目录下所有文件
     * @param $path
     * @return bool
     */
    private function deleteFolder($path)
    {
        if (!is_dir($path))
            return false;
        // 扫描一个文件夹内的所有文件夹和文件
        foreach (scandir($path) as $val) {
            // 排除目录中的.和..
            if (!in_array($val, ['.', '..'])) {
                // 如果是目录则递归子目录，继续操作
                if (is_dir($path . $val)) {
                    // 子目录中操作删除文件夹和文件
                    $this->deleteFolder($path . $val . DS);
                    // 目录清空后删除空文件夹
                    rmdir($path . $val . DS);
                } else {
                    // 如果是文件直接删除
                    unlink($path . $val);
                }
            }
        }
        return true;
    }
	    /**
     * 删除
     */
    public function delete($id='',$table='')
	{
		$model= "app\\common\\model\\".$table;
		if($table=='item'){
			$detail = ItemModel::detail($goods_id);
			if($detail['prom_type']>0)return $this->renderSuccess('该商品下有营销分类,请先删除');
		}
		if($table=='category'){
			if ($goodsCount = (new ItemModel())->getGoodsTotal(['goods_id' => $id])) {
				return $this->renderError ('该分类下存在' . $goodsCount . '个商品，不允许删除');
			}
            // 判断是否存在子分类
			if ((new Category)->get(['pid','=', $id]))return  $this->renderError('该分类下存在子分类，请先删除');
		}
		if (!(new $model)->destroy($id)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
	}
	 /**
     * 分销海报
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function qrcode()
    {
		  $model = new SettingModel;
        if (!$this->request->isAjax()) {
           $data = $model::getItem('qrcode');
			/* $data['backdrop']["src"]="https://bfb1.iirr5.com/uploads/20190415212159ef0054150.png";
			$data['nickName']["fontSize"]="14";
			$data['nickName']["color"]="#8000ff";
			$data['nickName']["left"]="150";
			$data['nickName']["top"]="99";
			$data['avatar']["width"]="70";
			$data['avatar']["style"]="circle";
			$data['avatar']["left"]="150";
			$data['avatar']["top"]="18";
			$data['qrcode']["width"]="100";
			$data['qrcode']["style"]="square";
			$data['qrcode']["left"]="126";
			$data['qrcode']["top"]="128";  */
            return $this->fetch('poster', [
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ]);
        }
        if ($model->edit(['qrcode' => $this->postData('qrcode')])) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
	/**
     * 物流公司编码表
     * @return mixed
     */
    public function company()
    {
        return $this->fetch('setting/express/company');
    }
	/**
     * 如何获取模板消息ID
     * @return mixed
     */
	public function get()
    {
        return $this->fetch('setting/tplmsg/get');
    }
}