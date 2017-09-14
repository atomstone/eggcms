<?php
namespace app\admin\controller;

class Site extends Base
{
    private $siteModel;

    public function __construct(){
        parent::__construct();
        $this->siteModel = model('Site');
    }

    /*
     * 站点列表
     */
    public function siteList()
    {
        $sites = $this->siteModel->select();
        //构造返回
        $this->assign('sites', $sites);
        return view(__FUNCTION__);
    }

    /*
     * 站点校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function siteCheck($data, $opType = false){
        if(empty($data['name'])){
            return error("请填写站点名称");
        }
        if(empty($data['domain'])){
            return error("请填写站点域名");
        }
        if(empty($data['patch_js'])){
            return error("请填写JS路径");
        }
        if(empty($data['patch_css'])){
            return error("请填写CSS路径");
        }
        if(empty($data['patch_img'])){
            return error("请填写图片路径");
        }
        if(empty($data['template_index'])){
            return error("请选择首页模板 ");
        }
        if($opType){
            if(empty($data['siteid'])){
                return error("请传入站点id");
            }
        }
        return success();
    }

    /*
     * 站点添加
     */
    public function siteAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->siteCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $siteid = $this->siteModel->insertGetId($data);
            if(!$siteid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 站点修改
     */
    public function siteEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->siteCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->siteModel->where("siteid = {$data['siteid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $siteid = input('param.siteid/d');
        if(empty($siteid)){
            return $this->errorPage("请传入站点id");
        }
        $site = $this->siteModel->get($siteid);
        if(empty($site)){
            return $this->errorPage("未检索到站点");
        }
        $this->assign('site', $site);
        return view(__FUNCTION__);
    }


}
