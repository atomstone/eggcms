<?php
namespace app\index\controller;
use app\common\model;
class Consult extends Base
{

    private $consultModel;
    private $replyModel;

    public function __construct(){
        parent::__construct();
        //判断是否登录
        if(empty($this->memberinfo)){
            cookie('refurl_log', '/'.Request()->pathinfo());
            $this->redirect('Index/reg');
        }
        $this->consultModel = model('Consult');
        $this->replyModel = model('ConsultReply');
    }


    /*
     * 我的咨询主页
     */
    public function consultIndex()
    {
        $sign = input('param.sign/s');
        $title = input('param.title/s');
        $authcode = input('param.authcode/s');
        $url = input('param.url/s');

        if(empty($sign) || empty($title) || empty($authcode) || empty($url)){
            return $this->error('参数错误');
        }
        $this->assign("sign", $sign);
        $this->assign("title", $title);
        $this->assign("url", $url);
        $this->assign("authcode", $authcode);
        $view = 'site_'.$this->siteid.'/consult/'.__FUNCTION__;
        return view($view);
    }


    /*
     * 我的咨询列表 按时间倒序
     */
    public function consultList()
    {
        $sign = input('param.sign/s');
        if(empty($sign)){
            return $this->error('参数错误');
        }
        $consults = $this->consultModel->where("siteid = " . $this->siteid . " and sign = '".$sign."'")->order("consultid desc")->select();
        foreach($consults as & $v){
            $v['replys'] = $this->replyModel->where("consultid = " . $v['consultid'])->order("replyid desc")->select();
        }
        $this->assign("consults", $consults);
        $view = 'site_'.$this->siteid.'/consult/'.__FUNCTION__;
        return view($view);
    }


    /*
     * 发布咨询
     */
    public function consultCreate()
    {

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.consult/a');
            //校验参数
            if(empty($data['sign'])){
                return $this->error('程序异常，未传入标识信息');
            }
            if(empty($data['url'])){
                return $this->error('程序异常，未传入url信息');
            }
            if(empty($data['title'])){
                return $this->error('程序异常，未传入title信息');
            }
            if(empty($data['message'])){
                return $this->error('请填写咨询内容');
            }
            if(empty($data['authcode'])){
                return $this->error('请不要非法操作1');
            }
            $str = $this->siteid.$data['sign'].$data['title'].$data['url'].config('secretkey');
            if(md5($str) != $data['authcode']){
                return $this->error('请不要非法操作2');
            }
            $consultData = array(
                'siteid'   => $this->siteid,
                'userid'   => 0,
                'username' => '游客',
                'sign'     => $data['sign'],
                'title'    => decode($data['title']),
                'url'      => decode($data['url']),
                'message'  => $data['message'],
                'ip'       => get_real_ip(),
                'created'  => time()
            );
            //执行添加
            $rs = $this->consultModel->insert($consultData);
            if(!$rs){
                return $this->error("添加失败");
            }
            return $this->success('发布咨询成功，请等待回复！');
        }

    }

    /*
     * 回复
     */
    public function reply()
    {

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $consultid = input('param.consultid/d');
            $replyparent = input('param.replyparent/d');
            $replymessage = input('param.replymessage/s');
            //校验参数
            if(empty($consultid)){
                return $this->error('参数错误');
            }
            if(empty($replyparent)){
                return $this->error('参数错误');
            }
            if(empty($replymessage)){
                return $this->error('请填写咨询信息');
            }
            $replyData = array(
                'replyparent'   => $replyparent,
                'consultid'     => $consultid,
                'replyuserid'   => '',
                'replyusername' => '',
                'replymessage'  => $replymessage,
                'replytype'     => 1,
                'replytime'     => time()
            );
            //执行添加
            $rs = $this->replyModel->insert($replyData);
            if(!$rs){
                return $this->error("添加失败");
            }
            $this->replyModel->where('replyid = ' . $replyparent)->update(array('child' => 1));
            return $this->success('发布咨询成功，请等待回复！');
        }

    }


}
