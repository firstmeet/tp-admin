<?php
namespace app\user\controller;
use app\common\model\Order as OrderModel;
/**
 * 订单操作控制器
 * Class Operate
 * @package app\user\controller\order
 */
class Operate extends Controller
{
    /* @var OrderModel $model */
    private $model;
    /**
     * 构造方法
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new OrderModel;
    }
    /**
     * 订单导出
     * @param string $dataType
     * @throws \think\exception\DbException
     */
    public function export($dataType)
    {	
        return $this->model->exportList($dataType, $this->request->get());
    }
    /**
     * 批量发货模板
     */
    public function deliveryTpl()
    {
        return $this->model->deliveryTpl();
    }
}