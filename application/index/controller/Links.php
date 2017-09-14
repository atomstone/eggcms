<?php
namespace app\index\controller;
class Links extends Base
{

    public function __construct(){
        parent::__construct();
    }

    /*
     * 友情链接首页
     */
    public function index()
    {
        //当前栏目
        $types = model('LinksType')->where("siteid = " . $this->siteid)->order('listorder asc')->select();
        foreach($types as & $v){
            $v['links'] = model('Links')->where("typeid = " . $v['typeid'])->order("listorder asc")->select();
        }
        //返回
        $this->assign('types',$types);
        return view('site_'.$this->site['siteid'].'/links/'.__FUNCTION__);
    }


}
