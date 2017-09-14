<?php
namespace app\admin\widget;
use app\common\model;
use app\common\model\Article;
use Think\Json;
class Form
{

    /*
     * 文本框
     */
    public function input($name, $id, $value = '', $other = array()){
        $class = 'form-control';
        if(isset($other['class'])){
            $class = $other['class'];
        }
        $html = "<input type='text' name='{$name}' id='{$id}' value='{$value}' class='{$class}'>";
        return $html;
    }

    /*
     * 数字框
     */
    public function inputnumber($name, $id, $value = '', $other = array()){
        $class = 'form-control';
        if(isset($other['class'])){
            $class = $other['class'];
        }
        $a = ' onkeyup="value=value.replace(/[^\d]/g,\'\')" onbeforepaste="clipboardData.setData(\'text\',clipboardData.getData(\'text\').replace(/[^\d]/g,\'\'))" ';
        $html = "<input  style='width:100px;' type='text' name='{$name}' id='{$id}' value='{$value}' class='{$class}' ".$a." >";
        return $html;
    }

    /*
     * 密码框
     */
    public function pwd($name, $id, $value = '', $other = array()){
        $class = 'form-control';
        if(isset($other['class'])){
            $class = $other['class'];
        }
        $html = "<input type='password' name='{$name}' id='{$id}' value='{$value}' class='{$class}'>";
        return $html;
    }

    /*
     * 多行文本框
     */
    public function textarea($name, $id, $value = '', $other = array()){
        $class = 'form-control';
        if(isset($other['class'])){
            $class = $other['class'];
        }
        $html = "<textarea name='{$name}' id='{$id}' rows='3' class='{$class}'>{$value}</textarea>";
        return $html;
    }

    /*
     * 多行文本框
     */
    public function dataitem($setting, $name, $id, $value = ''){
        $view = new \think\View();
        $view->assign('setting', $setting);
        $view->assign('name', $name);
        $view->assign('id', $id);
        $view->assign('value', $value);
        return $view->fetch('widget/form/dataitem');
        return $value;
    }


    /*
     * 单选按钮
     */
    public function radio(array $data, $name, $value="", $other = array()){
        $fun = '';
        if(isset($other['fun'])){
            $fun = $other['fun'];
        }
        $html = '<div class="radio-list">';
        foreach($data as $k => $v){
            $checked = ($k == $value) ? 'checked' : '';
            $html .= '<label class="radio-inline">';
            $html .= "<input type='radio' name='{$name}' value='{$k}' {$checked}  onclick=\"{$fun}\"/>";
            $html .= $v;
            $html .= '</label>';
        }
        $html .= '</div>';
        return $html;
    }

    /*
    * 下拉框
    */
    public function select(array $data, $name, $id, $value="", $other = array()){
        $fun = '';
        $class = 'form-control input-medium';
        $firstOptionName = '≡请选择≡';
        $firstOptionValue = '';
        if(isset($other['fun'])){
            $fun = $other['fun'];
        }
        if(isset($other['class'])){
            $class = $other['class'];
        }
        if(isset($other['firstOptionName'])){
            $firstOptionName = $other['firstOptionName'];
        }
        if(isset($other['firstOptionValue'])){
            $firstOptionValue = $other['firstOptionValue'];
        }
        $html  = "<select name='{$name}' id='{$id}' class='{$class}' onchange=\"{$fun}\">";
        $html .= "<option value='".$firstOptionValue."'>".$firstOptionName."</option>";
        foreach($data as $k => $v){
            $selected = ($k == $value && $value !== '') ? 'selected' : '';
            $html .= "<option value='{$k}' ".$selected.">{$v}</option>";
        }
        $html .= "</select>";
        return $html;
    }

   /*
    * 页数选择下拉框
    */
    public function pageSelect($val = 20){
        $html  = "每页显示<select id='pagesize' name='pagesize'>";
        $k = 0;
        for ($i = 1; $i <= 10; $i++) {
            $k += 10;
            $selected = ($k == $val) ? 'selected' : '';
            $html .= "<option value='{$k}' {$selected}>{$k}</option>";
        }
        $html .= "</select>条";
        return $html;
    }

