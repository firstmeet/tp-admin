<?php

namespace app\common\model;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class OrderDelivery extends BaseModel
{
    protected $name = 'order_delivery';
	
	
	public static function getAll($order_no){	
	
		$a=self::where('order_no',$order_no)->all();

	}



}
