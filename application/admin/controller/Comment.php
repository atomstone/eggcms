<?php
namespace app\admin\controller;

class Comment extends Base
{

    private $commentModel;
    private $replyModel;
    private $statsModel;

    public function __construct(){
        parent::__construct();
        $this->commentModel = model('Comment');
        $this->replyModel = model('CommentReply');
        $this->statsModel = model('CommentStats');
    }

    /*
     * 评论列表
     */
    public function lists(){
        //获取参数
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        //构造条件
        $where = array();
        $where[] = "siteid = " . $this->siteid;
        if(!empty($search)){
        foreach($search as $k => & $v){
            $v = trim($v);
            if($v != ''){
                if(in_array($k, array('title','message'))){
                    $where[] = "`$k` like '%{$v}%'";
                }else{
                    $where[] = "$k = '{$v}'";
                }
            }
        }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $this->commentModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $comments = $this->commentModel->where($where)->order("commentid desc")->limit($page->firstRow, $page->listRows)->select();

        //构造返回
        $this->assign("page", $page);
        $this->assign('comments', $comments);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }


    /*
     * 审核
     */
    public function audit()
    {
        $commentid = input('param.commentid/d');
        $statusval = input('param.statusval/d');
        if(empty($commentid)){
            return $this->error("请传入评论id");
        }
        if(empty($statusval) || !in_array($statusval, array(-1,1))){
            return $this->error("请选择审核结果");
        }

        $comment = $this->commentModel->get($commentid);
        if(empty($comment)){
            return $this->error("未检索到评论信息");
        }
        $upData = array(
            'status' => $statusval
        );
        $rs = $this->commentModel->where("commentid = {$commentid}")->update($upData);
        if(!$rs){
            return $this->error("审核失败");
        }
        return $this->success('审核成功');
    }


    /*
     * 回复
     */
    public function reply()
    {
        $commentid = input('param.commentid/d');
        $replymessage = input('param.replymessage/s');
        if(empty($commentid)){
            return $this->error("请传入评论id");
        }
        if(empty($replymessage)){
            return $this->error("请填写回复内容");
        }
        $comment = $this->commentModel->get($commentid);
        if(empty($comment)){
            return $this->error("未检索到评论信息");
        }
        $replyData = array(
            'replyparent'   => 0,
            'commentid'     => $commentid,
            'replyuserid'   => $this->admininfo['adminid'],
            'replyusername' => $this->admininfo['adminname'],
            'replymessage'  => $replymessage,
            'replytype'     => 2,
            'replytime'     => time(),
            'replystatus'   => 1
        );
        $rs = $this->replyModel->insert($replyData);
        if(!$rs){
            return $this->error("回复失败");
        }
        //增加统计
        $stats = $this->statsModel->where("siteid = " . $comment['siteid'] . " and sign = '".$comment['sign']."'")->find();
        if(!empty($stats)){
            $this->statsModel->where("statsid = " . $stats['statsid'])->setInc('total');
        }else{
            $this->statsModel->insert(array('siteid' => $comment['siteid'],'sign' => $comment['sign'],'total' => 1));
        }
        return $this->success('回复成功');
    }


    /*
     * 回复列表
     */
    public function replyList(){
        //获取参数
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        $commentid = input('param.commentid/d',0);
        if(empty($commentid)){
            return $this->error("请传入评论id");
        }
        $comment = $this->commentModel->get($commentid);
        if(empty($comment)){
            return $this->error("未检索到评论信息");
        }
        //构造条件
        $where = array();
        $where[] = "commentid = " . $commentid;
        if(!empty($search)){
            foreach($search as $k => & $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('replymessage'))){
                        $where[] = "`$k` like '%{$v}%'";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $this->replyModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $replys = $this->replyModel->where($where)->order("replyid desc")->limit($page->firstRow, $page->listRows)->select();

        //构造返回
        $this->assign("page", $page);
        $this->assign('comment', $comment);
        $this->assign('replys', $replys);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

}
