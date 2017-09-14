<?php
namespace app\admin\controller;

class Consult extends Base
{

    private $consultModel;
    private $replyModel;

    public function __construct(){
        parent::__construct();
        $this->consultModel = model('Consult');
        $this->replyModel = model('ConsultReply');
    }

    /*
     * 咨询列表
     */
    public function consultList(){

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
        $count = $this->consultModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $consults = $this->consultModel->where($where)->order("consultid desc")->limit($page->firstRow, $page->listRows)->select();
        //构造返回
        $this->assign("page", $page);
        $this->assign('consults', $consults);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

    /*
     * 回复
     */
    public function reply()
    {
        $consultid = input('param.consultid/d');
        $replyparent = input('param.replyparent/d');

        $replymessage = input('param.replymessage/s');
        if(empty($consultid)){
            return $this->error("请传入咨询id");
        }
        if(empty($replymessage)){
            return $this->error("请填写回复内容");
        }
        $consult = $this->consultModel->get($consultid);
        if(empty($consult)){
            return $this->error("未检索到咨询信息");
        }
        $replyData = array(
            'replyparent'   => $replyparent,
            'consultid'     => $consultid,
            'replyuserid'   => $this->admininfo['adminid'],
            'replyusername' => $this->admininfo['adminname'],
            'replymessage'  => $replymessage,
            'replytype'     => 2,
            'replytime'     => time()
        );
        $rs = $this->replyModel->insert($replyData);
        if(!$rs){
            return $this->error("回复失败");
        }
        if($replyparent == 0){
            $this->consultModel->where('consultid = ' . $consultid)->update(array('child' => 1));
        }else{
            $this->replyModel->where('replyid = ' . $replyparent)->update(array('child' => 1));
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
        $consultid = input('param.consultid/d',0);
        if(empty($consultid)){
            return $this->error("请传入咨询id");
        }
        $consult = $this->consultModel->get($consultid);
        if(empty($consult)){
            return $this->error("未检索到咨询信息");
        }
        //构造条件
        $where = array();
        $where[] = "consultid = " . $consultid;
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
        $this->assign('consult', $consult);
        $this->assign('replys', $replys);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }


}
