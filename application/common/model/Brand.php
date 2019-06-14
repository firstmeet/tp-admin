<?php
namespace app\common\model;
/**
 * 规格/属性(组)模型
 * Class Spec
 * @package app\common\model
 */
class Brand extends BaseModel
{
    protected $name = 'brand';
    protected $pk = 'id';
    protected $updateTime = false;
    /**
     * 分类图片
     * @return \think\model\relation\HasOne
     */
    public function images()
    {
        return $this->hasOne('uploadFile', 'id', 'logo')->bind(['file_path', 'file_name', 'file_url']);
    }
    /**
     * 所有分类
     * @return mixed
     */
    public static function getALL($name)
    {
		$where='';
		if($name)$where[]=['name','like','%'.$name.'%'];
		$model = new static;
		$data = $model->where($where)->with(['images'])->order(['id' => 'desc'])->paginate(10,false,['path'=>'index.php?s=/user/item/brand']);
        return $data;
    }
    /**
     * 新增品牌
     * @param $spec_name
     * @return false|int
     */
    public function add($data)
    {
		$data['app_id'] = self::$app_id;
        return  $this->allowField(true)->save($data);
    }
    /**
     * 编辑品牌
     * @param $spec_name
     * @return false|int
     */
    public function edit($data)
    {
        $data['app_id'] = self::$app_id;
        $where=['id','=',$data['id']];
        return $this->allowField(true)->save($data,$where);
    }
	  /**
     * 获取品牌详情
     * @param $id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($id)
    {	
        $model = new static;
        return $model->with(['images'])->where('id', '=', $id)->find();
    }
}