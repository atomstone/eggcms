<?php
namespace app\index\widget;
use app\common\model;
class Links
{


    /*
     * 获取友情链接
     */
    public function getLinks($siteid, $pars = array()){
        $where = isset($pars['where']) ? $pars['where'] . ' and siteid = ' . $siteid : 'siteid = ' . $siteid;
        $order = isset($pars['order']) ? $pars['order'] : 'listorder asc';
        $limit = isset($pars['limit']) ? $pars['limit'] : 100;
        $rs = model('Links')->where($where)->order($order)->limit($limit)->select();
        return $rs;
    }



    /*
     * 根据code友情链接
     */
    public function getLinksByCode($siteid, $code, $limit = 100){
        $type = model('LinksType')->where("siteid = ".$siteid." and code = '".$code."'")->find();
        if(empty($type)){
            return false;
        }
        $where = 'typeid = ' . $type['typeid'];
        $order = 'listorder asc';
        $rs = model('Links')->where($where)->order($order)->limit($limit)->select();
        return $rs;
    }


}
