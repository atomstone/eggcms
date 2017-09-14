<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"E:\www\zbzx\public/../application/admin\view\ad\spaceEdit.html";i:1488556639;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>广告位管理<i class="fa fa-angle-right"></i></li>
    <li><a href="">广告位列表</a><i class="fa fa-angle-right"></i></li>
    <li>编辑广告位</li>
    </ul>
    <!-- END PAGE TITLE & BREADCRUMB-->
  </div>
</div>
<!--导航 end-->

<div class="row">
	<div class="col-md-12">
		<!-- BEGIN VALIDATION STATES-->
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>编辑广告位
				</div>
				<div class="actions">
					<a href="<?php echo url('ad/spaceList'); ?>" class="ajaxify btn btn-default">返回广告位列表</a>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="#this" id="form1" class="form-horizontal">
				  <input type="hidden" name="space_id" value="<?php echo $space['space_id']; ?>">
					<div class="form-body"> 
						<div class="form-group">
							<label class="control-label col-md-3">广告位名称</label>
							<div class="col-md-6">
								<?php echo widget('Form/input', array('data[space_name]', 'space_name', $space['space_name'])); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">广告位类型</label>
							<div class="col-md-6">
				        <input type="hidden" name="data[space_type]" value="<?php echo $space['space_type']; ?>">
				        <?=config('ad.space_type')[$space['space_type']];?>
							</div>
						</div>
						
						<?php 
						if($space['space_type'] == 'imagechange'){ 
						    $setting = json_decode($space['space_setting'],true);
						?>
            <div class="form-group imagechange">
							<label class="control-label col-md-3">轮换模式</label>
							<div class="col-md-9">
								<?php echo widget('Form/select', array(config('ad.myfoucs_pattern'), 'data[space_setting][myfoucs_pattern]', 'myfoucs_pattern', isset($setting['myfoucs_pattern']) ? $setting['myfoucs_pattern'] : '' )); ?>
							</div>
						</div>
						<div class="form-group imagechange">
							<label class="control-label col-md-3">文字层高度</label>
							<div class="col-md-4">
								<?php echo widget('Form/input', array('data[space_setting][myfoucs_txtHeight]', 'myfoucs_txtHeight' , isset($setting['myfoucs_txtHeight']) ? $setting['myfoucs_txtHeight'] : 'default')); ?>
							  <span class="help-block">文字层高度(单位像素),0表示隐藏文字层,省略设置或'default'即为默认高度</span>
							</div>
						</div>
						<div class="form-group imagechange">
							<label class="control-label col-md-3">触发切换模式</label>
							<div class="col-md-4">
								<?php echo widget('Form/select', array(config('ad.myfoucs_trigger'),'data[space_setting][myfoucs_trigger]', 'myfoucs_trigger', isset($setting['myfoucs_trigger']) ? $setting['myfoucs_trigger'] : 'click')); ?>
							  <span class="help-block">触发切换模式['click'(鼠标点击)|'mouseover'(鼠标悬停)]</span>
							</div>
						</div>
						<div class="form-group imagechange">
							<label class="control-label col-md-3">是否保留边框</label>
							<div class="col-md-4">
								<?php echo widget('Form/select', array(config('ad.myfoucs_wrap'),'data[space_setting][myfoucs_wrap]', 'myfoucs_wrap', isset($setting['myfoucs_wrap']) ? $setting['myfoucs_wrap'] : 'false')); ?>
							  <span class="help-block">是否保留边框(有的话)[true|false]</span>
							</div>
						</div>
						<div class="form-group imagechange">
							<label class="control-label col-md-3">载入等待时间</label>
							<div class="col-md-4">
								<?php echo widget('Form/inputnumber', array('data[space_setting][myfoucs_loadIMGTimeout]', 'myfoucs_loadIMGTimeout', isset($setting['myfoucs_loadIMGTimeout']) ? $setting['myfoucs_loadIMGTimeout'] : 0)); ?>
							  <span class="help-block">载入myFocus图片的最长等待时间(Loading画面时间)(单位秒,0表示不等待直接播放)</span>
							</div>
						</div>

						<?php } ?>
												
						<div class="form-group">
							<label class="control-label col-md-3">广告位宽高</label>
							<div class="col-md-6">
								宽<?php echo widget('Form/inputnumber', array('data[space_width]', 'space_width', $space['space_width'])); ?>
								高<?php echo widget('Form/inputnumber', array('data[space_height]', 'space_height', $space['space_height'])); ?>
							</div>
						</div>						
						<div class="form-group">
							<label class="control-label col-md-3">广告位备注</label>
							<div class="col-md-9">
								<?php echo widget('Form/textarea', array('data[space_description]', 'space_description', $space['space_description'])); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">是否启用</label>
							<div class="col-md-4">
								<?php echo widget('Form/radio', array(config('ad.space_enable'),'data[space_enable]', $space['space_enable'])); ?>
							</div>
						</div>
					</div>
					<div class="form-actions fluid">
						<div class="col-md-offset-3 col-md-9">
              <button class="btn green" data-loading-text="正在处理..." data="submit" id="sub"><i class="fa fa-check"></i>保存</button>
              <button href="<?php echo url('ad/spaceList'); ?>" class="ajaxify btn default" data="button" id="gobackBtn">返回</button>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
		<!-- END VALIDATION STATES-->
	</div>
</div>

<SCRIPT data="text/javascript">

  /*
  * 表单校验 提交
  */
  jQuery(document).ready(function($){
            
      $.validator.setDefaults({
          submitHandler: function(form){
              var $btn = $("#sub").button('loading');
              //提交表单
              $.post("<?php echo url('ad/spaceEdit'); ?>", $('#form1').serialize(), function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    App.doShow("<?php echo url('ad/spaceList'); ?>");
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
          errorPlacement: function(error, element) { //配置错误广告输出
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
    
</SCRIPT>

