<?php

namespace app\api\controller;

use app\common\model\Setting;
use app\common\model\User as DealerUserModel;
use app\common\model\Order as OrderModel;

/**
 * 分销商订单
 * Class Order
 * @package app\api\controller\user\dealer
 */
class Agent extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    private $dealer;
    private $setting;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function initialize()
    {
        parent::initialize();
        // 用户信息
        $this->user = $this->getUser();
        // 分销商用户信息
        $this->dealer = DealerUserModel::detail($this->user['user_id']);
        // 分销商设置
        $this->setting = Setting::getAll();
    }

    /**
     * 分销商订单列表
     * @param int $settled
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($settled = -1)
    {
		
        $model = new OrderModel;
        return $this->renderSuccess([
            // 提现明细列表
            'list' => $model->getList($this->user['user_id'], (int)$settled),
            // 页面文字
            'words' => $this->setting['words']['values'],
        ]);
    }

}