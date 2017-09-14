<?php
namespace app\common\model;

class Dept extends Common
{

    /*
     * 格式树形结构
     * @param string $pid
     * @param array $childs
     * @return array
     */
    public function getTreeDepts($pid = 0, $where = "deptid > 0", $select = "*", $order = "listorder ASC,deptid ASC", $tags = "---", $level = 0, & $childs = array() ){
        $ret = $this->where("`parentid` = {$pid} AND " . $where)->field($select)->order($order)->select();
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
                self::getTreeDepts($item['deptid'], $where, $select, $order, $tags, $level+1, $childs);
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
    public function getChildIds($pid, $select = "deptid", $order = "listorder ASC", & $childids = array() ){
        $ret = $this->where("`parentid` = {$pid}")->field($select)->order($order)->select();
        if(is_array($ret)){
            foreach($ret as $item){
                $childids[] = $item['deptid'];
                $this->getChildIds($item['deptid'], $select, $order, $childids);
            }
        }
        return $childids;
    }

    /*
     * 返回所有父栏目id
     * @param string $id
     * @return array
     */
    public function getParentIds($id, & $parentids = array()){
        $ret = $this->where("deptid = " . $id)->find();
        if($ret['parentid'] == 0){
            return $parentids;
        }else{
            $parentids[] = $ret['parentid'];
            $this->getParentIds($ret['parentid'], $parentids);
        }
        return $parentids;
    }


    /*
     * 返回顶级分类
     * @param string $id
     * @return array
     */
    public function getTopDept($id, & $topClass = null){
        $ret = $this->where("deptid = " . $id)->find();
        if($ret['parentid'] == 0){
            $topClass = $ret;
        }else{
            self::getTopDept($ret['parentid'], $topClass);
        }
        return $topClass;
    }



}