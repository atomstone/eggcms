<?php
namespace app\admin\controller;
use think\Db;

use app\common\model\Article;
class Content extends Base
{
    private $categoryModel;
    private $modelModel;
    private $modelFieldModel;
    private $pageModel;

    public function __construct(){
        parent::__construct();
        $this->categoryModel = model('category');
        $this->modelModel = model('Model');
        $this->modelFieldModel = model('ModelField');
        $this->pageModel = model('Page');
    }

    /*
     * 模型列表
     */
    public function modelList()
    {
        $models = $this->modelModel->where("siteid = " . $this->siteid)->select();
        //构造返回
        $this->assign('models', $models);
        return view(__FUNCTION__);
    }

    /*
     * 模型校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function modelCheck($data, $opType = false){
        if(empty($data['name'])){
            return error("请填写模型名称");
        }
        if($opType){
            if(empty($data['modelid'])){
                return error("请传入模型id");
            }
        }else{
            if(empty($data['tablename'])){
                return error("请填写表名");
            }
            if(!preg_match("/^[A-Za-z0-9]+$/",$data['tablename'])){
                return error("表名必须为字母或数字");
            }
            $sql = "show tables like '".config('database.prefix'). $this->siteid . '_' .$data['tablename']."'";
            $rs = $this->modelModel->Query($sql);
            if($rs){
                return error("物理表已存在");
            }
            $rs = $this->modelModel->where("tablename = '{$data['tablename']}'")->find();
            if($rs){
                return error("表已存在");
            }
        }
        return success();
    }

    /*
     * 模型添加
     */
    public function modelAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $data['siteid'] = $this->siteid;
            //校验参数
            $rs = $this->modelCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data['tablename'] = $this->siteid . '_' .$data['tablename'];
            //执行添加
            $modelid = $this->modelModel->insertGetId($data);
            if(!$modelid){
                return $this->error("添加失败");
            }
            //生成物理表
            $model_sql = file_get_contents(config('path_datatemplate').'model.sql');
            $tablepre = config('database.prefix');
            $tablename = $data['tablename'];
            $model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
            $model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
            $model_sql = str_replace('$modelid',$modelid,$model_sql);
            $model_sql = str_replace('$siteid',$this->siteid,$model_sql);
            //拆分成单条sql执行(框架不支持多SQL执行)
            $sqlArr = explode('[-tag-]', $model_sql);
            foreach($sqlArr as $sql){
                $this->modelModel->execute($sql);
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 模型修改
     */
    public function modelEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->modelCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //禁止修改表名
            if(isset($data['tablename'])){
                unset($data['tablename']);
            }
            //执行修改
            $rs = $this->modelModel->where("modelid = {$data['modelid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $modelid = input('param.modelid/d',0);
        if(empty($modelid)){
            return $this->errorPage("请传入模型id");
        }
        $model = $this->modelModel->get($modelid);
        if(empty($model)){
            return $this->errorPage("未检索到模型");
        }
        $this->assign('model', $model);
        return view(__FUNCTION__);
    }

    /*
     * 字段列表
     */
    public function fieldList()
    {
        $modelid = input('param.modelid/d',0);
        if(empty($modelid)){
            return $this->errorPage("请传入模型id");
        }
        $model = $this->modelModel->get($modelid);
        if(empty($model)){
            return $this->errorPage("未检索到模型");
        }
        $fields = $this->modelFieldModel->where("modelid = " . $modelid)->order("disabled ASC, listorder ASC")->select();
        //构造返回
        $this->assign('model', $model);
        $this->assign('fields', $fields);
        return view(__FUNCTION__);
    }


    /*
     * 字段验证
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function fieldCheck($data, $opType = false){
        $model = $this->modelModel->get($data['modelid']);
        if(!$model){
            return error("未检索到模型");
        }
        $sql = "show tables like '".config('database.prefix').$model['tablename']."'";
        $rs = $this->modelModel->Query($sql);
        if(empty($rs)){
            return error("未检索到".$model['tablename']."表");
        }
        if(empty($data['formtype'])){
            return error("请选择字段类型");
        }
        if(empty($data['field'])){
            return error("请填写字段名");
        }
        if(!preg_match("/^[a-z\s]+$/",$data['field'])){
            return error("字段名必须为小写字母");
        }
        if(empty($data['name'])){
            return error("请填写字段别名");
        }
        //if(!empty($data['setting'])){
            if($data['formtype'] == 'box'){
                if(empty($data['setting']['boxtype'])){
                    return error("请选择选项类型");
                }
                if(empty($data['setting']['options'])){
                    return error("请填写选项");
                }
                $arr = explode(',', $data['setting']['options']);
                $options = Array();
                foreach($arr as $key=>$val){
                    $options[$key] = $val;
                }
                $setting = array(
                    'boxtype' => $data['setting']['boxtype'],
                    'options' => $options
                );
                $data['setting'] = json_encode($setting);
            }elseif($data['formtype'] == 'dataitem'){
                if(empty($data['setting'])){
                    return error("请填写数据组设置");
                }
                $data['setting'] = json_encode(explode(';', $data['setting']));
            }
        //}
        if(!$opType){
            $rs = $this->modelFieldModel->where("modelid = {$model['modelid']} and field = '{$data['field']}'")->find();
            if($rs){
                return error("字段名已存在");
            }
        }else{
            if(empty($data['fieldid'])){
                return error("请传入字段id");
            }
        }
        return success(array('model' => $model,'info' => $data));
    }

    /*
     * 修改表结构 增加字段
     */
    private function fieldStructureAdd($tablename, $field_type, $field){
        switch($field_type) {
            case 'text':
                $sql = "ALTER TABLE `$tablename` ADD `$field` VARCHAR( 255 ) DEFAULT ''";
                break;
            case 'textarea':
                $sql = "ALTER TABLE `$tablename` ADD `$field` TEXT";
                break;
            case 'editor':
                $sql = "ALTER TABLE `$tablename` ADD `$field` TEXT";
                break;
            case 'box':
                $sql = "ALTER TABLE `$tablename` ADD `$field` VARCHAR( 255 ) DEFAULT ''";
                break;
            case 'image':
                $sql = "ALTER TABLE `$tablename` ADD `$field` VARCHAR( 512 ) DEFAULT ''";
                break;
            case 'images':
                $sql = "ALTER TABLE `$tablename` ADD `$field` TEXT";
                break;
            case 'dataitem':
                $sql = "ALTER TABLE `$tablename` ADD `$field` TEXT";
                break;
            case 'video':
                $sql = "ALTER TABLE `$tablename` ADD `$field` VARCHAR( 512 ) DEFAULT ''";
                break;
            case 'number':
                $sql = "ALTER TABLE `$tablename` ADD `$field` INT DEFAULT 0";
                break;
            case 'date':
                $sql = "ALTER TABLE `egg_model_field` ADD `$field` date NULL";
                break;
            case 'datetime':
                $sql = "ALTER TABLE `egg_model_field` ADD `$field`  datetime NULL";
                break;
            case 'downonefile':
                    $sql = "ALTER TABLE `$tablename` ADD `$field` TEXT";
                    break;
            case 'downfiles':
                 $sql = "ALTER TABLE `$tablename` ADD `$field` TEXT";
                 break;
        }
        $this->modelFieldModel->execute($sql);
    }

    /*
     * 字段添加
     */
    public function fieldAdd(){
        if (request()->isPost() && request()->isAjax()){
            $info = input('post.info/a');
            //$info['setting'] = input('post.setting/a');
            $rs = $this->fieldCheck($info);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $model = $rs['data']['model'];
            //添加字段表
            $info = $rs['data']['info'];
            $info['siteid'] = $this->siteid;
            $rs = $this->modelFieldModel->insert($info);
            if(!$rs){
                return $this->error("添加数据失败");
            }
            //设置物理表结构 增加字段
            $tablename = config('database.prefix').$model['tablename'];
            $this->fieldStructureAdd($tablename, $info['formtype'], $info['field']);
            return $this->success("添加成功");
        }

        $modelid = input('param.modelid/d',0);
        if(empty($modelid)){
            return $this->errorPage("请传入模型id");
        }
        $model = $this->modelModel->get($modelid);
        if(empty($model)){
            return $this->errorPage("未检索到模型");
        }
        $this->assign('model', $model);
        return view(__FUNCTION__);
    }

    /*
     * 字段修改
     */
    public function fieldEdit(){
        if (request()->isPost() && request()->isAjax()){
            $info = input('post.info/a');
            //$info['setting'] = input('post.setting/a');
            $rs = $this->fieldCheck($info, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //修改字段
            $info = $rs['data']['info'];
            $rs = $this->modelFieldModel->where("fieldid = {$info['fieldid']}")->update($info);
            if(!$rs){
                return $this->error("修改数据失败");
            }
            return $this->success("修改成功");
        }

        $fieldid = input('param.fieldid/d',0);
        if(empty($fieldid)){
            return $this->errorPage("请传入字段id");
        }
        $field = $this->modelFieldModel->get($fieldid);
        if(empty($field)){
            return $this->errorPage("未检索到字段");
        }
        $model = $this->modelModel->get($field['modelid']);
        if(empty($model)){
            return $this->errorPage("未检索到模型");
        }
        $this->assign('model', $model);
        $this->assign('field', $field);
        return view(__FUNCTION__);
    }


    /*
     * 字段修改启用状态
     */
    public function fieldChangeDisabled(){
        $fieldid = input('param.fieldid/d',0);
        if(empty($fieldid)){
            return $this->error("请传入字段id");
        }
        $field = $this->modelFieldModel->get($fieldid);
        if(empty($field)){
            return $this->error("未检索到字段");
        }
        if($field['disabled'] == 1){
            $forbid_disabled_fields = config('field.forbid_disabled_fields');
            if(in_array($field['field'], $forbid_disabled_fields) && $field['disabled'] == 0){
                return $this->error("此字段禁止操作");
            }
        }
        $data = array(
            'disabled' => $field['disabled'] == 1 ? 0 : 1
        );
        $rs = $this->modelFieldModel->where("fieldid = {$field['fieldid']}")->update($data);
        if(!$rs){
            return $this->error("修改启用状态失败");
        }
        return $this->success("修改启用状态成功");
    }


    /*
     * 字段修改列表显示状态
     */
    public function fieldChangeIslist(){
        $fieldid = input('param.fieldid/d',0);
        if(empty($fieldid)){
            return $this->error("请传入字段id");
        }
        $field = $this->modelFieldModel->get($fieldid);
        if(empty($field)){
            return $this->error("未检索到字段");
        }
        if($field['islist'] == 1){
            $nustshow_islist_fields = config('field.nustshow_islist_fields');
            if(in_array($field['field'], $nustshow_islist_fields) && $field['disabled'] == 0){
                return $this->error("此字段禁止操作");
            }
        }
        $data = array(
            'islist' => $field['islist'] == 1 ? 0 : 1
        );
        $rs = $this->modelFieldModel->where("fieldid = {$field['fieldid']}")->update($data);
        if(!$rs){
            return $this->error("修改列表显示状态失败");
        }
        return $this->success("修改列表显示状态成功");
    }

    /*
     * 字段修改是否参与检索状态
     */
    public function fieldChangeIssearch(){
        $fieldid = input('param.fieldid/d',0);
        if(empty($fieldid)){
            return $this->error("请传入字段id");
        }
        $field = $this->modelFieldModel->get($fieldid);
        if(empty($field)){
            return $this->error("未检索到字段");
        }
        $data = array(
            'issearch' => $field['issearch'] == 1 ? 0 : 1
        );
        $rs = $this->modelFieldModel->where("fieldid = {$field['fieldid']}")->update($data);
        if(!$rs){
            return $this->error("修改搜索状态失败");
        }
        return $this->success("修改搜索状态成功");
    }

    /*
     * 字段排序
     */
    public function fieldSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的字段');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->modelFieldModel->where("fieldid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }

    /*
     * 栏目列表
     */
    public function categoryList()
    {
        $categorys = $this->categoryModel->getTreeCats($this->siteid, 0);
        foreach($categorys as & $v){
            if($v['type'] == 0){
                $v['model'] = $this->modelModel->get($v['modelid']);
            }
        }
        $this->assign('categorys', $categorys);
        return view(__FUNCTION__);
    }

    /*
     * 栏目校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function categoryCheck($data, $opType = false){
        if($opType){
            if(empty($data['catid'])){
                return error("请传入栏目id");
            }
            if($data['catid'] == $data['parentid']){
                return error("归属级别错误,上级不能是自己");
            }
            $childids = $this->categoryModel->getChildIds($this->siteid, $data['catid']);
            if(in_array($data['parentid'], $childids)){
                return error("归属级别错误，不能是自己的下级");
            }
        }
        if(empty($data['catname'])){
            return error("请填写栏目名称");
        }
        if($data['type'] == 0){
            if(empty($data['modelid'])){
                return error("请选择模型");
            }
            if(empty($data['setting'])){
                return error("请选择模板");
            }
            $setting = json_decode($data['setting'],true);
            if(empty($setting['category_template']) || empty($setting['list_template']) || empty($setting['show_template'])){
                return error("请选择模板");
            }
        }elseif($data['type'] == 1){
            if(empty($data['setting'])){
                return error("请选择模板");
            }
            $setting = json_decode($data['setting'],true);
            if(empty($setting['page_template'])){
                return error("请选择模板");
            }
        }elseif($data['type'] == 2){
            if(empty($data['url'])){
                return error("请填写链接地址");
            }
        }
        return success();
    }

    /*
     * 栏目添加
     */
    public function categoryAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $setting = input('post.setting/a');
            $data['siteid'] = $this->siteid;
            if(!empty($setting)){
                $data['setting'] = json_encode($setting);
            }
            //校验参数
            $rs = $this->categoryCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $catid = $this->categoryModel->insertGetId($data);
            if(!$catid){
                return $this->error("添加失败");
            }
            //如果是单页 则添加单页表
            if($data['type'] == 1){
                $pageData = array(
                    'catid'       => $catid,
                    'title'       => $data['catname'],
                    'description' => $data['description']
                );
                $this->pageModel->insert($pageData);
            }
            $this->setChildAndParentIds();
            return $this->success('添加成功');
        }
        $type = input('param.type/d', 0);
        if(!isset(config("category.type")[$type])){
            return $this->errorPage('栏目类型错误');
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }


    /*
     * 栏目修改
     */
    public function categoryEdit(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $setting = input('post.setting/a');
            if(!empty($setting)){
                $data['setting'] = json_encode($setting);
            }
            //校验参数
            $rs = $this->categoryCheck($data,true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->categoryModel->where("catid = {$data['catid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            $this->setChildAndParentIds();
            return $this->success('修改成功');
        }

        $catid = input('param.catid/d',0);
        if(empty($catid)){
            return $this->errorPage("请传入栏目id");
        }
        $category = $this->categoryModel->get($catid);
        if(empty($category)){
            return $this->errorPage("未检索到栏目");
        }
        if(!empty($category['setting'])){
            $category['setting'] = json_decode($category['setting'],true);
        }
        $this->assign('category', $category);
        return view(__FUNCTION__);
    }

    /*
     * 栏目删除
     */
    public function categoryDel(){
        $catid = input('param.catid/d',0);
        if(empty($catid)){
            return $this->error("请传入栏目id");
        }
        $rs = $this->categoryModel->where("parentid = " . $catid)->find();
        if($rs){
            return $this->error("此栏目下有子栏目，请先删除子栏目！");
        }
        $rs = $this->categoryModel->where("catid = " . $catid)->delete();
        if($rs){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }

    /*
     * 设置每个栏目的 所有父栏目ids、所有子栏目ids 字段
     */
    private function setChildAndParentIds(){
        $cats = $this->categoryModel->where("siteid = " . $this->siteid)->select();
        foreach($cats as $v){
            $arrchildid  = $this->categoryModel->getChildIds($this->siteid, $v['catid']);
            $arrparentid = $this->categoryModel->getParentIds($this->siteid, $v['catid']);
            $data = array(
                'arrchildid'  => implode(',', $arrchildid),
                'arrparentid' => implode(',', $arrparentid),
                'child'       => !empty($arrchildid) ? 1 : 0
            );
            $this->categoryModel->where("catid = " . $v['catid'])->update($data);
        }


    }

    /*
     * 栏目排序
     */
    public function categorySetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的菜单');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->categoryModel->where("catid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 内容管理首页
     */
    public function contentIndex(){
        $categorys = $this->categoryModel->where("siteid = " . $this->siteid)->order("listorder ASC")->select();
        $this->assign('categorys', $categorys);
        return view(__FUNCTION__);
    }

    /*
     * 单页修改
     */
    public function pageEdit(){
        $catid = input('param.catid/d',0);
        $category = $this->categoryModel->where("siteid = " . $this->siteid . " and catid = " . $catid)->find();
        if(empty($category)){
            return $this->error('未检索到栏目');
        }
        //检测栏目权限
        if(!$this->checkCategoryPriv($catid, 'pageEdit')){
            return $this->error("您没有此栏目的单页编辑权限");
        }
        if (request()->isPost() && request()->isAjax()){
            $data = input('post.data/a');
            if(empty($data['title'])){
                return $this->error("请填写标题");
            }
            if(empty($data['content'])){
                return $this->error("请填写内容");
            }
            $data['updatetime'] = time();
            $page = $this->pageModel->where("catid = " . $catid)->find();
            if($page){
                $rs = $this->pageModel->where("catid = {$catid}")->update($data);
                if(!$rs){
                    return $this->error("修改失败");
                }
                return $this->success("修改成功");
            }else{
                $data['catid'] = $catid;
                $rs = $this->pageModel->insert($data);
                if(!$rs){
                    return $this->error("添加失败");
                }
                return $this->success("添加成功");
            }
        }

        $page = $this->pageModel->where("catid = " . $catid)->find();
        $page['catid'] = $catid;
        $this->assign('category', $category);
        $this->assign('page', $page);
        return view(__FUNCTION__);
    }

    /*
     * 超链接修改
     */
    public function linkEdit(){
        $catid = input('param.catid/d',0);
        //检测栏目权限
        if(!$this->checkCategoryPriv($catid, 'linkEdit')){
            return $this->error("您没有此栏目的链接编辑权限");
        }
        $category = $this->categoryModel->get($catid);
        if(empty($category)){
            $this->error("未检索到栏目");
        }

        if (request()->isPost() && request()->isAjax()){
            $data = input('post.data/a');
            if(empty($data['url'])){
                return $this->error("请填写链接地址");
            }
            $rs = $this->categoryModel->where("catid = {$catid}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success("修改成功");
        }

        $this->assign('category', $category);
        return view(__FUNCTION__);
    }


    /*
     * 校验栏目权限
     */
    private function checkCategoryPriv($catid = 0, $action){
        if($this->admininfo['roleid'] == 1){
            return true;
        }
        $action = strtolower($action);
        $priv = model('CategoryPriv')->where("siteid = ".$this->siteid." and roleid = ".$this->admininfo['roleid']." and catid = ".$catid." and action = '".$action."'")->find();
        if(empty($priv)){
            return false;
        }
        return true;
    }

    /*
     * 文章列表
     */
    public function articleList(){
        //获取参数
        $catid = input('param.catid/d',0);
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        //检测栏目权限
        if(!$this->checkCategoryPriv($catid, 'articleList')){
            return $this->errorPage("您没有此栏目的文章列表权限");
        }
        //相关校验
        $category = $this->categoryModel->get($catid);
        if(!$category){
            return $this->errorPage("未找到栏目");
        }
        $model = $this->modelModel->get($category['modelid']);
        if(!$model){
            return $this->errorPage('未找到模型');
        }
        //实例化文章模型
        $articleModel = new Article();
        $articleModel->setTableName($model['tablename']);
        //列表要显示的字段
        $rs = $this->modelFieldModel->where("modelid = ".$model['modelid']." and islist = 1")->order('listorder asc')->select();
        $listFiels = array();
        foreach($rs as $v){
            $listFiels[$v['field']] = $v;
        }
        if(!isset($listFiels['title'])){
            return $this->errorPage("请先设置标题字段在列表页显示");
        }
        //构造条件
        $where = array();
        $where[] = "catid = " . $catid;
        if(!empty($search)){
            foreach($search as $k => & $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('title'))){
                        $where[] = "$k like '%{$v}%'";
                    }else if($k == 'posid'){ //如果是推荐位
                        $aids = [0];
                        $rs = model('PositionData')->where("posid = ".$v." and catid = " . $catid)->select();
                        if($rs){
                            foreach($rs as $_v){
                                $aids[] = $_v['aid'];
                            }
                        }
                        $where[] = "id in (".implode(',', $aids).")";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $articleModel->getCount($where);
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $articles = $articleModel->getMasterAll($where, '', 'stick desc,listorder desc,updatetime desc,id desc', $page->firstRow, $page->listRows);

        //构造返回
        $this->assign("page", $page);
        $this->assign('model', $model);
        $this->assign('category', $category);
        $this->assign('articles', $articles);
        $this->assign('search', $search);
        $this->assign('listFiels', $listFiels);
        return view(__FUNCTION__);
    }


    /*
     * 文章校验
     */
    private function articleCheck($master, $fields, $opType = false){
        $newFields = array();
        foreach($fields as $v){
            $newFields[$v['field']] = $v;
        }
        $data = $master;
        foreach($data as $k => $v){

            if(is_array($v)){
                $v = json_encode($v);
                if(isset($master[$k])){
                    $master[$k] = $v;
                }
            }

            $vallen = mb_strlen($v,'utf8');
            if(isset($newFields[$k])){
                if($newFields[$k]['minlength'] > 0){
                    if($vallen < $newFields[$k]['minlength']){
                        return error($newFields[$k]['name'].'过短，需大于'.$newFields[$k]['minlength']);
                    }
                }
                if($newFields[$k]['maxlength'] > 0){
                    if($vallen > $newFields[$k]['maxlength']){
                        return error($newFields[$k]['name'].'过长,需小于'.$newFields[$k]['maxlength']);
                    }
                }
            }

            //正则校验
            if(!empty($newFields[$k]['pattern'])){
                if(!preg_match($newFields[$k]['pattern'] , $v)){
                    return error($newFields[$k]['name']."未通过正则校验");
                }
            }

            if($newFields[$k]['formtype'] == 'datetime'){
                if(isset($master[$k])){
                    $master[$k] = strtotime($v);
                }
            }
        }
        if(!isset($master['inputtime'])){
            $master['inputtime'] = time();
        }
        if(!isset($master['updatetime'])){
            $master['updatetime'] = time();
        }
        return success(array('master' => $master));
    }


    /*
     * 文章添加
     */
    public function articleAdd(){
        $catid = input('param.catid/d',0);
        //检测栏目权限
        if(!$this->checkCategoryPriv($catid, 'articleAdd')){
            return $this->error("您没有此栏目的文章添加权限");
        }
        $category = $this->categoryModel->get($catid);
        $model = $this->modelModel->get($category['modelid']);
        $fields = $this->modelFieldModel->where("modelid = ".$model['modelid']." AND disabled = 0")->order("listorder asc")->select();
        if(empty($fields)){
            return $this->error("未检索到字段组");
        }
        if (request()->isPost() && request()->isAjax()){
            $master = input('post.master/a');
            $sub    = input('post.sub/a');
            $positions = input('post.positions/a');
            //$master['posids'] = !empty($positions) ? 1 : 0;
            $master['catid'] = $catid;
            //校验参数
            $rs = $this->articleCheck($master,$fields);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $master = $rs['data']['master'];
            $articleid = Db::table(config('database.prefix').$model['tablename'])->insertGetId($master);
            if(!$articleid){
                return $this->error("添加文章主表失败");
            }
            //发布到其他栏目
            $otherCatIds = input('post.otherCatIds/a');
            if(!empty($otherCatIds)){
                $this->addArticleToOtherCategory($otherCatIds,$master,$sub);
            }
            //设置推荐位
            if(!empty($positions)){
                $this->setPosition($positions, $articleid, $master);
            }
            //设置相关文章
            $raids = input('post.raids/a',array());
            $this->setRelation($catid,$articleid,$raids);
            return $this->success("添加成功");
        }

        $this->assign('category', $category);
        $this->assign('model', $model);
        $this->assign('fields', $fields);
        return view(__FUNCTION__);
    }

    /*
     * 文章修改
     */
    public function articleEdit(){

        $articleid = input('param.articleid/d',0);
        $catid = input('param.catid/d',0);
        //检测栏目权限
        if(!$this->checkCategoryPriv($catid, 'articleEdit')){
            return $this->error("您没有此栏目的文章编辑权限");
        }
        $articleModel = new Article();
        $article = $articleModel->getOne($this->siteid,$catid,$articleid,true);
        if(empty($article)){
            return $this->error("未检索到文章");
        }
        $category = $article['categoryInfo'];
        $model = $article['modelInfo'];
        $fields = $this->modelFieldModel->where("modelid = ".$model['modelid']." AND disabled = 0")->order("listorder asc")->select();
        if(empty($fields)){
            return $this->error("未检索到字段组");
        }
        foreach($fields as & $v){
            $v['def_val'] = isset($article[$v['field']]) ? $article[$v['field']] : '';
        }

        if (request()->isPost() && request()->isAjax()){
            $master = input('post.master/a');
            $sub    = input('post.sub/a');
            $positions = input('post.positions/a', array());
            //$master['posids'] = !empty($positions) ? 1 : 0;
            //校验参数
            $rs = $this->articleCheck($master,$fields,true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $master = $rs['data']['master'];
            $rs = Db::table(config('database.prefix').$model['tablename'])->where("id = " . $articleid)->update($master);
            if($rs === false){
                return $this->error("修改文章主表失败");
            }
            //设置推荐位
            $master['catid'] = $catid;
            $this->setPosition($positions, $articleid, $master, true);
            //设置相关文章
            $raids = input('post.raids/a',array());
            $this->setRelation($catid,$articleid,$raids);
            return $this->success("修改成功");
        }
        $this->assign('article', $article);
        $this->assign('category', $category);
        $this->assign('model', $model);
        $this->assign('fields', $fields);
        return view(__FUNCTION__);
    }

    /*
     * 删除文章
     */
    public function articleDel(){
        if (request()->isPost() && request()->isAjax()){
            $catid = input('post.catid/d',0);
            //检测栏目权限
            if(!$this->checkCategoryPriv($catid, 'articleDel')){
                return $this->error("您没有此栏目的文章删除权限");
            }
            $aid   = input('post.aid/d',0);
            $articleModel = new Article();
            $rs = $articleModel->del($this->siteid, $catid, $aid);
            if(!$rs){
                return $this->error('删除失败');
            }else{
                $this->delPosition($catid, $aid);
                return $this->success('删除成功');
            }
        }
    }


    /*
     * 文章排序
     */
    public function articleSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            $catid = input('param.catid/d',0);
            //检测栏目权限
            if(!$this->checkCategoryPriv($catid, 'articleSetOrder')){
                return $this->error("您没有此栏目的文批量排序权限");
            }
            $data = input('post.setorder/a');
            if(empty($data)){
                return $this->error('没有要排序的数据');
            }
            $category = $this->categoryModel->get($catid);
            if(empty($category)){
                return $this->errorPage("未检索到文章栏目");
            }
            $model = $this->modelModel->get($category['modelid']);
            if(empty($model)){
                return $this->errorPage("未检索到模型");
            }
            $articleModel = new Article();
            $articleModel->setTableName($model['tablename']);
            //执行排序
            foreach($data as $k => $v){
                $rs = $articleModel->updateMaster("id = " . $k, array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 删除推荐位
     */
    private function delPosition($catid, $articleid){
        $data = model('PositionData')->where("catid = ".$catid." and aid = " . $articleid)->find();
        if(!empty($data)){
            model('PositionData')->where("dataid = " . $data['dataid'])->delete();
        }
        return;
    }

    private function addArticleToOtherCategory($otherCatIds,$master,$sub){
        $new_master = array(
            'title'      => isset($master['title']) ? $master['title'] : '',
            'thumb'      => isset($master['thumb']) ? $master['thumb'] : '',
            'keywords'   => isset($master['keywords']) ? $master['keywords'] : '',
            'url'        => isset($master['url']) ? $master['url'] : '',
            'content'    => isset($master['content']) ? $master['content'] : '',
            'inputtime'  => isset($master['inputtime']) ? $master['inputtime'] : time(),
            'updatetime' => isset($master['updatetime']) ? $master['updatetime'] : time()
        );
        foreach($otherCatIds as $catid){
            $category = $this->categoryModel->get($catid);
            if($category){
                $model = $this->modelModel->get($category['modelid']);
                if($model){
                    $new_master['catid'] = $catid;
                    $articleid = db($model['tablename'])->insertGetId($new_master);
                }
            }
        }
    }

    /*
     * 设置推荐位
     * $opType false:添加 true:修改
     */
    private function setPosition(array $positions, $articleid, $master, $opType = false){
        $master['title']       = isset($master['title']) ? $master['title'] : '';
        $master['description'] = isset($master['description']) ? $master['description'] : '';
        $master['thumb']       = isset($master['thumb']) ? $master['thumb'] : '';
        $master['updatetime']  = isset($master['updatetime']) ? $master['updatetime'] : '';
        $data = array(
            'title'       => $master['title'],
            'description' => $master['description'],
            'thumb'       => $master['thumb'],
            'inputtime'   => $master['updatetime']
        );
        if($opType){
            //查询已有的推荐位
            $positiondatas = model('PositionData')->where("catid = ".$master['catid']." and aid = " . $articleid)->select();
            //无操作
            if(empty($positions) && empty($positiondatas)){
                return;
            }
            //已有的都删了
            if(empty($positions) && !empty($positiondatas)){
                foreach($positiondatas as $v){
                    model('PositionData')->where("dataid = " . $v['dataid'])->delete();
                }
                return;
            }
            //都添加
            if(!empty($positions) && empty($positiondatas)){
                foreach($positions as $k => $v){
                    //查询最大的排序号
                    $listorder = model('PositionData')->where("posid = " . $v)->max('listorder');
                    model('PositionData')->insert(array('posid'=>$v,'aid'=>$articleid,'catid'=>$master['catid'],'thumb'=>$master['thumb'],'data'=>json_encode($data),'listorder'=>$listorder+1));
                }
            }
            //存在已有里面的修改 不存在已有里面的删除  差集添加
            if(!empty($positions) && !empty($positiondatas)){
                $posided = array();
                foreach($positiondatas as $v){
                    $posided[] = $v['posid'];
                    if(in_array($v['posid'], $positions)){ //修改
                        model('PositionData')->where("dataid = " . $v['dataid'])->update(array('thumb'=>$master['thumb'],'data'=>json_encode($data)));
                    }else{ //删除
                        model('PositionData')->where("dataid = " . $v['dataid'])->delete();
                    }
                }
                //差集添加
                $newa = array_diff($positions,$posided);
                foreach($newa as $k => $v){
                    //查询最大的排序号
                    $listorder = model('PositionData')->where("posid = " . $v)->max('listorder');
                    model('PositionData')->insert(array('posid'=>$v,'aid'=>$articleid,'catid'=>$master['catid'],'thumb'=>$master['thumb'],'data'=>json_encode($data),'listorder'=>$listorder+1));
                }
            }
        }else{
            foreach($positions as $k => $v){
                //查询最大的排序号
                $listorder = model('PositionData')->where("posid = " . $v)->max('listorder');
                model('PositionData')->insert(array('posid'=>$v,'aid'=>$articleid,'catid'=>$master['catid'],'thumb'=>$master['thumb'],'data'=>json_encode($data),'listorder'=>$listorder+1));
            }
        }
        //超过最大保存条数的都删除
        $positions = model('Position')->select();
        foreach($positions as $position){
            $datanum = model('PositionData')->where("posid = " . $position['posid'])->count();
            if($datanum > $position['maxnum']){
                $limit = $datanum - $position['maxnum'];
                model('PositionData')->where("posid = " . $position['posid'])->order("listorder asc")->limit($limit)->delete();
            }
        }
        return;
    }

    /*
     * 弹框 选择栏目
     */
    public function cmpSelectCategorys(){
        $sites = model('Site')->select();
        foreach($sites as & $site){
            $categorys = $this->categoryModel->where("siteid = " . $site['siteid'])->order("listorder ASC")->select();
            $zNodes = '';
            foreach($categorys as $k => $v){
                $iconClose = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/10.png";
                $iconOpen = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/11.png";
                if($v['type'] == 0){
                    $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/12.png";
                    $url =   url('content/articleList', array('catid'=>$v['catid']));
                }elseif($v['type'] == 1){
                    $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/2.png";
                    $url =   url('content/pageEdit', array('catid'=>$v['catid']));
                }elseif($v['type'] == 2){
                    $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/3.png";
                    $url =   url('content/linkEdit', array('catid'=>$v['catid']));
                }
                $zNodes .= '{id:'.$v['catid'].', pId:'.$v['parentid'].',type:'.$v['type'].', name:"'.$v['catname'].'", tourl:"'.$url.'", open:true, icon:"'.$ico.'", iconClose:"'.$iconClose.'", iconOpen:"'.$iconOpen.'"},';
            }
            $site['zNodes'] = '[' . $zNodes . ']';
        }
        //构造返回
        $this->assign('sites', $sites);
        return view(__FUNCTION__);
    }


    /*
     * 相关文章列表
     */
    public function relationList(){
        //获取参数
        $catid = input('param.catid/d',0);
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        //相关校验
        $category = $this->categoryModel->get($catid);
        if(!$category){
            die('未检索到栏目');
            $this->assign('catid', $catid);
            return view(__FUNCTION__);
        }
        $model = $this->modelModel->get($category['modelid']);
        if(!$model){
            die('未检索到模型');
            $this->assign('catid', $catid);
            return view(__FUNCTION__);
        }
        //实例化文章模型
        $articleModel = new Article();
        $articleModel->setTableName($model['tablename']);
        //构造条件
        $where = array();
        $where[] = "catid = " . $catid;
        if(!empty($search)){
            foreach($search as $k => $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('title'))){
                        $where[] = "$k like '%{$v}%'";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $articleModel->getCount($where);
        $page = new \com\Ajaxpage($count, $limitRows, "fy");
        $articles = $articleModel->getMasterAll($where, '', 'stick desc,listorder desc,id desc', $page->firstRow, $page->listRows);

        //构造返回
        $this->assign("page", $page);
        $this->assign('model', $model);
        $this->assign('catid', $catid);
        $this->assign('category', $category);
        $this->assign('articles', $articles);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

    /*
     * 设置相关文章
     */
    private function setRelation($catid, $articleid, $raids = array()){
        //删除已存在的
        model('Relation')->where("catid = ".$catid." and aid = " . $articleid)->delete();
        //添加新的相关文章
        if(!empty($raids)){
            //去重
            $raids = array_unique($raids);
            //添加
            foreach($raids as $v){
                list($rcatid,$raid) = explode('|', $v);
                $data = array(
                    'catid'  => $catid,
                    'aid'    => $articleid,
                    'rcatid' => $rcatid,
                    'raid'   => $raid
                );
                model('Relation')->insert($data);
            }
        }
    }

    /*
     * 全文检索
     */
    public function fullsearchList(){
        //获取参数
        $keyword = input('param.keyword', '');
        $scope   = input('param.scope', 'title');

        $keyword = trim($keyword);
        //构造数据
        //构造条件
        if($scope == 'all'){
            $where = "title like '%".$keyword."%' or description like '%".$keyword."%' or content like '%".$keyword."%'";
        }else{
            $where = $scope . " like '%".$keyword."%'";
        }

        $datas = array();
        if(!empty($keyword)){
            //所有模型
            $models = $this->modelModel->select();
            foreach($models as $v){
                //实例化文章模型
                $articleModel = new Article();
                $articleModel->setTableName($v['tablename']);
                $articles = $articleModel->getMasterAll($where, 'id,catid,thumb,title', 'stick desc,listorder desc,id desc');
                foreach($articles as & $a){
                    $cat = $this->categoryModel->find($a['catid']);
                    $site = model('site')->find($v['siteid']);
                    $a['catname'] = isset($cat['catname']) ? $cat['catname'] : '';
                    $a['sitename'] = isset($site['name']) ? $site['name'] : '';
                    $a['siteid'] = isset($site['siteid']) ? $site['siteid'] : '';
                    $a['title'] = str_replace($keyword, '<font color=red>'.$keyword.'</font>', $a['title']);
                    $datas[] = $a;
                }
            }

        }
        //构造返回
        $this->assign("datas", $datas);
        $this->assign('keyword', $keyword);
        $this->assign('scope', $scope);
        return view(__FUNCTION__);
    }


    /*
     * 全文检索 删除
     */
    public function fullsearchDel(){
        if (request()->isPost() && request()->isAjax()){
            $siteid = input('post.siteid/d',0);
            $catid = input('post.catid/d',0);
            $aid   = input('post.aid/d',0);
            if(empty($siteid) || empty($catid) || empty($aid)){
                return $this->error('删除失败,参数错误！');
            }
            $articleModel = new Article();
            $rs = $articleModel->del($siteid, $catid, $aid);
            if(!$rs){
                return $this->error('删除失败');
            }else{
                $this->delPosition($catid, $aid);
                return $this->success('删除成功');
            }
        }
    }


}
