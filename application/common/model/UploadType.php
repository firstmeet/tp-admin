<?php
namespace app\common\model;
/**
 * 商品图片模型
 * Class GoodsImage
 * @package app\common\model
 */
class UploadType extends BaseModel
{
    protected $name = 'upload_type';
    /**
     * 关联文件库
     * @return \think\model\relation\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('UploadFile', 'image_id', 'id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }
	/* public function uploadfile()
    {
        return $this->belongsTo('uploadFile','image_id','id');
    } */
}