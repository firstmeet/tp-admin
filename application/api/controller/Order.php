<?php
namespace app\api\controller;
use app\common\model\Order as OrderModel;
use app\common\model\App as AppModel;
use app\common\model\Cart as CartModel;
use app\common\model\AppPrepayId as AppPrepayIdModel;
use app\common\library\wechat\WxPay;
/**
 * 订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;
    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function initialize()
    {	
        parent::initialize();
        $this->user = $this->getUser();   // 用户信息
    }
    /**
     * 订单确认-立即购买
     * @param $item_id
     * @param $goods_num
     * @param $item_sku_id
     * @param $coupon_id
     * @param string $remark
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow($item_id, $goods_num, $item_sku_id, $coupon_id = null, $remark = '', $prom = '', $other = '')
    {	
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow($this->user, $item_id, $goods_num, $item_sku_id ,$prom,$other);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($model->createOrder($this->user['user_id'], $order, $coupon_id, $remark,$prom,$item_id,$other)) {
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->unifiedorder($model, $this->user),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }
    /**
     * 订单确认-购物车结算
     * @param string $cart_ids (支持字符串ID集)
     * @param $coupon_id
     * @param string $remark
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart($cart_ids, $coupon_id = null, $remark = '')
    {	
        // 商品结算信息
        $Card = new CartModel($this->user['user_id']);
        $order = $Card->getList($this->user, $cart_ids);
        if (!$this->request->isPost()){
            return $this->renderSuccess($order);
        }
        // 创建订单
        $model = new OrderModel;
        if ($model->createOrder($this->user['user_id'], $order, $coupon_id, $remark)) {
            // 移出购物车中已下单的商品
            $Card->clearAll($cart_ids);
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->unifiedorder($model, $this->user),
                'order_id' => $model['order_id']
            ]);
        }
        return $this->renderError($model->getError() ?: '订单创建失败');
    }
    /**
     * 构建微信支付
     * @param $order
     * @param $user
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function unifiedorder($order, $user)
    {
        // 统一下单API
        $wxConfig = AppModel::getAppCache();
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($order['order_no'], $user['open_id'], $order['pay_price']);
        // 记录prepay_id
        $model = new AppPrepayIdModel;
        $model->add($payment['prepay_id'], $order['order_id'], $user['user_id']);
        return $payment;
    }
	/**
     * 我的订单列表
     * @param $dataType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }
    /**
     * 订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        return $this->renderSuccess(compact('order'));
    }
    /**
     * 获取物流信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function express($order_id)
    {
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        /* @var \app\store\model\Express $model */
        $model = $order['express'];
        $express = $model->dynamic($model['express_name'], $model['express_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }
    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess('收货成功');
        }
        return $this->renderError($model->getError());
    }
    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt($this->user)) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }
    /**
     * 立即支付
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function pay($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
		// 判断商品状态、库存
        if (!$order->checkStatusFromOrder($order['goods'])) {
            return $this->renderError($order->getError());
        }
        // 统一下单API
        $wxConfig = AppModel::getAppCache();
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($order['order_no'], $this->user['open_id'], $order['pay_price']);
        // 记录prepay_id
        $model = new AppPrepayIdModel;
        $model->add($payment['prepay_id'], $order['order_id'], $this->user['user_id']);
        return $this->renderSuccess($payment);
    }
}