<?php
namespace app\common\model;
use app\common\exception\BaseException;
use think\facade\Cache;
use think\facade\Session;
/**
 * 微信小程序模型
 * Class App
 * @package app\common\model
 */
class App extends BaseModel
{
    protected $name = 'app';
    protected $pk = 'app_id';
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'appid',
        'mchid',
        'apikey',
        'create_time',
        'update_time'
    ];
	
	
	 /**
     * 删除
     * @return \think\model\relation\HasOne
     */
    public function delete()
    {
        return parent::delete();
    }
	 /**
     * 关联文件库
     * @return \think\model\relation\BelongsTo
     */
    public function file()
    {
         return $this->belongsTo('UploadFile', 'image_id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }
    /**
     * 小程序导航
     * @return \think\model\relation\HasOne
     */
    public function navbar()
    {
        return $this->hasOne('AppNavbar');
    }
    /**
     * 关联小程序图片表 @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('UploadType')->order(['id' => 'asc']);
    }
    /**
     * 小程序页面
     * @return \think\model\relation\HasOne
     */
    public function diyPage()
    {
        return $this->hasOne('AppPage');
    }
    /**
     * 获取小程序信息
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($app_id=[])
    {
		$app_id?$where['app_id']=$app_id : $where=[];
        return self::get($where);
    }
 
    /**
     * 从缓存中获取小程序信息
     * @param null $app_id
     * @return mixed|null|static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function getAppCache($app_id = null)
    {
        if (is_null($app_id))
            $app_id = self::$app_id;
		
        if (!$data = Cache::get('app_' . $app_id)) {
            $data = self::with('file')->get($app_id);

            Cache::set('app_' . $app_id, $data,600);
		}
        return $data;
    }
    
 /**
     * 更新小程序设置
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        $where['app_id']=self::$app_id;
        return $this->allowField(true)->save($data,$where);
    }
    /**
     * 所有数据
     * @return mixed
     */
    public static function getAll()
    {
        $model = new static;
        $data = $model->with('file')->all();
        return $data;
    }
    /**
     * 编辑记录
     * @param $data
	 * @param $app_id
     * @return bool|int
     */
    public function editCategory($data)
    {
        $where['app_id']=self::$app_id;
        return $this->allowField(true)->save($data,$where);
    }
	
	
    private function uploadImage($app_id, $oldFileId, $newFileName, $fromType)
    {
        $UploadFileUsed = new UploadFileUsed;
        if ($oldFileId > 0) {
            // 获取原图片path
            $oldFileName = UploadFile::getFileName($oldFileId);
            // 新文件与原来路径一致, 代表用户未修改, 不做更新
            if ($newFileName === $oldFileName)
                return $oldFileId;
            // 删除原文件使用记录
            $UploadFileUsed->remove('service', $oldFileId);
        }
        // 删除图片
        if (empty($newFileName)) return 0;
        // 查询新文件file_id
        $fileId = UploadFile::getFildIdByName($newFileName);
        // 添加文件使用记录
        $UploadFileUsed->add([
            'id' => $fileId,
            'app_id' => $app_id,
            'from_type' => $fromType
        ]);
        return $fileId;
    }
    /**
     * 更改app信息
     * @return bool
     */
    public function addOn($app_id)
    {
        Session::set('app_id', $app_id);
    }
    /**
     * 删除app缓存
     * @return bool
     */
    public static function deleteCache()
    {
        return Cache::rm('app_' . self::$app_id);
    }
	
}