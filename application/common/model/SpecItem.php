<?php
namespace app\common\model;
/**
 * 规格/属性(组)模型
 * Class Spec
 * @package app\common\model
 */
class SpecItem extends BaseModel
{
    protected $name = 'spec_item';
    protected $updateTime = false;
        /**
     * 获取 wy_spec_item表 指定规格id的 规格项
     * @param int $spec_id 规格id
     * @return array 返回数组
     */
    public function getSpecItem($spec_id)
    { 
        $arr = $this->where("spec_id = $spec_id")->order('id')->select(); 
        $arr = $this->get_id_val($arr, 'id','item');     
        return $arr;
    } 
	/**
	 * @param $arr
	 * @param $key_name
	  * @param $key_name2
	 * @return array
	 * 将数据库中查出的列表以指定的 id 作为数组的键名 数组指定列为元素 的一个数组
	 */
	function get_id_val($arr, $key_name,$key_name2)
	{
		$arr2 = array();
		foreach($arr as $key => $val){
			$arr2[$val[$key_name]] = $val[$key_name2];
		}
		return $arr2;
	}
	 /**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $id 规格id
     */
    public function saveAfter($id='',$items)
    {
        $model = new static; // 实例化User对象
        $post_items = explode(",",$items);
		//删除所有的规格
       if($id) $model->where('spec_id','=', $id)->delete();
        /*数据插入*/
		$dataList=[];
        foreach($post_items as $key => $val)
        {
            $val = str_replace('_', '', $val); // 替换特殊字符
            $val = str_replace('@', '', $val); // 替换特殊字符
            $val = trim($val);
            $dataList[] = array(
                'spec_id'=>$id,
                'item'=>$val,
                'app_id'=>self::$app_id
            );
        }
        // 批量添加数据
        return $model->saveAll($dataList);
    } 
}