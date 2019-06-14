<?php

namespace app\common\model;
use think\Model;
/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Integral extends Model
{
	   /**
     * 增加积分表详情
     * @param $assemble_id
	 * @param $user_id
	 * @param $note
	 * @param $app_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
   public function add($give_integral,$user_id,$note,$app_id)
   {
	   return $this->save([
	   'give_integral'=>$give_integral,
	   'user_id'      =>$user_id,
	   'note'         =>$note,
	   'app_id'       =>$app_id
	   ]);
   }
    /**
     * 获取积分详情
     * @param $assemble_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($id)
    {
        if($id)
        {
            $model = new static;
            return $model->where('id','=',$id)->find();
        }
    }
}