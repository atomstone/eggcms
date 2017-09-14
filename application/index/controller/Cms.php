<?php
namespace app\index\controller;
use app\common\model\Article;

class Cms extends Base
{

    public function __construct(){
        parent::__construct();
    }

    /*
     * 路由页
     */
    public function index()
    {
        $catid = input('param.catid/d');
        if(empty($catid)){
            return $this->error("请传入栏目id");
        }
        $cat = model('Category')->where("siteid = " . $this->siteid . " and catid = " . $catid)->find();
        if(empty($cat)){
            return $this->error("未检索到栏目");
        }
        if($cat['type'] == 0){
            return $this->lists($catid);
            if($cat['child'] == 1){
                return $this->channel($catid);
            }else{
                return $this->lists($catid);
            }
        }elseif($cat['type'] == 1){
            return $this->catpage($catid);
        }elseif($cat['type'] == 2){
            header('Location: '.$cat['url']);
        }
    }


    /*
     * 栏目首页(频道页)
     */
    public function channel($catid = false)
    {
        //当前栏目
        $cat = model('Category')->where("siteid = " . $this->siteid . " and catid = " . $catid)->find();
        if(empty($cat)){
            return $this->error("未检索到栏目");
        }
        if($cat['type'] == 1){
            return $this->catpage($catid);
        }
        $setting = json_decode($cat['setting'],true);
        if(!isset($setting['category_template'])){
            return $this->error("未指定频道模板信息");
        }
        //顶级栏目
        $topcat = model('Category')->getTopCat($this->siteid, $catid);
        //返回
        $this->assign('currentcat',$cat);
        $this->assign('topcat',$topcat);
        //seo
        $this->assign('seo_title',isset($setting['meta_title']) ? $setting['meta_title'] : '');
        $this->assign('seo_keywords',isset($setting['meta_keywords']) ? $setting['meta_keywords'] : '');
        $this->assign('seo_description',isset($setting['meta_description']) ? $setting['meta_description'] : '');
        $view = $this->getView($setting['category_template']);
        return view($view);
    }

    /*
     * 栏目列表页
     */
    public function lists($catid = false){

        //获取参数
        $pagesize = input('param.pagesize/d',15);
        $cpage = input('param.cpage/d',1);
        //栏目
        $category = model('Category')->where("siteid = " . $this->siteid . " and catid = " . $catid)->find();
        if(!$category){
            return $this->error("未找到栏目");
        }
        $setting = json_decode($category['setting'],true);
        if(!isset($setting['list_template'])){
            return $this->error("未指定列表模板信息");
        }
        //模型
        $model = model('Model')->get($category['modelid']);
        if(!$model){
            return $this->error('未找到模型');
        }
        //顶级栏目
        $topcat = model('Category')->getTopCat($this->siteid, $catid);
        //实例化文章模型
        $articleModel = new Article();
        $articleModel->setTableName($model['tablename']);
        //构造条件
        $where = array();
        $where[] = "catid = " . $catid;
        $where = implode(' AND ', $where);
        //构造数据
        $rowcount = $articleModel->getCount($where);
        $articles = $articleModel->getMasterAll($where, '', 'stick desc, listorder desc, updatetime desc,id desc', ($cpage-1)*$pagesize, $pagesize);
        $page     = new \com\Mypage(['total'=> $rowcount,'perpage'=> $pagesize]);
        //构造返回
        $this->assign("page", $page);
        $this->assign('model', $model);
        $this->assign('currentcat', $category);
        $this->assign('topcat', $topcat);
        $this->assign('articles', $articles);
        //seo
        $this->assign('seo_title',isset($setting['meta_title']) ? $setting['meta_title'] : '');
        $this->assign('seo_keywords',isset($setting['meta_keywords']) ? $setting['meta_keywords'] : '');
        $this->assign('seo_description',isset($setting['meta_description']) ? $setting['meta_description'] : '');
        $view = $this->getView($setting['list_template']);
        return view($view);
    }

    /*
     * 分类单页
     */
    public function catpage($catid = false){
        $cat = model('Category')->where("siteid = " . $this->siteid . " and catid = " . $catid)->find();
        if(empty($cat)){
            return $this->error("未检索到栏目");
        }
        $setting = json_decode($cat['setting'],true);
        if(!isset($setting['page_template'])){
            return $this->error("未指定单页模板信息");
        }
        $page = model('Page')->where("catid = " . $catid)->find();
        if(!$page){
            return $this->error("未找到栏目详情");
        }
        $topcat = model('Category')->getTopCat($this->siteid, $catid);
        $this->assign('page',$page);
        $this->assign('currentcat',$cat);
        $this->assign('topcat',$topcat);
        //seo
        $this->assign('seo_title',isset($setting['meta_title']) ? $setting['meta_title'] : '');
        $this->assign('seo_keywords',isset($setting['meta_keywords']) ? $setting['meta_keywords'] : '');
        $this->assign('seo_description',isset($setting['meta_description']) ? $setting['meta_description'] : '');
        $view = $this->getView($setting['page_template']);
        return view($view);
    }

    /*
     * 文章详情页
     */
    public function detail($catid = false, $articleid = false){
        if(empty($catid) || empty($articleid)){
            return $this->error("参数错误");
        }
        //实例化文章模型
        $articleModel = new Article();

        $model = $articleModel->getModel($catid);
        $articleModel->setTableName($model['tablename']);
        //点击量+1
        $articleModel->setMasterInc('id = ' . $articleid,'hits',1);

        $article = $articleModel->getOne($this->siteid, $catid, $articleid, true);
        if(empty($article)){
            return $this->error('未找到文章');
        }

        if(!empty($article['url'])){
            header('Location: '.$article['url']);
            die();
        }
        $currentcat = $article['categoryInfo'];
        $setting = json_decode($currentcat['setting'],true);
        if(!isset($setting['show_template'])){
            return $this->error("未指定模板信息");
        }
        $topcat = model('Category')->getTopCat($this->siteid, $catid);
        //构造返回
        $this->assign('article', $article);
        $this->assign('currentcat', $currentcat);
        $this->assign('topcat',$topcat);
        //seo
        $seo_title = '';
        $seo_keywords = '';
        $seo_description = '';
        if(isset($setting['meta_title']) && !empty($setting['meta_title'])){
            $seo_title = $article['title'] . '==' . $setting['meta_title'];
        }else{
            $seo_title = $article['title'];
        }
        if(!empty($article['keywords'])){
            $seo_keywords = $article['keywords'];
        }elseif(isset($setting['meta_keywords'])){
            $seo_keywords = $setting['meta_keywords'];
        }
        if(!empty($article['description'])){
            $seo_description = $article['description'];
        }elseif(isset($setting['meta_description'])){
            $seo_description = $setting['meta_description'];
        }
        $this->assign('seo_title', $seo_title);
        $this->assign('seo_keywords', $seo_keywords);
        $this->assign('seo_description', $seo_description);

        $view = $this->getView($setting['show_template']);
        return view($view);
    }

    public function getArticle(){
        $articleid = input('param.articleid/d',0);
        $catid = input('param.catid/d',0);
        $articleModel = new Article();
        $article = $articleModel->getOne($this->siteid,$catid,$articleid,true);
        if(empty($article)){
            return $this->error("未检索到文章");
        }
        return $this->success('','',$article);
    }

    /*
     * 获取视图地址
     */
    public function getView($tmp){
        $view = 'site_'.$this->site['siteid'].'/cms/'.$tmp;
        return $view;
    }

}
