<?php
namespace app\index\widget;

use app\common\model;

class Comment
{

    public function getCommentView($siteid,$sign,$title){
        $comments = model('Comment')->where("siteid = " . $siteid . " and sign = '".$sign."' and status = 1")->order("created desc")->select();
        foreach($comments as & $v){
            $v['replys'] = model('CommentReply')->order("replyid DESC")->where("commentid = " . $v['commentid'] . " and replystatus = 1")->select();
        }
        $view = new \think\View();
        $str = $siteid.$sign.$title.config('secretkey');
        $authcode = md5($str);
        $view->assign('siteid', $siteid);
        $view->assign('sign', $sign);
        $view->assign('title', $title);
        $view->assign('authcode', $authcode);
        $view->assign('comments', $comments);
        return $view->fetch('site_'.$siteid.'/widget/comment/showComment');
    }

    public function getTotal($siteid,$sign){
        $rs = model('CommentStats')->where("siteid = " . $siteid . " and sign = '".$sign."'")->find();
        if($rs){
           $total = $rs['total'];
        }else{
            $total = 0;
        }
        return $total;
    }

}
