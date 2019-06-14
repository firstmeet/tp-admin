<?php

namespace app\api\controller;

use app\common\model\Coupon as CouponModel;
use app\common\model\UserCoupon as UserCouponModel;

/**
 * 优惠券中心
 * Class Coupon
 * @package app\api\controller
 */
class Coupon extends Controller
{
	
	/* @var UserCouponModel $model */
    private $model;

    /* @var \app\api\model\User $model */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new UserCouponModel;
        $this->user = $this->getUser();
    }

    /**
     * 我的优惠券列表
     * @param string $data_type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function mylist($data_type = 'all')
    {
        $is_use = false;
        $is_expire = false;
        switch ($data_type) {
            case 'not_use':
                $is_use = false;
                break;
            case 'is_use':
                $is_use = true;
                break;
            case 'is_expire':
                $is_expire = true;
                break;
        }
        $list = $this->model->getList($this->user['user_id'], $is_use, $is_expire);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 领取优惠券
     * @param $coupon_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function receive($coupon_id)
    {
        if ($this->model->receive($this->user, $coupon_id)) {
            return $this->renderSuccess([], '领取成功');
        }
        return $this->renderError($this->model->getError() ?: '添加失败');
    }
	
	
    /**
     * 优惠券列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        $model = new CouponModel;
        $list = $model->getList($this->getUser(false));
        return $this->renderSuccess(compact('list'));
    }
}