<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:70:"E:\www\zbzx\public/../application/admin\view\content\relationList.html";i:1505129534;s:44:"../application/admin/view/public/header.html";i:1488556637;s:40:"../application/admin/view/public/js.html";i:1488556637;s:44:"../application/admin/view/public/footer.html";i:1488556637;}*/ ?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.1.1
Version: 2.0.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo config('web_name'); ?>-EggCms后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="<?php echo \think\Config::get('web_res_root'); ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-multi-select/css/multi-select.css"/>
<!-- BEGIN THEME STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-toastr/toastr.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>

  <div id="contentRightView" class="contentRightView">
    <div class="">
      <div class="col-md-12">
        <div class="portlet box light-grey">
          <div class="portlet-body">
            <table class="table  table-bordered table-hover" id="sample_1">
              <tr>
                <td colspan="3">
                  <form id="searchForm" action="<?php echo url('Content/relationList'); ?>">
                	  栏目:<?php echo widget('Form/categorySelect', array($siteid, 'catid', 'catid', $catid, array('class'=>'aaa'))); ?>
                	  标题:<?php echo widget('Form/input', array('search[title]', 'search[title]', isset($search['title'])?$search['title']:'', array('class' => 'form-control input-inline' ))); ?>
                	  <input name="p" id="p" type="hidden" value="<?php echo \think\Request::instance()->param('p'); ?>">
                    <?php echo widget('Form/pageSelect', array(\think\Request::instance()->param('pagesize'))); ?>
                    <input type="hidden" name="orderby" id="orderby" value="">
                    <input type="hidden" name="dir" id="dir" value="">
                    <button type="button" id="btnSearchForm"  data-loading-text="正在查询..." class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> 查询</button>
                	</form>
                </td>
              </tr>
              <?php 
              if(isset($articles) && !empty($articles)){
                  foreach($articles as $k => $v){ 
              ?>
                      <tr class="odd gradeX">
                        <td><input type="checkbox" name="raid[]" class="checkarticleid" value="<?php echo $v['catid']; ?>|<?php echo $v['id']; ?>" txt="<?php echo $v['title']; ?>" /></td>
                        <td><?php echo $v['id']; ?></td>
                        <td>
                          <?php 
                          echo $v['title'];
                          if($v['stick'] == 1){
                              echo '<span class="badge badge-danger badge-roundless">顶</span>';
                          }
/* 
                         if($v['posids'] == 1){
                              echo '<span class="badge badge-primary badge-roundless">荐</span>';
                          }
*/
                          if(!empty($v['thumb'])){
                              echo '<span class="badge badge-warning badge-roundless">图</span>';
                          }
                          ?>
                        </td>
                      </tr>

              <?php } ?>
                <tr>
                  <td colspan="3"><?php echo $page->show(); ?></td>
                </tr>
              <?php }else{ ?>
                <tr><td colspan="3">无数据</td></tr>
              <?php } ?>
            </table>
          </div>        
        </div>
      </div>
    </div>
  </div>


<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
  <script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/excanvas.min.js"></script>
  <script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/respond.min.js"></script>  
  <![endif]-->
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>plugins/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->


<!--ajax上传-->
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/ajaxupload.js"></script>
	
<!-- END CORE PLUGINS -->
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/core/app.js"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/custom/components-dropdowns.js"></script>
<!--表单验证-->
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/jquery.validate.js"></script>
<!--编辑器-->
<script charset="utf-8" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/kindeditor-4.1.10/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/kindeditor-4.1.10/lang/zh_CN.js"></script>
<link rel="stylesheet" href="<?php echo \think\Config::get('web_res_root'); ?>scripts/kindeditor-4.1.10/themes/default/default.css" />
<!--对话框组件-->
<script	src="<?php echo \think\Config::get('web_res_root'); ?>scripts/artDialog/jquery.artDialog.js?skin=opera"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/artDialog/plugins/iframeTools.js"></script>
<!--时间组件-->
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/My97DatePicker/WdatePicker.js"></script>
<!--图片上传-->
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/swfupload/handlers.js"></script>
<!--拖拽-->
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/jquery.dragsort-0.5.2.min.js"></script>
<!--树形结构 带复选框-->
<link rel="stylesheet" href="<?php echo \think\Config::get('web_res_root'); ?>scripts/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/ztree/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/ztree/js/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/ztree/js/jquery.ztree.exedit-3.5.min.js"></script>
<!--下拉联动-->
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>scripts/selectLinkage/jquery.select.js"></script>

<!--区域定位-->
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/autofix_anything/js/jquery.autofix_anything.js"></script>
<link rel="stylesheet" href="<?php echo \think\Config::get('web_res_root'); ?>scripts/autofix_anything/css/autofix_anything.css" type="text/css">
<link rel="stylesheet" href="<?php echo \think\Config::get('web_res_root'); ?>scripts/autofix_anything/css/style.css" type="text/css">

<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/common.js"></script>	

<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/custom/table-my-list-ajax.js"></script>


<script>
  var K=window.KindEditor;
  function createKind(id,width,height,itemstype){
      if(itemstype == 'all'){
          item = ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                		'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                		'anchor', 'link', 'unlink'];
      }else if(itemstype == 'basic'){
          item = ['source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
          'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
          'insertunorderedlist', '|', 'emoticons', 'image', 'link'];
      }
      K.create("#"+id,{
      resizeType : 0,
      width:'100%',
      height:''+height+'',
      afterBlur:function(){this.sync();},   //关键  同步KindEditor的值到textarea文本框   解决了多个editor的取值问题
      items : item
      });
  }
  

</script>

			
<script>
  
  function fy(p){
      $("#p").val(p);
      $("#searchForm").submit();
  }

  jQuery(document).ready(function($){
      $("#btnSearchForm").click(function(){
          var $btn = $("#btnSearchForm").button('loading');
          $("#p").val(1);
          $("#searchForm").submit();
      });    
  });
  
  $(".checkarticleid").click(function(){
      val = $(this).val();
      txt = $(this).attr('txt');
      if($(this).attr('checked') == 'checked'){
          $("#aids",window.parent.document).append('<li onclick="$(this).remove();" id="'+val+'"><input type="hidden" value="'+val+'" name="raids[]"><a href="#this">'+txt+' <span class="badge badge-danger">x</span></a></li>');
      }else{
          window.parent.document.getElementById(val).click();
          //$("#"+val,window.parent.document).remove();
      }
      
      
  });
  

</script>


<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
