<?php
namespace app\user\controller;
use app\common\model\UploadFile;
use app\common\library\storage\Driver as StorageDriver;
use app\common\model\Setting as SettingModel;

/**
 * 文件库管理
 * Class Upload
 * @package app\user\controller
 */
class Upload extends Controller
{
    private $config;

    /**
     * 构造方法
     */
    public function initialize()
    {
        parent::initialize();
        // 存储配置信息
   
        $this->config = SettingModel::getItem('storage');
	
    }

    /**
     * 图片上传接口
     * @param int $group_id
     * @return array
     * @throws \think\Exception
     */
    public function image($group_id = -1)
    {
		
        // 实例化存储驱动
	
        $StorageDriver = new StorageDriver($this->config);
		
        // 上传图片
        if (!$StorageDriver->upload())
            return json(['code' => 0, 'msg' => '图片上传失败' . $StorageDriver->getError()]);
        // 图片上传路径
        $fileName = $StorageDriver->getFileName();
        // 图片信息
        $fileInfo = $StorageDriver->getFileInfo();
        // 添加文件库记录
        $uploadFile = $this->addUploadFile($group_id, $fileName, $fileInfo, 'image');
        // 图片上传成功
        return json(['code' => 1, 'msg' => '图片上传成功', 'data' => $uploadFile]);
    }

    /**
     * 添加文件库上传记录
     * @param $group_id
     * @param $fileName
     * @param $fileInfo
     * @param $fileType
     * @return UploadFile
     */
    private function addUploadFile($group_id, $fileName, $fileInfo, $fileType)
    {
        // 存储引擎
        $storage = $this->config['default'];
        // 存储域名
        $fileUrl = isset($this->config['engine'][$storage]) ? $this->config['engine'][$storage]['domain'] : '';
        // 添加文件库记录
        $model = new UploadFile;
		
        $model->add([
            'group_id' => $group_id > 0 ? (int)$group_id : 0,
            'storage' => $storage,
            'file_url' => $fileUrl,
            'file_name' => $fileName,
            'file_size' => $fileInfo['size'],
            'file_type' => $fileType,
			'user_id'   => $this->store['user_id'],
            'extension' => pathinfo($fileInfo['name'], PATHINFO_EXTENSION),
        ]);
        return $model;
    }
	
	
	public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('file');

    // 移动到框架应用根目录/uploads/ 目录下
    $info = $file->move( './uploads/');
	
		if($info){
		
			$data['src']="https://".$_SERVER['SERVER_NAME'].'/uploads/'.$info->getSaveName();
			$data['title']='';
			 return json(['code' => 0, 'msg' => '图片上传成功', 'data' => $data]);
		}else{
			// 上传失败获取错误信息
			return json(['code' => 1, 'msg' => '图片上传失败', 'data' => $info->getSaveName()]);
		}
	}
	
	
	

}