    /*
     * 时间控件
    */
    public static function datetime($name, $id, $value = '', $other = array()){
        $name   = empty($name) ? '' : $name;
        $id     = empty($id) ? '' : $id;
        $format = 'yyyy-MM-dd HH:mm:ss';
        if(!empty($value)){
            $ret = strtotime($value);
            if($ret === FALSE || $ret == -1){
                $value = date("Y-m-d H:i:s", $value);
            }
        }else{
            $value = date('Y-m-d H:i:s');
        }
        if(isset($other['format'])){
            $format = $other['format'];
        }
        if($format == 'yyyy-MM-dd HH:mm:ss'){
           $width = '140px;';
        }else{
           $width = '87px';
        }
        $startDate  = '%y-%M-%d %H:%m:%s';
        $str = '<input class="Wdate" style="width:'.$width.';" readonly="readonly" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" onFocus="WdatePicker({startDate:\''.$startDate.'\',dateFmt:\''.$format.'\',alwaysUseStartDate:true})"/>';
        return $str;
    }

    /*
    * 富文本编辑器
    */
    public function kindeditor($name, $id, $value = '', $other = array()) {
        $width   =  '100%';
        $height  =  '300';
        $itemstype = 'all';
        if(isset($other['width'])){
            $width = $other['width'];
        }
        if(isset($other['height'])){
            $height = $other['height'];
        }
        if(isset($other['itemstype'])){
            $itemstype = $other['itemstype'];
        }
        $str     = '';
        $str .= "    <textarea id='{$id}' name='{$name}' style='width:{$width}px;height:{$height}px;'>    \r\n";
        $str .=  $value;
        $str .= "    </textarea>\r\n";
        $str .= "    <script>    \r\n";
        $str .= "      createKind('{$id}','{$width}','{$height}','{$itemstype}');    \r\n";
        $str .= "    </script>    \r\n";
        return $str;
    }

    /*
    * 获取默认图片
    */
    public function defaultImg($data = false){
        $html = '';
        if(!empty($data)){
            $data = $this->json->decode($data);
            foreach($data as $v){
                if($v->default == 1){
                    $html = "<img src='".$v->src."' width='30px' height='30px'>";
                    break;
                }
            }
        }
        return $html;
    }

    /*
    * 上传单个文件/图片
    */
    public function upOneFile($name, $id, $value = ''){
        $dvalue = '/static/admin/assets/img/upload-pic.png';
        if(!empty($value)){
            $dvalue = $value;
            $btn = "<button id='{$id}uploadButton' style='cursor:pointer;' class='btn btn-success' type='button'>替换</button>";
        }else{
            $btn = "<button id='{$id}uploadButton' style='cursor:pointer;' class='btn btn-primary' type='button'>选择</button>";
        }
        $html = "<img src='{$dvalue}' id='{$id}img' width='40%' height='30%'>";
        $html .= $btn;
        $html .= "<input type='hidden' name='{$name}' id='{$id}' value='{$value}'>";
        $html .= "<script>createUpload('{$id}');</script>";
        return $html;
    }

    /*
     * 上传单个视频
     */
    public function upOneVideo($name, $id, $value = ''){
        //$type = 1;
        //$address = '';
        if(!empty($value)){
            //$arr = json_decode($value, true);
            //$type = $arr['type'];
            //$address = $arr['address'];
            $btn = "<button id='{$id}uploadButton' style='cursor:pointer;' class='btn btn-success' type='button'>替换</button>";
        }else{
            $btn = "<button id='{$id}uploadButton' style='cursor:pointer;' class='btn btn-primary' type='button'>选择</button>";
        }
        $html = '';
        //$html .= "<input type='radio' myid='{$id}' name='{$name}[]' class='{$id}type' value='1' ".(($type==1)?'checked':'')." />上传视频";
        //$html .= "<input type='radio' myid='{$id}' name='{$name}[]' class='{$id}type' value='2' ".(($type==2)?'checked':'')." />外网视频";
        $html .= '<div class="input-group">';
        $html .= '<span class="input-group-btn">';
        $html .= $btn;
        $html .= '</span>';
        $html .= "<input  name='{$name}' id='{$id}' value='{$value}' type='text' class='form-control' placeholder='视频地址'>";
        $html .= '</div>';
        $html .= "<span id='{$id}span'></span>";
        $html .= "<script>";
        $html .= "$('.{$id}type').click(function(){";
        $html .= "    if($(this).val() == 1){";
        $html .= "        $('#{$id}uploadButton').show();";
        $html .= "    }else{";
        $html .= "        $('#{$id}uploadButton').hide();";
        $html .= "    }";
        $html .= "});";
        $html .= "createUploadVideo('{$id}');";
        $html .= "</script>";
        return $html;
    }


