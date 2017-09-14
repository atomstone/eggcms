<?php
namespace app\index\widget;
use app\common\model;
class Special
{


    /*
     * 获取专题
     */
    public function getSpecials($siteid, $pars = array()){
        $where = isset($pars['where']) ? $pars['where'] . ' and siteid = ' . $siteid : 'siteid = ' . $siteid;
        $order = isset($pars['order']) ? $pars['order'] : 'elite desc, listorder asc';
        $limit = isset($pars['limit']) ? $pars['limit'] : 100;
        $rs = model('Special')->where($where)->order($order)->limit($limit)->select();
        return $rs;
    }




}
