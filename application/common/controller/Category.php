<?php
namespace app\common\controller;
use think\Controller;
use app\common\model;
class Category extends Controller
{
    private $categoryModel;
    private $siteModel;

    public function __construct(){
        parent::__construct();
        $this->categoryModel = model('Category');
        $this->siteModel = model('Site');
    }

    /*
     * 站点栏目联动
     */
    public function siteCategoryLinkage()
    {
        $areas = array('citylist' => array());
        $sites = $this->siteModel->order("siteid ASC")->select();
        foreach($sites as $site){
            $cats = $this->categoryModel->getTreeCats($site['siteid'], 0);
            $ctiysArray = array();
            foreach($cats as $cat){
                $ctiysArray[] = array(
                    'n' => $cat['tag'].$cat['catname'],
                    'id' => $cat['catid']
                );
            }
            $areas['citylist'][] = array(
                'p'   =>  $site['name'],
                'id'  =>  $site['siteid'],
                'c'   =>  $ctiysArray
            );
        }
        $result =  json_encode($areas);
        echo $result;
    }




}
