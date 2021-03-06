<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"E:\www\zbzx\public/../application/admin\view\index\login.html";i:1488556637;}*/ ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>登录==<?php echo config('web_name'); ?></title>
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
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/select2/select2-metronic.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-toastr/toastr.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo \think\Config::get('web_res_root'); ?>css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->

<div class="logo">
	<!--
	<a href="index.html">
		<img src="<?php echo \think\Config::get('web_res_root'); ?>img/logo-big.png" alt=""/>
	</a>
	-->
</div>

<div style="text-align:center;color:#ffffff;font-size:30px;font-weight:bold;"><?php echo config('web_name'); ?>-后台管理系统</div>

<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form id="loginform" class="loginform" action="#this" method="post">
		<h3 class="form-title">登录到您的账户</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>请输入登录名和密码</span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">登录名</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="登录名" id="adminname" name="adminname"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="password"/>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" id="sub" data-loading-text="登录中..." class="btn blue pull-right">登录 <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
		<div class="create-account">
			<p>欢迎进入登录页面</p>
		</div>
	</form>
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 <?=date('Y');?> &copy; <?php echo config('web_name'); ?>
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
	<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/respond.min.js"></script>
	<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/excanvas.min.js"></script> 
	<![endif]-->
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('web_res_root'); ?>plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/core/app.js" type="text/javascript"></script>
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/custom/login-soft.js" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo \think\Config::get('web_res_root'); ?>plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- END PAGE LEVEL SCRIPTS -->
<script src="<?php echo \think\Config::get('web_res_root'); ?>scripts/common.js"></script>

<script>
  
  
		jQuery(document).ready(function() {     

$("body").bind('keyup',function(event) {  
    if(event.keyCode==13){  
        $("#sub").click();  
    }     
});     

     $("#adminname").focus();
            
      $.validator.setDefaults({
          submitHandler: function(form){
              var $btn = $("#sub").button('loading');
              //提交表单
              $.post("<?php echo url('Index/index'); ?>", $('#loginform').serialize(), function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    $btn.button('reset');
                	    location.href = "<?php echo url('Index/home'); ?>";
                  }else{
                      error(data.msg);
                      $btn.button('reset');
                      $("#adminname").focus();
                  }
              });
          }
      });
      $("#loginform").validate({
  		    event:"blur",
  		    errorElement: "span",
          errorPlacement: function(error, element) { //配置错误信息输出
              error.appendTo( element.parent("div") );
          },
          success: function(label) {
              label.text("").addClass("success"); //返回值
          },
          rules: {
                //"data[test]": {required:true},
                  },
          messages: {
                //"data[test]": {required:"请填写手机号"},
                  }
      });
      

		  App.init();
		  Login.init();
		});
	</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>