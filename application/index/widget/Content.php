<?php
namespace app\index\widget;
use app\common\model;
use app\common\model\Article;
class Content
{


    public function getTopCat($siteid, $id){
        return model('Category')->getTopCat($siteid, $id);
    }

   /*
   * 获取所有子类id
   */
   public function getChildIds($siteid, $pid){
        return model('Category')->getChildIds($siteid, $pid);
    }
    
    /*
     * 格式树形结构
     * @param string $pid
     * @param array $childs
     * @return array
     */
    public function getTreeCats($siteid, $pid = 0){
        return model('Category')->getTreeCats($siteid, $pid);
    }


    /*
     * 获取嵌套数组栏目
     */
    public function getDeepArrayCats($siteid, $parentid=0){
        $rs = model("Category")->where("siteid = " . $siteid . " and parentid = " . $parentid)->order("listorder asc")->select();
        $arr = array();
        if($rs){//如果有子类
            foreach($rs as $row){ //循环记录集
                $row['list'] = $this->getDeepArrayCats($siteid, $row['catid']); //调用函数，传入参数，继续查询下级
                $arr[] = $row; //组合数组
            }
            return $arr;
        }
    }

    /*
     * 获取栏目
     */
    public function getCats($siteid, $pars = array()){
        $where = isset($pars['where']) ? $pars['where'] . ' and siteid = ' . $siteid : 'siteid = ' . $siteid;
        $order = isset($pars['order']) ? $pars['order'] : 'listorder asc';
        $limit = isset($pars['limit']) ? $pars['limit'] : 100;
        $rs = model('Category')->where($where)->order($order)->limit($limit)->select();
        return $rs;
    }

    /*
     * 根据栏目获取文章列表
     */
    public function getArticles($siteid, $catid, $pars = array()){
        //栏目
        $category = model('Category')->where("siteid = " . $siteid . " and catid = " . $catid)->find();
        if(empty($category)){
            return false;
        }
        //模型
        $model = model('Model')->get($category['modelid']);
        if(empty($model)){
            return false;
        }
        //实例化文章模型
        $articleModel = new Article();
        $articleModel->setTableName($model['tablename']);
        //构造条件
        $where = isset($pars['where']) && !empty($where) ? $pars['where'] . ' and catid = ' . $catid : 'catid = ' . $catid;
        $order = isset($pars['order']) ? $pars['order'] : 'stick desc, listorder desc,id desc';
        $limit = isset($pars['limit']) ? $pars['limit'] : 100;
        //构造数据
        $articles = $articleModel->getMasterAll($where, '', $order, 0, $limit);
        if(empty($articles)){
            return false;
        }
        //构造返回
        return $articles;
    }

    /*
     * 获取相关文章
     */
    public function getRelations($siteid, $catid, $aid = 0, $pars = array()){
        //构造条件
        $where = " catid = " . $catid;
        if(!empty($aid)){
            $where .= " and aid = " . $aid;
        }
        if(isset($pars['where'])){
            $where .= ' and ' . $pars['where'];
        }
        //构造其他属性
        $limit = isset($pars['limit']) ? $pars['limit'] : 10;
        //构造数据
        $articles = array();
        $rs = model('relation')->where($where)->limit($limit)->select();
        $articleObj = new Article();
        foreach($rs as $v){
            $article = $articleObj->getOne($siteid, $v['rcatid'], $v['raid']);
            if(!empty($article)){
                $articles[] = $article;
            }
        }
        return $articles;
    }

    /*
     * 获取栏目
     */
    public function getPage($catid){
        $rs = model('Page')->where("catid = " . $catid)->find();
        if(empty($rs)){
            return false;
        }
        return $rs;
    }

    /*
     * 当前栏目位置导航
     * $siteid 站点id, $catid 栏目id, $isIncludeCurrent 是否包含当前栏目 true包含 false不包含
     */
    public function getSiteCatNav($siteid = false, $catid = false, $isIncludeCurrent = true){
        //查找当前栏目
        $cat = model("Category")->where("siteid = " . $siteid . " and catid = " . $catid)->find();
        if(empty($cat)){
            return false;
        }
        //查找所有父级栏目
        $navs = array();
        if($cat['arrparentid'] == 0){
            if(!$isIncludeCurrent){
                return false;
            }
            $navs[] = $cat;
        }else{
            $navs = model("Category")->where("catid in(".$cat['arrparentid'].")")->order("catid asc")->select();
            if(empty($navs)){
                return false;
            }
            if($isIncludeCurrent){
                array_push($navs, $cat);
            }
        }
        //遍历并修改url
        foreach($navs as & $v){
            if($v['type'] == 2){
                $v['target'] = "_blank";
            }else{
                $v['url'] = url('Cms/index',array('catid'=>$v['catid']));
                $v['target'] = "_self";
            }
        }
        return $navs;
    }


}