    /*
     * 上传单个文件
     */
    public function downonefile($name, $id, $value = ''){
        //$type = 1;
        //$address = '';
        if(!empty($value)){
            //$arr = json_decode($value, true);
            //$type = $arr['type'];
            //$address = $arr['address'];
            $btn = "<button id='{$id}uploadButton' style='cursor:pointer;' class='btn btn-success' type='button'>替换</button>";
        }else{
            $btn = "<button id='{$id}uploadButton' style='cursor:pointer;' class='btn btn-primary' type='button'>选择</button>";
        }
        $html = '';
        //$html .= "<input type='radio' myid='{$id}' name='{$name}[]' class='{$id}type' value='1' ".(($type==1)?'checked':'')." />上传视频";
        //$html .= "<input type='radio' myid='{$id}' name='{$name}[]' class='{$id}type' value='2' ".(($type==2)?'checked':'')." />外网视频";
        $html .= '<div class="input-group">';
        $html .= '<span class="input-group-btn">';
        $html .= $btn;
        $html .= '</span>';
        $html .= "<input  name='{$name}' id='{$id}' value='{$value}' type='text' class='form-control' placeholder='文件地址'>";
        $html .= '</div>';
        $html .= "<span id='{$id}span'></span>";
        $html .= "<script>";
        $html .= "$('.{$id}type').click(function(){";
        $html .= "    if($(this).val() == 1){";
        $html .= "        $('#{$id}uploadButton').show();";
        $html .= "    }else{";
        $html .= "        $('#{$id}uploadButton').hide();";
        $html .= "    }";
        $html .= "});";
        $html .= "createUploadOneFile('{$id}');";
        $html .= "</script>";
        return $html;
    }


