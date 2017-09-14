<?php
namespace app\index\widget;

use app\common\model;

class Zan
{

    public function show($siteid,$sign){
        $view = new \think\View();
        $str = $siteid.$sign.config('secretkey');
        $authcode = md5($str);
        $zancount = $this->getTotal($siteid,$sign);
        $view->assign('siteid', $siteid);
        $view->assign('sign', $sign);
        $view->assign('authcode', $authcode);
        $view->assign('zancount', $zancount);
        return $view->fetch('site_'.$siteid.'/widget/zan/show');
    }

    public function getTotal($siteid,$sign){
        $rs = model('Zan')->where("siteid = " . $siteid . " and sign = '".$sign."'")->find();
        if($rs){
            $total = $rs['total'];
        }else{
            $total = 0;
        }
        return $total;
    }

}
