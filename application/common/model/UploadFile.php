<?php
namespace app\common\model;
use think\facade\Request;
use traits\model\SoftDelete;
/**
 * 文件库模型
 * Class UploadFile
 * @package app\common\model
 */
class UploadFile extends BaseModel
{
    //use SoftDelete;
    protected $name = 'upload_file';
    protected $append = ['file_path'];
	/**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'app_id',
        'create_time',
    ];
    /**
     * 获取图片完整路径
     * @param $value
     * @param $data
     * @return string
     */
    public function getFilePathAttr($value, $data)
    {
        if ($data['storage'] === 'local') {
            return base_Url() . 'uploads/' . $data['file_name'];
        }
        return $data['file_url'] . '/' . $data['file_name'];
    }
    /**
     * 根据文件名查询文件id
     * @param $fileName
     * @return mixed
     */
    public static function getFildIdByName($fileName)
    {
        return (new static)->where(['file_name' => $fileName])->value('id');
    }
    /**
     * 查询文件id
     * @param $fileId
     * @return mixed
     */
    public static function getFileName($fileId)
    {
        return (new static)->where(['id' => $fileId])->value('file_name');
    }
    /**
     * 获取列表记录
     * @param $group_id
     * @param string $file_type
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($group_id, $file_type = 'image')
    {
        if ($group_id != -1) {
            $this->where(compact('group_id'));
        }
        return $this->where([
            'file_type' => $file_type,
            'is_user' => 0,
            'is_delete' => 0
        ])->order(['id' => 'desc'])
            ->paginate(32, false, [
                'query' => Request::instance()->request()
            ]);
    }
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        return $this->save($data);
    }
	 /**
     * 批量软删除
     * @param $fileIds
     * @return $this
     */
    public function softDelete($fileIds)
    {
        return $this->where('id', 'in', $fileIds)->update(['is_delete' => 1]);
    }
    /**
     * 批量移动文件分组
     * @param $group_id
     * @param $fileIds
     * @return $this
     */
    public function moveGroup($group_id, $fileIds)
    {
        return $this->where('id', 'in', $fileIds)->update(compact('group_id'));
    }
}