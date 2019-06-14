<?php

namespace app\api\controller;

use app\api\controller\Controller;
use app\common\model\Formid as FormidModel;

/**
 * form_id 管理
 * Class Formid
 * @package app\api\controller
 */
class Formid extends Controller
{
    /**
     * 新增form_id
     * @param $formId
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function save($formId)
    {
        if (!$user = $this->getUser(false)) {
            return $this->renderSuccess();
        }
        if (FormidModel::add($user['user_id'], $formId)) {
            return $this->renderSuccess();
        }
        return $this->renderError();
    }

}