<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"E:\www\zbzx\public/../application/admin\view\position\positionAdd.html";i:1488556640;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>推荐位管理<i class="fa fa-angle-right"></i></li>
    <li><a href="">推荐位列表</a><i class="fa fa-angle-right"></i></li>
    <li>添加推荐位</li>
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
					<i class="fa fa-reorder"></i>添加推荐位
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
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3">推荐位名称</label>
							<div class="col-md-4">
								<?php echo widget('Form/input', array('data[name]', 'name')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">栏目</label>
							<div class="col-md-9">
                <input id="citySel" type="text" readonly value="" class="form-control" onclick="showMenu();" />
                <input id="catid" name="data[catid]" type="hidden" value="" />
                <span class="help-block">如未选此项，则全栏目通用</span>
                <div id="menuContent" class="menuContent" style="display:none; position: absolute;">
                  <ul id="treeDemo" class="ztree" style="margin-top:0; width:300px; height: 400px;"></ul>
                </div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">最大保存条数</label>
							<div class="col-md-4">
							  <?php echo widget('Form/inputnumber', array('data[maxnum]', 'maxnum')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">排序</label>
							<div class="col-md-4">
							  <?php echo widget('Form/inputnumber', array('data[listorder]', 'listorder')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">对应图片</label>
							<div class="col-md-4">
							  <?php echo widget('Form/upOneFile', array('data[thumb]', 'thumb')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">启用状态</label>
							<div class="col-md-4">
								<?php echo widget('Form/radio', array(config('position.enable'),'data[enable]', 1)); ?>
              </div>
						</div>

					</div>
					<div class="form-actions fluid">
						<div class="col-md-offset-3 col-md-9">
              <button class="btn green" data-loading-text="正在处理..." type="submit" id="sub"><i class="fa fa-check"></i>保存</button>
              <button href="<?php echo url('position/positionList'); ?>" class="ajaxify btn default" type="button" id="gobackBtn">返回</button>
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

		var setting = {
			check: {
				enable: true,
				chkboxType: {"Y":"", "N":""}
			},
			view: {
				dblClickExpand: false
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
				beforeClick: beforeClick,
				onCheck: onCheck
			}
		};

		var zNodes =<?php echo $zNodes; ?>;

		function beforeClick(treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			zTree.checkNode(treeNode, !treeNode.checked, null, true);
			return false;
		}
		
		function onCheck(e, treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
			nodes = zTree.getCheckedNodes(true),
			v = "";
			catids = '';
			for (var i=0, l=nodes.length; i<l; i++) {
				v += nodes[i].name + ",";
				catids += nodes[i].id + ",";
			}
			if (v.length > 0 ) v = v.substring(0, v.length-1);
			if (catids.length > 0 ) catids = catids.substring(0, catids.length-1);
			var cityObj = $("#citySel");
			cityObj.attr("value", v);
			$("#catid").attr("value", catids);
		}

		function showMenu() {
			var cityObj = $("#citySel");
			var cityOffset = $("#citySel").offset();
      //$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");
			$("#menuContent").css({left:"15px", top:"33px", "z-index":"100", border: "1px solid #617775", "background-color": '#F0F6E4',"overflow-y":'auto'}).slideDown("fast");

			$("body").bind("mousedown", onBodyDown);
		}
		function hideMenu() {
			$("#menuContent").fadeOut("fast");
			$("body").unbind("mousedown", onBodyDown);
		}
		function onBodyDown(event) {
			if (!(event.target.id == "menuBtn" || event.target.id == "citySel" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
				hideMenu();
			}
		}

		$(document).ready(function(){
			$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		});
	
	
	
	
  /*
  * 表单校验 提交
  */
  jQuery(document).ready(function($){
            
      $.validator.setDefaults({
          submitHandler: function(form){
              var $btn = $("#sub").button('loading');
              //提交表单
              $.post("<?php echo url('position/positionAdd'); ?>", $('#form1').serialize(), function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    App.doShow("<?php echo url('position/positionList'); ?>");
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
    
</SCRIPT>

