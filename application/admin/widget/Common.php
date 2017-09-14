<?php
namespace app\admin\widget;
use app\common\model;
class Common
{

    /*
     * 考试上边的菜单
     * $tag 0题库 1随机 2正式试卷
     */
    public function examMenu($tag = 0){
        $view = new \think\View();
        $view->assign('tag', $tag);
        return $view->fetch('widget/examMenu');
    }

    /*
     * 友情链接上边的菜单
     * $tag 0题库 1随机 2正式试卷
     */
    public function linksMenu($tag = 0){
        $view = new \think\View();
        $view->assign('tag', $tag);
        return $view->fetch('widget/linksMenu');
    }


    /*
     * 获取推荐位
     */
    public function getPositions($siteid, $catid = 0, $articleid = 0){
        $rs = model('PositionData')->where("FIND_IN_SET('".$catid."',catid)  and aid = " . $articleid)->select();
        if(empty($rs)){
            return false;
        }
        foreach($rs as $v){
            $posid[] = $v['posid'];
        }
        $posid = implode(',', $posid);
        $positions = model('Position')->where("siteid = " . $siteid . " and posid in(".$posid.")")->order("listorder asc")->select();
        if(empty($positions)){
            return false;
        }
        return $positions;
    }


}
