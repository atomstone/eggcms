<?php
namespace app\common\model;

class Category extends Common
{

    /*
     * 格式树形结构
     * @param string $pid
     * @param array $childs
     * @return array
     */
    public function getTreeCats($siteid, $pid = 0, $where = "catid > 0", $select = "*", $order = "listorder ASC,catid ASC", $tags = "---", $level = 0, & $childs = array() ){
        $ret = $this->where("siteid = ".$siteid." and `parentid` = {$pid} AND " . $where)->field($select)->order($order)->select();
        if(is_array($ret)){
            foreach($ret as $item){
                $str = '';
                for($i = 0; $i < $level; $i++){
                    $str .= $tags;
                }
                //$keys = array_keys($item);
                $item['tag'] = $str;
                $item['level'] = $level;
                $childs[] = $item;
                self::getTreeCats($siteid, $item['catid'], $where, $select, $order, $tags, $level+1, $childs);
            }
        }
        return $childs;
    }

    /*
     * 返回分类下所有子分类ID
     * @param string $pid
     * @param array $childs
     * @return array
     */
    public function getChildIds($siteid, $pid, $select = "catid", $order = "listorder ASC", & $childids = array() ){
        $ret = $this->where("siteid = ".$siteid." and `parentid` = {$pid}")->field($select)->order($order)->select();
        if(is_array($ret)){
            foreach($ret as $item){
                $childids[] = $item['catid'];
                $this->getChildIds($siteid, $item['catid'], $select, $order, $childids);
            }
        }
        return $childids;
    }

    /*
     * 返回所有父栏目id
     * @param string $id
     * @return array
     */
    public function getParentIds($siteid, $id, & $parentids = array()){
        $ret = $this->where("siteid = ".$siteid." and catid = " . $id)->find();
        if($ret['parentid'] == 0){
            return $parentids;
        }else{
            $parentids[] = $ret['parentid'];
            $this->getParentIds($siteid, $ret['parentid'], $parentids);
        }
        return $parentids;
    }


    /*
     * 返回顶级分类
     * @param string $id
     * @return array
     */
    public function getTopCat($siteid, $id, & $topClass = null){
        $ret = $this->where("siteid = " . $siteid . " and catid = " . $id)->find();
        if($ret['parentid'] == 0){
            $topClass = $ret;
        }else{
            self::getTopCat($siteid, $ret['parentid'], $topClass);
        }
        return $topClass;
    }



}