<?php

namespace app\api\controller;

use app\common\model\Setting;
use app\common\model\User as DealerUserModel;
use app\common\model\Withdraw as WithdrawModel;

/**
 * 分销商提现
 * Class Withdraw
 * @package app\api\controller\user\dealer
 */
class Withdraw extends Controller
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
     * 提交提现申请
     * @param $data
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function submit($data)
    {
        $formData = json_decode(htmlspecialchars_decode($data), true);
        $model = new WithdrawModel;
        if ($model->submit($this->dealer, $formData)) {
            return $this->renderSuccess([], '申请提现成功');
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

    /**
     * 分销商提现明细
     * @param int $status
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($status = -1)
    {	
        $model = new WithdrawModel;

        return $this->renderSuccess([
            // 提现明细列表
            'list' => $model->getLists($this->user['user_id'], (int)$status),
            // 页面文字
            'words' => $this->setting['words']['values'],
        ]);
    }

}