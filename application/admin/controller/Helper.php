<?php
namespace app\admin\controller;

class Helper extends Base
{


    public function __construct(){
        parent::__construct();
    }

    public function getFormTypeHtml()
    {
        $formtype = input('post.formtype');
        $fieldid = input('post.fieldid/d', 0 );
        $field = model('ModelField')->get($fieldid);
        $setting = array();
        if(!empty($field)){
            $setting = json_decode($field['setting'], true);
        }
        $this->assign('formtype', $formtype);
        $this->assign('field', $field);
        $this->assign('setting', $setting);
        return view(__FUNCTION__);
    }

    public function createDataitem(){
        $setting = input('post.setting');
        $id = input('post.id');
        $name = input('post.name');
        $num = input('post.num');
        $val = input('post.val');
        if(!empty($val)){
            $val = base64_decode($val);
            $val = json_decode($val,true);
        }
        $this->assign('setting', $setting);
        $this->assign('id', $id);
        $this->assign('num', $num);
        $this->assign('name', $name);
        $this->assign('val', $val);
        return view(__FUNCTION__);
    }

}
