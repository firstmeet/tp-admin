<?php

namespace app\api\controller;

use app\common\model\Spike as SpikeModel;

class Spike extends Controller
{
	public function spikeList($time)
	{
		return $this->renderSuccess([
			'spikeList' => SpikeModel::spikeList($time)
		]);
	}	
}