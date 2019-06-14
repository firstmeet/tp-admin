<?php
namespace app\common\model;

class agentCapital extends BaseModel
{
    protected $name = 'agent_capital';
	
	public static function detail(){
		return self::all();
	}
}