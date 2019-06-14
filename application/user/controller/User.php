<?php
namespace app\user\controller;
use app\common\model\User as UserModel;
use app\common\model\Level as LevelModel;
use app\common\model\Withdraw as WithdrawModel;
use app\common\model\Profit as ProfitModel;
/**
 * 用户管理
 * Class User
 * @package app\user\controller
 */
class User extends Controller
{
    /**
     * 用户列表
     * @param string $nickName
     * @param $gender
     * @param $pid
     * @param $is_delete
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($nickName = '', $gender = null,$pid=null,$is_delete=0)
    {
        $model = new UserModel;
        $list = $model->getList(trim($nickName), $gender,$pid,$is_delete);

        $page=$list->render();
        return $this->fetch('index', compact('list','page'));
    }
    /**
     * 用户收益列表
     * @param $uid
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function profit($uid=null)
    {
        $model = new ProfitModel;
        $list=$model::getlist($uid);
        return $this->fetch('profit', compact('list','page'));
    }
    /**
     * 会员领取列表
     * @param string $nickName
     * @param null $gender
     * @param $pid
     * @param $is_delete
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function card($nickName = '', $gender = null,$pid=null,$is_delete=0)
    {
        $nickName=trim($nickName);
        $model = new LevelModel;
        $list = $model->getList($nickName, $gender,$pid,$is_delete);
        $page=$list->render();
        return $this->fetch('card', compact('list','page'));
    }
    /**
     * 冻结用户列表
     * @param string $nickName
     * @param null $gender
     * @param null $pid
     * @param null $is_delete
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function frozen($nickName = '', $gender = null,$pid=null,$is_delete=1)
    {
        $nickName=trim($nickName);
        $model = new UserModel;
        $list = $model->getList($nickName,$gender,$pid,$is_delete);
        $page=$list->render();
        return $this->fetch('lock', compact('list','page'));
    }
    /**
     * 冻结用户
     * @param $user_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($user_id)
    {
        // 商品详情
        $model = UserModel::detail($user_id);
        if (!$model->setDelete()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('已冻结');
    }
    /**
     *删除用户
     *@param $user_id
     *@return array
     *@throws \think\exception\DbException
     */
    public function remove($user_id)
    {
        // 商品详情
        $model = UserModel::detail($user_id);
        if (!$model->removeDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    /**
     *解冻用户
     *@param $user_id
     *@return array
     *@throws \think\exception\DbException
     */
    public function uefreezing($user_id){
        // 商品详情
        $model = UserModel::detail($user_id);
        if (!$model->uefreezing()) {
            return $this->renderError('解冻失败');
        }
        return $this->renderSuccess('已解冻');
    }
    /**
     * 驳回
     * @param $id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function reject($id='')
    {
        $model = WithdrawModel::detail($id);
        $data=$this->postData('withdraw');
        if(!$model->submits($data)){
            return $this->fetch();
        }
    }
}