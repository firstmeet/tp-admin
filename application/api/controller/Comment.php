<?php

namespace app\api\controller;

use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods as OrderGoodsModel;
use app\common\model\Comment as CommentModel;
/**
 * 商品评价控制器
 * Class Comment
 * @package app\api\controller
 */
class Comment extends Controller
{
    /**
     * 商品评价列表
     * @param $item_id
     * @param int $scoreType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($item_id, $scoreType = -1)
    {
        $model = new CommentModel;
        $list = $model->getGoodsCommentList($item_id, $scoreType);
        $total = $model->getTotal($item_id);
        return $this->renderSuccess(compact('list', 'total'));
    }

	 public function order($order_id)
    {
		
        // 用户信息
        $user = $this->getUser();
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $user['user_id']);
        // 验证订单是否已完成
        $model = new CommentModel;
        if (!$model->checkOrderAllowComment($order)) {
            return $this->renderError($model->getError());
        }
        // 待评价商品列表
        /* @var \think\Collection $goodsList */
        $goodsList = OrderGoodsModel::getNotCommentGoodsList($order_id);
        if ($goodsList->isEmpty()) {
            return $this->renderError('该订单没有可评价的商品');
        }
        // 提交商品评价
        if ($this->request->isPost()) {
            $formData = $this->request->post('formData', '', null);
            if ($model->addForOrder($order, $goodsList, $formData)) {
                return $this->renderSuccess([], '评价发表成功');
            }
            return $this->renderError($model->getError() ?: '评价发表失败');
        }
        return $this->renderSuccess(compact('goodsList'));
    }
}