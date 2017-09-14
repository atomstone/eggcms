<?php
namespace app\index\controller;
class Special extends Base
{

    public function __construct(){
        parent::__construct();
    }

    /*
     * 专题列表页
     */
    public function specialList()
    {
        //专题
        $specials = model('Special')->where("siteid = " . $this->siteid . " and disabled = 0")->order("elite desc, listorder asc")->select();
        //返回
        $this->assign('specials',$specials);
        $view = $this->getView('specialList');
        return view($view);
    }


    /*
     * 专题详情页 专题介绍+文章列表
     */
    public function specialDetail($specialid = false)
    {
        //专题
        $special = model('Special')->where("siteid = " . $this->siteid . " and specialid = " . $specialid . " and disabled = 0")->find();
        if(empty($special)){
            return $this->error("未检索到专题");
        }
        if(!isset($special['index_template'])){
            return $this->error("未指定专题模板信息");
        }
        //专题下所有分类
        $types = model('SpecialType')->where("specialid = " . $special['specialid'])->order("listorder asc")->select();
        foreach($types as & $v){
            $v['datas'] = model('SpecialData')->where("specialid = " . $special['specialid'] . " and typeid = " . $v['typeid'])->order("stick desc, listorder desc")->select();
        }
        //返回
        $this->assign('special',$special);
        $this->assign('types',$types);
        $view = $this->getView($special['index_template']);
        return view($view);
    }

    /*
     * 文章详情页
     */
    public function specialDataDetail($dataid = false){
        if(empty($dataid)){
            return $this->error("参数错误");
        }
        $data = model('SpecialData')->getOneData($this->siteid, $dataid);
        if(empty($data)){
            return $this->error('未找到文章');
        }
        //构造返回
        $this->assign('data', $data);
        $view = $this->getView($data['special']['show_template']);
        return view($view);
    }

    /*
     * 获取视图地址
     */
    public function getView($tmp){
        $view = 'site_'.$this->site['siteid'].'/special/'.$tmp;
        return $view;
    }

}
