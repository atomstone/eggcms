<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:60:"E:\www\zbzx\public/../application/admin\view\index\home.html";i:1488556636;s:44:"../application/admin/view/public/header.html";i:1488556637;s:40:"../application/admin/view/public/js.html";i:1488556637;s:44:"../application/admin/view/public/footer.html";i:1488556637;}*/ ?>
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
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-fixed-top mega-menu">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner">
		<!-- BEGIN LOGO -->	
		<a class="navbar-brand" href="/" target="_blank" style="padding-left:30px"><span style="color:#C3C3C3;font-weight:bold;"><?php echo config('web_name'); ?></span><span style="color:#F53E3E;font-weight:bold;"></span></a>
		<!-- END LOGO -->
		
		<!-- 顶部菜单 start -->
		<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">
        <?php foreach($menus as $k => $v){ ?>
  		      <li name="menu_num<?php echo $v['menuid']; ?>" class="classic-menu-dropdown <?=($k==0) ? 'active' : '';?>"><a href="#this"><?php echo $v['menuname']; ?><span class="selected"></span></a></li>
        <?php } ?>
			</ul>
		</div>
		<!-- 顶部菜单 end -->
		
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?php echo \think\Config::get('web_res_root'); ?>img/menu-toggler.png" alt=""/>
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">
			<!-- BEGIN NOTIFICATION DROPDOWN -->
			<li class="dropdown" id="header_notification_bar">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="fa fa-th-large"></i>
					<span class="badge"><?php echo $site['name']; ?></span>
				</a>
				<ul class="dropdown-menu extended notification">
					<li><p>请选择站点</p></li>
					<li>
						<ul class="dropdown-menu-list scroller" style="height: 250px;">
						  <?php foreach($sites as $v){ ?>
							<li>
								<a href="<?php echo url('Index/home', array('siteid'=>$v['siteid'])); ?>">
								  <span class="label label-icon label-success"><i class="fa fa-plus"></i></span><?php echo $v['name']; ?>
								</a>
							</li>
						  <?php } ?>
						</ul>
					</li>
				</ul>
			</li>
			<!-- END NOTIFICATION DROPDOWN -->

			<!-- END TODO DROPDOWN -->
			<!-- BEGIN USER LOGIN DROPDOWN -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?php echo \think\Config::get('web_res_root'); ?>img/avatar1_small.jpg"/>
					<span class="username hidden-1024"><?=!empty($admininfo['realname']) ? $admininfo['realname'] : $admininfo['adminname'];?></span>
					<i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo url('Index/logout'); ?>">
							<i class="fa fa-key"></i> 退出登录
						</a>
					</li>
				</ul>
			</li>
			<!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN HORIZONTAL MENU PAGE SIDEBAR1 -->
	<div class="page-sidebar navbar-collapse collapse">
		<!--BEGIN: SIDEBAR MENU FOR DESKTOP-->
		<ul class="page-sidebar-menu hidden-sm hidden-xs" data-auto-scroll="true" data-slide-speed="200">
			<li>
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler hidden-sm hidden-xs">
				</div>
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
			</li>
			

      <?php foreach($menus as $k => $top){ ?>
          <li class="menu_master_li menu_num<?php echo $top['menuid']; ?> start" style="display:<?=($k==0) ? '' : 'none';?>">
    				<a href="javascript:;">
    					<i class="fa fa-cogs"></i>
    					<span class="title"><?php echo $top['menuname']; ?></span>
    					<span class="selected "></span>
    					<span class="arrow open"></span>
    				</a>
    				<ul class="sub-menu" style="display: block;">
    				  <?php foreach($top['sub'] as $sub){ ?>
                  <li><a class="ajaxify" href="<?php echo url($sub['c'].'/'.$sub['a']); ?>"><?php echo $sub['menuname']; ?></a></li>
              <?php } ?>
    				</ul>
    			</li>
      <?php } ?>
		</ul>
	</div>
	<!-- END BEGIN HORIZONTAL MENU PAGE SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content" style="padding-top:9px;">
		  <div class="page-content-body">
			</div>
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="footer">
	<div class="footer-inner">
		 <?=date('Y');?> &copy; Metronic by <?php echo config('web_name'); ?>.
	</div>
	<div class="footer-tools">
		<span class="go-top">
			<i class="fa fa-angle-up"></i>
		</span>
	</div>
</div>
<!-- END FOOTER -->

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
    jQuery(document).ready(function() {
      
       App.init();
       ComponentsDropdowns.init();
       $('.page-sidebar .ajaxify.start').click(); // load the content for the dashboard page.
       
             
       $(".classic-menu-dropdown").click(function(){
           $(".menu_master_li").hide();
           $("."+$(this).attr('name')).show();
           $(".classic-menu-dropdown").attr('class','classic-menu-dropdown');
           $(this).attr('class','classic-menu-dropdown active');
           
           $("."+$(this).attr('name')).children('ul:first').children('li:first').children('a:first').click();
          // 下面的 ul(.sub-menu)的li的a点击
       });
       
    });
    
    //location.reload();
    
	</script>
	

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>

