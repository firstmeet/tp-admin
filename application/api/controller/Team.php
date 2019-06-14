<?php

namespace app\api\controller;

use app\api\controller\Controller;
use app\common\model\Setting;
use app\common\model\User as UserModel;
/**
 * 我的团队
 * Class Order
 * @package app\api\controller\user\dealer
 */
class Team extends Controller
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
        // 分销商设置
        $this->setting = Setting::getAll();
    }

    /**
     * 我的团队列表
     * @param int $level
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($level = -1,$token ='')
    {
        $model = new UserModel;
        return $this->renderSuccess([
            // 分销商用户信息
            'dealer' => $model::getUser($token),
            // 提现明细列表
            'list' => $model->teamlist($this->user['user_id'], (int)$level),
            // 基础设置
            'setting' => $this->setting['basic']['values'],
            // 页面文字
            'words' => $this->setting['words']['values'],
        ]);
    }

}