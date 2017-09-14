<?php
namespace app\index\controller;
use think\Db;
class Index extends Base
{

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->assign("name", 'test');
        $view = 'site_'.$this->siteid.'/index/'.$this->site['template_index'];
        return view($view);
    }

    public function daoru(){
        die();
        $rel = array(
            100 => 15,
            101 => 16,
        );
        foreach($rel as $new_catid => $old_catid){
            $rs1 = Db::table('egg_rel')->where("term_id = " . $old_catid)->select();
            foreach($rs1 as $v1){
                $rs2 = Db::table('egg_news')->where("id = " . $v1['object_id'])->select();
                foreach($rs2 as $v2){
                    $category = model('Category')->get($new_catid);
                    $model = model('Model')->get($category['modelid']);
                    $master = array(
                        'catid'       => $category['catid'],
                        'title'       => $v2['post_title'],
                        'thumb'       => $v2['smeta'],
                        'description' => $v2['post_excerpt'],
                        'inputtime'   => strtotime($v2['post_date']),
                        'updatetime'  => strtotime($v2['post_modified'])
                    );
                    $articleid = Db::table(config('database.prefix').$model['tablename'])->insertGetId($master);
                    $sub = array(
                        'id' => $articleid,
                        'content' => $v2['post_content']
                    );
                    Db::table(config('database.prefix').$model['tablename'].'_data')->insert($sub);
                }
            }
        }
        die();
    }

}

















