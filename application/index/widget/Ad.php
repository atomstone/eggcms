<?php
namespace app\index\widget;
use app\common\model;
class Ad
{


    public function showAd($siteid, $space_id, $limit = 100){
        $space = model('AdSpace')->where("siteid = ".$siteid." and space_id = '".$space_id."' and space_enable = 1")->find();
        if(empty($space)){
            return false;
        }
        $where = array();
        $where[] = 'space_id = ' . $space['space_id'];
        $where[] = 'ad_enable = 1';
        $where[] = 'ad_startdate < ' . time();
        $where[] = 'ad_enddate > ' . time();
        $where = implode(' and ', $where);
        $order = 'ad_listorder asc';
        $ads = model('Ad')->where($where)->order($order)->limit($limit)->select();
        if(empty($ads)){
            return false;
        }
        $view = new \think\View();
        $view->assign('space', $space);
        $view->assign('ads', $ads);
        return $view->fetch('widget/ad/showAd');
    }

    public function getAds($siteid, $space_id, $limit = 100){
        $space = model('AdSpace')->where("siteid = ".$siteid." and space_id = '".$space_id."' and space_enable = 1")->find();
        if(empty($space)){
            return false;
        }
        $where = array();
        $where[] = 'space_id = ' . $space['space_id'];
        $where[] = 'ad_enable = 1';
        $where[] = 'ad_startdate < ' . time();
        $where[] = 'ad_enddate > ' . time();
        $where = implode(' and ', $where);
        $order = 'ad_listorder asc';
        $ads = model('Ad')->where($where)->order($order)->limit($limit)->select();
        return $ads;
    }

}
