<?php

namespace app\api\controller;

use app\common\model\Turntable as TurntableModel;
use app\common\model\PrizeList as PrizeListModel;

class Turntable extends Controller
{
	/**
     * 获取活动详情
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
	public function turntable($type)
	{
		$model = new TurntableModel;
		$prize = $model->getPrize($type);
		return $this->renderSuccess(compact('prize'));
	}
	
	
	/**
     * 添加抽奖信息
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
	public function luckDraw($id)
	{
		$model = new PrizeListModel;
		if ($data = $model->add($id,$this->getUser())) {
            return is_string($data) ? $this->renderError($data) :$this->renderSuccess('添加成功',compact('data'));
        }
		return $this->renderError($this->model->getError() ?: '添加失败');
	}
}