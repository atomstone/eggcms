<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model;
class Base extends Controller
{

    public $siteid;
    public $currentsite;
    public $admininfo;
    private $allowAction; //不做校验的权限  如跳过栏目文章的校验 由内部进行校验

    public function __construct()
    {
       parent::__construct();

       //判断是否登录
       $this->admininfo = $this->getAdmininfo();
       if(!$this->admininfo){
           $this->redirect('Index/index');
       }

       //获取站点id
       $this->siteid = $this->getSiteId();

       if(!$this->siteid){
           die("cookie站点为空");
       }
       $this->currentsite = model('Site')->find($this->siteid);
       if(empty($this->currentsite)){
           die("未找到站点");
       }
       //设置允许的权限 键值需小写字母
       $this->allowAction = array(
           'content' => array('articlelist','articleadd','articleedit','articledel','articlesetorder','linkedit','pageedit','cmpselectcategorys','relationlist'),
       );

       //判断操作权限
       if($this->admininfo['roleid'] != 1){
           $rs = $this->checkPower();
           if(!$rs){
               return $this->error("您没有此操作权限！");
           }
       }

       $this->assign('siteid', $this->siteid);
       $this->assign('currentsite', $this->currentsite);
       $this->assign('admininfo', $this->admininfo);
    }


    public function getAdmininfo(){
        //$admininfo = session('admininfo','','admin');
        $admininfo = cookie('admininfo');
        if(empty($admininfo)){
            $admininfo = false;
        }
        return json_decode($admininfo, true);
    }

    public function getSiteId(){
        //$siteid = session('siteid','','admin');
        $siteid = cookie('siteid');

        if(empty($siteid)){
            $siteid = false;
        }
        return $siteid;
    }

    public function checkPower(){
        $c = strtolower(request()->controller());
        $a = strtolower(request()->action());
        //过滤允许的权限
        if(isset($this->allowAction[$c]) && in_array($a,$this->allowAction[$c])){
            return true;
        }
        $rs = model('AdminRolePriv')->where("siteid = ".$this->siteid." and roleid = ".$this->admininfo['roleid']." and c = '".$c."' and a = '".$a."'")->find();
        if(empty($rs)){
            return false;
        }
        return true;
    }

    /*
     * 错误页面
     */
    public function errorPage($msg = ''){
        $this->assign('msg', $msg);
        return view('common/errorPage');
    }


}