<?php
namespace app\index\controller;
use app\common\model;
class Comment extends Base
{

    private $commentModel;
    private $statsModel;

    public function __construct(){
        parent::__construct();
        $this->commentModel = model('Comment');
        $this->statsModel = model('CommentStats');
    }

    public function add()
    {

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.comment/a');
            //校验参数
            if(empty($data['sign'])){
                return $this->error('程序异常，未传入标识信息');
            }
            if(empty($data['message'])){
                return $this->error('请填写评论内容');
            }
            if(empty($data['authcode'])){
                return $this->error('请不要非法操作1');
            }
            $str = $data['siteid'].$data['sign'].$data['title'].config('secretkey');
            if(md5($str) != $data['authcode']){
                return $this->error('请不要非法操作2');
            }
            $commentData = array(
                'siteid'   => $data['siteid'],
                'userid'   => 0,
                'username' => '游客',
                'sign'     => $data['sign'],
                'title'    => $data['title'],
                'url'      => $data['url'],
                'message'  => $data['message'],
                'ip'       => get_real_ip(),
                'created'  => time()
            );
            //执行添加
            $rs = $this->commentModel->insert($commentData);
            if(!$rs){
                return $this->error("添加失败");
            }
            //增加统计
            $stats = $this->statsModel->where("siteid = " . $data['siteid'] . " and sign = '".$data['sign']."'")->find();
            if(!empty($stats)){
                $this->statsModel->where("statsid = " . $stats['statsid'])->setInc('total');
            }else{
                $this->statsModel->insert(array('siteid' => $data['siteid'],'sign' => $data['sign'],'total' => 1));
            }
            return $this->success('评论成功,请等待审核！');
        }

    }


}
