<?php
namespace app\index\controller;
use think\Controller;
use app\common\model;

class Base extends Controller
{

    public $site;
    public $siteid;

    public function __construct()
    {
        parent::__construct();

        $domain = Request()->host();
        if( count(explode('http://',$domain)) <= 1 ){
            $domain = 'http://' . $domain;
        }

        $this->site = model('Site')->where("domain = '".$domain."'")->find();
        if(empty($this->site)){
            $this->site = $site = model('Site')->get(1);
        }
        $this->siteid = $this->site['siteid'];
        $this->assign('site', $this->site);
        $this->assign('siteid', $this->site['siteid']);
    }



}
