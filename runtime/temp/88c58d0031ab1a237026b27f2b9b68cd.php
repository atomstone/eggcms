<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"E:\www\zbzx\public/../application/admin\view\menu\menuAdd.html";i:1505152485;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>前台菜单管理<i class="fa fa-angle-right"></i></li>
    <li><a href=""><?php echo $type['typename']; ?> - 菜单列表</a><i class="fa fa-angle-right"></i></li>
    <li>添加菜单</li>
    </ul>
    <!-- END PAGE TITLE & BREADCRUMB-->
  </div>
</div>
<!--导航 end-->

<div class="col-auto">
  <div class="col-1">
    <div class="content pad-6">
    </div>
  </div>
</div>

<div class="row">
	<div class="col-md-12">
		<!-- BEGIN VALIDATION STATES-->
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i><?php echo $type['typename']; ?> - 添加菜单
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="#portlet-config" data-toggle="modal" class="config"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="#this" id="form1" class="form-horizontal">
				  <input type="hidden" name="typeid" id="typeid" value="<?php echo $type['typeid']; ?>">
					<div class="form-body">        
						<div class="form-group">
							<label class="control-label col-md-1">上级菜单</label>
							<div class="col-md-4">
								<?php echo widget('Form/frontMenuSelect', array($type['typeid'], 'data[parentid]', 'parentid', $parentid)); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-1">菜单名称</label>
							<div class="col-md-4">
								<?php echo widget('Form/input', array('data[menuname]', 'menuname')); ?>
							</div>
						</div>
            <div class="form-group">
							<label class="control-label col-md-1">链接</label>
							<div class="col-md-10">
							  <div class="radio-list">
                  <label class="radio-inline">
                    <input type="radio" name="data[linkstype]" value="1">无连接
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="data[linkstype]" value="2">
                    <input type="text" name="data[url]" id="url"  value="http://" style="width:300px;">
                  </label>
                  <label class="radio-inline" id="area_inkage">
                    <input type="radio" name="data[linkstype]" value="3">           
                    <select class="s1 catlinkage" id="siteid" name="data[siteid]"><option value="">请选择</option></select>  
                    <select class="s2 catlinkage" id="catid" name="data[catid]"><option value="">请选择</option></select>
                  </label>
                </div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-1">打开方式</label>
							<div class="col-md-4">
								<?php echo widget('Form/radio', array(config('menu.target'),'data[target]', '_self')); ?>
              </div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-1">图标</label>
							<div class="col-md-4">
								<?php echo widget('Form/upOneFile', array('data[image]', 'image')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-1">介绍</label>
							<div class="col-md-4">
								<?php echo widget('Form/textarea', array('data[description]', 'description')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-1">排序</label>
							<div class="col-md-4">
								<?php echo widget('Form/inputnumber', array('data[listorder]', 'listorder')); ?>
							</div>
						</div>
					</div>
					<div class="form-actions fluid">
						<div class="col-md-offset-3 col-md-9">
              <button class="btn green" data-loading-text="正在处理..." type="submit" id="sub"><i class="fa fa-check"></i>保存</button>
              <button href="<?php echo url('menu/menuList',array('typeid'=>$type['typeid'])); ?>" class="ajaxify btn default" type="button" id="gobackBtn">返回</button>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
		<!-- END VALIDATION STATES-->
	</div>
</div>

<SCRIPT type="text/javascript">

  /*
  * 表单校验 提交
  */
  jQuery(document).ready(function($){
            
      $.validator.setDefaults({
          submitHandler: function(form){
              var $btn = $("#sub").button('loading');
              //提交表单
              $.post("<?php echo url('menu/menuAdd'); ?>", $('#form1').serialize(), function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    App.doShow("<?php echo url('menu/menuList',array('typeid'=>$type['typeid'])); ?>");
                  }else{
                      error(data.msg);
                      $btn.button('reset');
                  }
              });
          }
      });
      $("#form1").validate({
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
      
  });

  $("#area_inkage").linkageSelect({ 
      url:"<?php echo url('common/category/siteCategoryLinkage'); ?>"
      
      //,s1default:"" //省份 
      //,s2default:"" //城市 
      //,s3default:"" //区县 
      //,nodata:"none" //当子集无数据时，隐藏select 
      ,required:false
  }); 



  $("#url").click(function(){
      $("input[name='data[linkstype]'][value='2']").attr('checked','checked');
      $("input[name='data[linkstype]'][value='2']").parent().attr('class','checked');
      $("input[name='data[linkstype]'][value='1']").removeAttr('checked');
      $("input[name='data[linkstype]'][value='1']").parent().attr('class','');
      $("input[name='data[linkstype]'][value='3']").removeAttr('checked');
      $("input[name='data[linkstype]'][value='3']").parent().attr('class','');
  });
						
  $(".catlinkage").click(function(){
      $("input[name='data[linkstype]'][value='3']").attr('checked','true');
      $("input[name='data[linkstype]'][value='3']").parent().attr('class','checked');
      $("input[name='data[linkstype]'][value='1']").removeAttr('checked');
      $("input[name='data[linkstype]'][value='1']").parent().attr('class','');
      $("input[name='data[linkstype]'][value='2']").removeAttr('checked');
      $("input[name='data[linkstype]'][value='2']").parent().attr('class','');
  });
  
  $("#catid").change(function(){
      if($('#menuname').val() == ''){
          $('#menuname').val($(this).text());
      }
  });

</SCRIPT>

