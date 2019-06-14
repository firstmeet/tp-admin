<?php
namespace app\user\controller;
use app\common\model\Order as OrderModel;
use app\common\model\Express as ExpressModel;
use app\common\model\OrderDelivery;
use app\common\model\Setting;
/**
 * 订单管理
 * Class Order
 * @package app\user\controller
 */
class Order extends Controller
{
    /**
     * 待发货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function deliveryList()
    {

        return $this->getList('待发货订单列表', 'delivery','');
    }
    /**
     * 待收货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receiptList()
    {

        return $this->getList('待收货订单列表', 'receipt','');
    }
    /**
     * 待处理售后列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function etcSaleList($dataType='etcsalelist',$user_id='')
    {
        $model = new OrderModel;
        $list = $model->getLists('etcsalelist', $this->request->get(),$user_id);
        return $this->fetch('etcsale', compact('title', 'dataType', 'list'));
    }

    /**
     * 已处理售后列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function inSaleList($dataType='inSaleList',$user_id='')
    {
        $model = new OrderModel;
        $list = $model->getLists($dataType, $this->request->get(),$user_id);
        return $this->fetch('insale', compact('title', 'dataType', 'list'));
    }
    /**
     * 售后详情
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function saleDetail($order_id)
    {
        return $this->fetch('saledetail', [
            'detail' => OrderModel::detail($order_id),
            'express_list' => ExpressModel::getAll()
        ]);
    }

    /**
     * 分佣佣金
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function gold($dataType='gold',$user_id='')
    {
        $model = new OrderModel;
        $list = $model->getGold($dataType);
        $values = Setting::detail('basic')['values'];
        $setting = unserialize($values);
        if($setting['is_open']==2){
            return $this->Error('分销功能已关闭');
        }
        return $this->fetch('gold', compact('dataType', 'list','setting'));
    }

    /**
     * 分佣
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function Sub($order_id='')
    {
        $model =OrderModel::detail($order_id);

        if ($model->path($model['item_id'],$model['pay_price'],$model['user']['path'],1)){
            $model->save([
                'end_time'=>time(),
                'order_status'=>30,
            ]);
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError('操作失败');
    }

    /**
     * 一键分佣
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function SubAll()
    {
        $model = new OrderModel;
        $list = $model->getGold('gold');
        if($list){
            return $this->renderError('分佣列表为空');
        }
        foreach($model as $key =>$val)
        {
            if($data=$model->path($val['item_id'],$val['pay_price'],$val['user']['path'],1)){
                $data->save([
                    'end_time'=>time(),
                    'order_status'=>30,
                ]);
            }

        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 待付款订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function payList()
    {
        return $this->getList('待付款订单列表', 'pay','');
    }
    /**
     * 已完成订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function completeList()
    {
        return $this->getList('已完成订单列表', 'complete','');
    }
    /**
     * 已取消订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function cancelList($user_id = null)
    {
        return $this->getList('已取消订单列表', 'cancel',$user_id);
    }
    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function allList($user_id = null)
    {
        return $this->getList('全部订单列表', 'All',$user_id);
    }
    /**
     * 订单详情
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $detail=OrderModel::detail($order_id);
        return $this->fetch('detail',[
            'detail' => $detail,
            'express_list' => ExpressModel::getAll(),
            'orderdelivery' => OrderDelivery::getAll($detail['order_no'])
        ]);

    }
    /**
     * 确认发货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function delivery($order_id)
    {

        $model = OrderModel::detail($order_id);
        if ($model->delivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($model->getError() ?: '发货失败');
    }
    /**
     * 修改订单价格
     * @param $order_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function updatePrice($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->updatePrice($this->postData('order'))) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }
    /**
     * 订单列表
     * @param string $title
     * @param string $dataType
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $dataType,$user_id)
    {
        $model = new OrderModel;
        $list = $model->getLists($dataType, $this->request->post(),$user_id);

        return $this->fetch('index', compact('title', 'dataType', 'list'));
    }
    /**
     * 批量发货
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function give()
    {

        if (!$this->request->isAjax()) {

            return $this->fetch('give', [
                'express' => ExpressModel::getAll()
            ]);
        }
        if ($this->model->batchDelivery($this->postData('order'))) {
            //dump(8888);die;
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($this->model->getError() ?: '发货失败');
    }
    /**
     * 批量发货
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function batchDelivery()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('batchDelivery', [
                'express' => ExpressModel::getAll()
            ]);
        }
        if ($this->model->batchDelivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($this->model->getError() ?: '发货失败');
    }
}