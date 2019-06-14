<?php
namespace app\index\controller;

class Index  extends \think\Controller
{

    public function index()
    {
		
        return $this->redirect('user/index/index',302);
    }

}