    /*
     * 多文件上传
     */
    public function multiplefilesupload($name, $id, $data = '', $limit = 0){
        $html = '';
        $html .=  "<input class='moreimg' type='hidden' name='".$name."' id='".$id."' value='".$data."'>";
        $html .=  '<span id="spanButtonPlaceholder_'.$id.'"></span>';
        $html .=  '<div id="divFileProgressContainer_'.$id.'"></div>';
        $html .=  '<ul class="list-inline" id="thumbnails_'.$id.'"  class="tiles">';
        if(!empty($data)){
            $imglist = json_decode($data, true);
            foreach($imglist as $k => $v){
                $html .= '<li class="formupfile" style="background-color:#f1f1f1;width:100%;height:80px;margin-top:3px;">';
                $html .= '路径:<span class="_file">'.$v['src'].'</span>';
                $html .= '<br>';
                $html .= '文件名:<input onkeyup="upfileParse(\''.$id.'\')" type="text" class="imgInput" value="'.$v['title'].'" style="width:90%;">';
                $html .= '<br>';
                $html .= '<a style="color:#000000;" href="javascript:void(0)" onclick="$(this).parent().remove();upfileParse(\''.$id.'\');">删除</a>';
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '<script type="text/javascript">';
            $html .= 'var swfu_'.$id.';';
            $html .= 'swfu_'.$id.' = new SWFUpload({';
            $html .= 'upload_url: "/Admin/Upload/doUpload?dir=all",';
            $html .= 'post_params: {"PHPSESSID": "'.session_id().'"},';
            $html .= 'file_size_limit : "200MB",';
            $html .= 'file_types : "*.*",';
            $html .= 'file_types_description : "*.*",';
            $html .= 'file_upload_limit : "'.$limit.'",';
            $html .= 'cid : "'.$id.'",';
            $html .= 'file_queue_error_handler : fileQueueError,';
            $html .= 'file_dialog_complete_handler : fileDialogComplete,';
            $html .= 'upload_progress_handler : uploadProgress,';
            $html .= 'upload_error_handler : uploadError,';
            $html .= 'upload_success_handler : uploadSuccess_file,';
            $html .= 'upload_complete_handler : uploadComplete,';
            $html .= 'button_image_url : "/static/admin/assets/scripts/swfupload/images/17x18.png",';
            $html .= 'button_placeholder_id : "spanButtonPlaceholder_'.$id.'",';
            $html .= 'button_width: 180,';
            $html .= 'button_height: 18,';
            $html .= 'button_text : "点击上传",';
            $html .= 'button_text_style : "",';
            $html .= 'button_text_top_padding: 0,';
            $html .= 'button_text_left_padding: 18,';
            $html .= 'button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,';
            $html .= 'button_cursor: SWFUpload.CURSOR.HAND,';
            $html .= 'flash_url : "/static/admin/assets/scripts/swfupload/swfupload.swf",';
            $html .= 'custom_settings : {';
                $html .= 'upload_target : "divFileProgressContainer_'.$id.'"';
            $html .= '},';
            $html .= 'debug: false';
            $html .= '});';

            $html .= '$("#thumbnails_'.$id.'").dragsort({ dragSelector: "li", dragBetween: true, placeHolderTemplate: "<li class=\'formupfile\'></li>", dragEnd: function() {upfileParse("'.$id.'")} });';

        $html .= '</script>';
        return $html;
    }

    /*
     * 多图片上传
     */
    public function upimg($name, $id, $data = '', $limit = 0){
        $html = '';
        $html .=  "<input class='moreimg' type='hidden' name='".$name."' id='".$id."' value='".$data."'>";
        $html .=  '<span id="spanButtonPlaceholder_'.$id.'"></span>';
        $html .=  '<div id="divFileProgressContainer_'.$id.'"></div>';
        $html .=  '<ul class="list-inline" id="thumbnails_'.$id.'"  class="tiles">';
        if(!empty($data)){
            $imglist = json_decode($data, true);
            foreach($imglist as $k => $v){
                $html .= '<li class="pic '.(($v['default'] == 1) ? 'defaultImg' : '').'" style="width:150px;height:175px;">';
                $html .= '<img class="img" onclick="defaultImage(this, \''.$id.'\');" style="opacity:1;width:140px;height:120px;margin-top:6px;" src="'.$v['src'].'" />';
                $html .= '<br>';
                $html .= '<input onkeyup="upimgParse(\''.$id.'\')" type="text" class="imgInput" value="'.$v['title'].'" style="width:140px;">';
                $html .= '<br>';
                $html .= '<a style="color:#000000;" href="javascript:void(0)" onclick="$(this).parent().remove();upimgParse(\''.$id.'\');">删除</a>';
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '<script type="text/javascript">';
            $html .= 'var swfu_'.$id.';';
            $html .= 'swfu_'.$id.' = new SWFUpload({';
            $html .= 'upload_url: "/Admin/Upload/doUpload",';
            $html .= 'post_params: {"PHPSESSID": "'.session_id().'"},';
            $html .= 'file_size_limit : "2 MB",';
            $html .= 'file_types : "*.jpg",';
            $html .= 'file_types_description : "JPG Images",';
            $html .= 'file_upload_limit : "'.$limit.'",';
            $html .= 'cid : "'.$id.'",';
            $html .= 'file_queue_error_handler : fileQueueError,';
            $html .= 'file_dialog_complete_handler : fileDialogComplete,';
            $html .= 'upload_progress_handler : uploadProgress,';
            $html .= 'upload_error_handler : uploadError,';
            $html .= 'upload_success_handler : uploadSuccess,';
            $html .= 'upload_complete_handler : uploadComplete,';
            $html .= 'button_image_url : "/static/admin/assets/scripts/swfupload/images/17x18.png",';
            $html .= 'button_placeholder_id : "spanButtonPlaceholder_'.$id.'",';
            $html .= 'button_width: 180,';
            $html .= 'button_height: 18,';
            $html .= 'button_text : "点击上传",';
            $html .= 'button_text_style : "",';
            $html .= 'button_text_top_padding: 0,';
            $html .= 'button_text_left_padding: 18,';
            $html .= 'button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,';
            $html .= 'button_cursor: SWFUpload.CURSOR.HAND,';
            $html .= 'flash_url : "/static/admin/assets/scripts/swfupload/swfupload.swf",';
            $html .= 'custom_settings : {';
                $html .= 'upload_target : "divFileProgressContainer_'.$id.'"';
            $html .= '},';
            $html .= 'debug: false';
            $html .= '});';

            $html .= '$("#thumbnails_'.$id.'").dragsort({ dragSelector: "li", dragBetween: true, placeHolderTemplate: "<li class=\'pic\'></li>", dragEnd: function() {upimgParse("'.$id.'")} });';

        $html .= '</script>';
        return $html;
    }

    /*
     * 构造角色下拉
     */
    public function roleSelect($name, $id, $value='', $other = array()){
        $roles = model('AdminRole')->order("listorder ASC")->select();
        $data = array();
        foreach($roles as $v){
            $data[$v['roleid']] = $v['rolename'];
        }
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造友情链接分类下拉
     */
    public function linksTypeSelect($siteid, $name, $id, $value='', $other = array()){
        $types = model('LinksType')->where("siteid = " . $siteid)->order('listorder asc')->select();
        $data = array();
        foreach($types as $v){
            $data[$v['typeid']] = $v['typename']."(".$v['code'].")";
        }
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造模型下拉
     */
    public function modelSelect($siteid, $name, $id, $value='', $other = array()){
        $models = model('Model')->where("siteid = " . $siteid)->select();
        $data = array();
        foreach($models as $v){
            $data[$v['modelid']] = $v['name'];
        }
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造专题类型下拉
     */
    public function specialTypeSelect($specialid, $name, $id, $value='', $other = array()){
        $types = model('SpecialType')->where("specialid = " . $specialid)->order("listorder asc")->select();
        $data = array();
        foreach($types as $v){
            $data[$v['typeid']] = $v['typename'];
        }
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造模型下拉
     */
    public function examTypeSelect($siteid, $name, $id, $value='', $other = array()){
        $types = model('ExamType')->where("siteid = " . $siteid)->order("listorder asc")->select();
        $data = array();
        foreach($types as $v){
            $data[$v['typeid']] = $v['typename'];
        }
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造菜单下拉
     */
    public function menuSelect($name, $id, $value=''){
        $menus = model('AdminMenu')->getTreeMenus();
        $data = array();
        foreach($menus as $v){
            $data[$v['menuid']] = $v['tag'].$v['menuname'];
        }
        $other = array('firstOptionName' => '≡作为顶级菜单≡','firstOptionValue' => 0);
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造前台菜单下拉
     */
    public function frontMenuSelect($typeid, $name, $id, $value=''){
        $menus = model('Menu')->getTreeMenus($typeid);
        $data = array();
        foreach($menus as $v){
            $data[$v['menuid']] = $v['tag'].$v['menuname'];
        }
        $other = array('firstOptionName' => '≡作为顶级菜单≡','firstOptionValue' => 0);
        return $this->select($data, $name, $id, $value, $other);
    }
    /*
     * 构造栏目下拉
     */
    public function categorySelect($siteid, $name, $id, $value='', $other = array()){
        $categorys = model('Category')->getTreeCats($siteid, 0);
        $data = array();
        foreach($categorys as $v){
            $data[$v['catid']] = $v['tag'].$v['catname'];
        }
        $other['firstOptionName']  = isset($other['firstOptionName']) ? $other['firstOptionName'] : '≡作为一级栏目≡';
        $other['firstOptionValue'] = 0;
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造中队文章分类下拉
     */
    public function teamCatSelect($deptid, $name, $id, $value='', $other = array()){
        $categorys = model('TeamCat')->getTreeCats($deptid, 0);
        $data = array();
        foreach($categorys as $v){
            $data[$v['catid']] = $v['tag'].$v['catname'];
        }
        $other['firstOptionName']  = isset($other['firstOptionName']) ? $other['firstOptionName'] : '≡作为一级分类≡';
        $other['firstOptionValue'] = 0;
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
     * 构造组织机构下拉
     */
    public function deptSelect($name, $id, $value='', $other = array()){
        $categorys = model('Dept')->getTreeDepts(0);
        $data = array();
        foreach($categorys as $v){
            $data[$v['deptid']] = $v['tag'].$v['deptname'];
        }
        $other['firstOptionName']  = isset($other['firstOptionName']) ? $other['firstOptionName'] : '≡请选择≡';
        $other['firstOptionValue'] = 0;
        return $this->select($data, $name, $id, $value, $other);
    }

    /*
    * 构造文章添加编辑控件
    */
    public function getArticleForm($field){
        $input_arr       = array('text','title','keyword');
        $inputnumber_arr = array('number');
        $textarea_arr    = array('textarea');
        $kindeditor_arr  = array('editor');
        $img_arr         = array('image');
        $imgs_arr        = array('images');
        $select_arr      = array('box');
        $datetime_arr    = array('date','datetime');
        $video_arr       = array('video');
        $downonefile_arr   = array('downonefile');
        $downfiles_arr   = array('downfiles');
        $dataitem_arr    = array('dataitem');

        $name = "master[".$field['field']."]";
        $id = $field['field'];

        $val = '';
        if(isset($field['def_val'])){
            $val = $field['def_val'];
        }

        if(in_array($field['formtype'], $input_arr)){
            $control = $this->input($name, $id, $val);
        }elseif(in_array($field['formtype'], $inputnumber_arr)){
            $control = $this->inputnumber($name, $id, $val);
        }elseif(in_array($field['formtype'], $textarea_arr)){
            $control = $this->textarea($name, $id, $val);
        }elseif(in_array($field['formtype'], $input_arr)){
            $control = $this->input($name, $id, $val);
        }elseif(in_array($field['formtype'], $kindeditor_arr)){
            $control = $this->kindeditor($name, $id, $val);
        }elseif(in_array($field['formtype'], $img_arr)){
            $control = $this->upOneFile($name, $id, $val);
        }elseif(in_array($field['formtype'], $imgs_arr)){
            //$val = "".$val."";
            $control = $this->upimg($name, $id, $val);
        }elseif(in_array($field['formtype'], $datetime_arr)){
            $control = $this->datetime($name, $id, $val);
        }elseif(in_array($field['formtype'], $select_arr)){
            $setting = json_decode($field['setting'],true);
            if($setting['boxtype'] == 'radio'){
                $control = $this->radio($setting['options'], $name, $val);
            }elseif($setting['boxtype'] == 'select'){
                $control = $this->select($setting['options'], $name, $id, $val);
            }elseif($setting['boxtype'] == 'checkbox'){
                return '';
            }else{
                return '';
            }
        }elseif(in_array($field['formtype'], $video_arr)){
            $control = $this->upOneVideo($name, $id, $val);
        }elseif(in_array($field['formtype'], $downonefile_arr)){
                $control = $this->downonefile($name, $id, $val);
        }elseif(in_array($field['formtype'], $downfiles_arr)){
            $control = $this->multiplefilesupload($name, $id, $val);
        }elseif(in_array($field['formtype'], $dataitem_arr)){
                $control = $this->dataitem($field['setting'], $name, $id, $val);
        }else{
            return '';
        }


        $html  = '<div class="form-group">';
        $html .= '<label class="control-label col-md-2" style="font-weight:bold;">'.$field['name'].'</label>';
        $html .= '<div class="col-md-10">';
        $html .= $control;
        if(!empty($field['tips'])){
            $html .= '<span class="help-block">'.$field['tips'].'</span>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /*
     * 构造文章列表页搜索控件
     */
    public function getArticleListSearchForm($moduleid,$search){
        $html = '';
        //获取参与查询的字段
        $fields = model('ModelField')->where("modelid = ".$moduleid." AND disabled = 0 and issearch = 1")->order("listorder asc")->select();
        foreach($fields as $k => $v){
            if(in_array($v['formtype'], array("text","title","keyword","number","textarea","editor"))) {
                    $html .= $v['name'] . ':';
                    $html .=  $this->input("search[".$v['field']."]", "search[".$v['field']."]", isset($search[$v['field']])?$search[$v['field']]:'', array('class' => 'form-control input-inline'));
            }elseif(in_array($v['formtype'], array("box"))){
                $setting = json_decode($v['setting'],true);
                if($setting['boxtype'] == 'radio'){
                    $html .= $v['name'] . ':';
                    $html .= $this->select($setting['options'], "search[".$v['field']."]", "search[".$v['field']."]", isset($search[$v['field']])?$search[$v['field']]:'', array('class' => 'form-control input-inline'));
                }elseif($setting['boxtype'] == 'select'){
                    $html .= $v['name'] . ':';
                    $html .= $this->select($setting['options'], "search[".$v['field']."]", "search[".$v['field']."]", isset($search[$v['field']])?$search[$v['field']]:'', array('class' => 'form-control input-inline'));
                }elseif($setting['boxtype'] == 'checkbox'){

                }
            }else{

            }
        }
        return $html;
    }

    /*
     * 构造文章列表搜索下拉框
     */
    public function getArticleListSearchPosition($catid,$search){
        $positions = model('Position')->where("enable = 1 and (catid = '' || FIND_IN_SET(".$catid.",catid))")->order("listorder asc")->select();
        if(empty($positions)){
            return '';
        }
        foreach($positions as $v){
            $data[$v['posid']] = $v['name'];
        }
        return '推荐位:'. $this->select($data, "search[posid]", "search[posid]", isset($search['posid'])?$search['posid']:'', array('class' => 'form-control input-inline'));
    }

    /*
     * 构造模板下拉
     * $type cat_index cat_list cat_show siteindex article_show
     */
    public function tmpSelect($name, $id, $val = '', $siteid, $type, $prefix){
        $dir = ROOT_PATH . "/application/index/view/site_".$siteid."/".$type."/";
        $result = array();
        if(file_exists($dir)){
            $file = scandir($dir);
            foreach($file as $v){
                if($v!='.' && $v!='..'){
                    if(strtolower(current(explode('_',$v))) == $prefix){
                        $v = basename($v,'.html');
                        $result[$v] = $v;
                    }
                }
            }
        }
        return $this->select($result, $name, $id, $val);
    }

    /*
     * 构造推荐位复选框
     */
    public function positionChecks($siteid, $catid = 0, $articleid = 0){
        $ischecked = array();
        if(!empty($catid) && !empty($articleid)){
            $rs = model('PositionData')->where("FIND_IN_SET('".$catid."',catid)  and aid = " . $articleid)->select();
            if(!empty($rs)){
                foreach($rs as $v){
                    $ischecked[] = $v['posid'];
                }
            }
        }
        $positions = model('Position')->where("siteid = " . $siteid . " and enable = 1 and (catid = '' || FIND_IN_SET(".$catid.",catid))")->order("listorder asc")->select();
        $html = '<div class="checkbox-list">';
        foreach($positions as $k => $v){
            $checked = '';
            if(in_array($v['posid'], $ischecked)){
                $checked = 'checked';
            }
            $html .= '<label class="checkbox-inline">';
            $html .= '<input name="positions[]" value="'.$v['posid'].'" '.$checked.' type="checkbox"></span> ' . $v['name'];
            $html .= '</label>';
        }
        $html .= '</div>';
        return $html;
    }

    /*
     * 构造关联控件
     */
    public function getRelation($siteid, $catid, $aid = 0){
        $html = '<a href="#this" url="'.url("Content/relationList", array("catid" => $catid)).'"  id="relationBut">[添加相关]</a>';
        $exist = '';
        if(!empty($catid) && !empty($aid)){
            $rs = model('relation')->where("catid = ".$catid." and aid = " . $aid)->select();
            $articleObj = new Article();
            foreach($rs as $v){
                $article = $articleObj->getOne($siteid, $v['rcatid'], $v['raid']);
                if(!empty($article)){
                    $id = $v['rcatid'] . '|' . $v['raid'];
                    $exist .= '<li onclick="$(this).remove();" id="'.$id.'"><input type="hidden" value="'.$id.'" name="raids[]"><a href="#this">'.$article["title"].' <span class="badge badge-danger">x</span></a></li>';
                }
            }

        }
        $html .= '<ul class="nav nav-pills" id="aids">'.$exist.'</ul>';
        return $html;
    }

}
