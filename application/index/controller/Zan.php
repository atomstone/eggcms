<?php
namespace app\index\controller;
use app\common\model;
class Zan extends Base
{

    private $zanModel;
    
    public function __construct(){
        parent::__construct();
        $this->zanModel = model('Zan');
    }

    public function dianzan()
    {

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.zan/a');
            //校验参数
            if(empty($data['sign'])){
                return $this->error('程序异常，未传入标识信息');
            }
            if(empty($data['authcode'])){
                return $this->error('请不要非法操作1');
            }
            $str = $data['siteid'].$data['sign'].config('secretkey');
            if(md5($str) != $data['authcode']){
                return $this->error('请不要非法操作2');
            }
            //增加统计
            $stats = $this->zanModel->where("siteid = " . $data['siteid'] . " and sign = '".$data['sign']."'")->find();
            if(!empty($stats)){
                $this->zanModel->where("zanid = " . $stats['zanid'])->setInc('total');
            }else{
                $this->zanModel->insert(array('siteid' => $data['siteid'],'sign' => $data['sign'],'total' => 1));
            }
            return $this->success('点赞成功');
        }

    }


}
