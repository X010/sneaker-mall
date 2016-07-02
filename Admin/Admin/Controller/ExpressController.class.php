<?php
namespace Admin\Controller;

use Think\Controller;

class ExpressController extends BaseController
{

    private $r = array("error" => 0, "msg" => "", "data" => "");
    private $limit = 13;

    /**
     * 列表
     */
    public function express()
    {
        $this->display('Express:express');
    }
}