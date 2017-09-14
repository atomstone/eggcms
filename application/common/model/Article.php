<?php
namespace app\common\model;
use think\Db;
class Article extends Common
{

    private $masterTableName;

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    public function setTableName($tablename){
        $this->name = $tablename;
        $this->masterTableName = $tablename;
    }


    /*
     * 通过catid获取模型
     */
    public function getModel($catid){
        $catObj = new Category();
        $cat = $catObj->find($catid);
        if(empty($cat)){
            return false;
        }
        $modelObj = new Model();
        $model = $modelObj->get($cat['modelid']);
        if(empty($model)){
            return false;
        }
        return $model;
    }

    /*
     * 自增或自减一个字段的值
     */
    public function setMasterInc($where,$field,$num = 1){
        return db($this->masterTableName)->where($where)->setInc($field,$num);
    }


    /*
     * 通过catid和aid获取文章信息
     * $getSub 是否获取附表信息 true 是 false 否
     */
    public function getOne($siteid, $catid, $aid, $getSub = false){
        $catObj = new Category();
        $cat = $catObj->where("siteid = ".$siteid." and catid = " . $catid)->find();
        if(empty($cat)){
            return false;
        }
        $modelObj = new Model();
        $model = $modelObj->get($cat['modelid']);
        if(empty($model)){
            return false;
        }
        $this->setTableName($model['tablename']);
        $rs = $this->getOneById($aid);
        if(empty($rs)){
           return false;
        }
        $rs['categoryInfo'] = $cat;
        $rs['modelInfo'] = $model;
        return $rs;
    }

    public function getCount($where = ''){
        return db($this->masterTableName)->where($where)->count();
    }

    public function getMasterAll($where = '', $field = '*', $order = '', $firstRow = 0, $listRows = 1000){
        return db($this->masterTableName)->field($field)->where($where)->order($order)->limit($firstRow,$listRows)->select();
    }

    public function updateMaster($where = '', $data = array()){
        return db($this->masterTableName)->where($where)->update($data);
    }

    public function getOneMasterById($articleid){
        $rs = db($this->masterTableName)->where("id = " . $articleid)->find();
        return $rs;
    }

    public function getOneById($articleid){
        $rs = db($this->masterTableName)->where("id = " . $articleid)->find();
        if(empty($rs)){
            return false;
        }
        return $rs;
    }


    public function del($siteid, $catid, $aid){
        if(empty($siteid) || empty($catid) || empty($aid)){
            return false;
        }
        $catObj = new Category();
        $cat = $catObj->where("siteid = ".$siteid." and catid = " . $catid)->find();
        if(empty($cat)){
            return false;
        }
        $modelObj = new Model();
        $model = $modelObj->get($cat['modelid']);
        if(empty($model)){
            return false;
        }
        $this->setTableName($model['tablename']);
        $rs = Db::table(config('database.prefix').$this->masterTableName)->where("id = " . $aid)->delete();
        if(!$rs){
            return false;
        }
        return true;
    }



}