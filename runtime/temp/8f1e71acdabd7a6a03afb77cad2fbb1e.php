<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"E:\www\zbzx\public/../application/admin\view\content\articleEdit.html";i:1488556634;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>内容管理<i class="fa fa-angle-right"></i></li>
    <li><a href=""><?php echo $category['catname']; ?>栏目</a><i class="fa fa-angle-right"></i></li>
    <li>编辑内容</li>
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
					<i class="fa fa-reorder"></i>编辑文章
				</div>

        <div class="actions">
          <button class="btn blue subBtn" data-loading-text="正在处理..." onclick="javascript:$('#sub').click();"><i class="fa fa-check"></i>保存</button>
          <a href="<?php echo url('Content/articleList', array('catid' => $category['catid'])); ?>" class="ajaxifyContent btn default"><i class="fa fa-reply"></i> 返回</a>        
        </div>


			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="#this" id="form1" class="form-horizontal form-row-seperated">
					<div class="form-body">
						 <input type="hidden" name="articleid" id="articleid" value="<?php echo $article['id']; ?>">					  
             <div class="form-group"> 
              <label class="control-label col-md-2" style="font-weight:bold;">栏目</label>
              <div class="col-md-9">
								<input type="hidden" name="catid" id="catid" value="<?php echo $category['catid']; ?>">
								<?php echo $category['catname']; ?>
              </div>
            </div>

					  <?php foreach($fields as $k => $v){ ?>
					  <?php echo widget('Form/getArticleForm', array($v)); } ?>

            <div class="form-group" style="color:red;"> 
              <label class="control-label col-md-2" style="color:#000000;font-weight:bold;">添加相关</label>
              <div class="col-md-9">
								<?php echo widget('Form/getRelation', array($siteid,$category['catid'],$article['id'])); ?>
              </div>
            </div>

            <div class="form-group" style="color:red;"> 
              <label class="control-label col-md-2" style="color:#000000;font-weight:bold;">推荐位</label>
              <div class="col-md-9">
								<?php echo widget('Form/positionChecks', array($siteid, $category['catid'], $article['id'])); ?>
              </div>
            </div>


					</div>
					<div class="form-actions fluid">
						<div class="col-md-offset-3 col-md-9">
              <button class="btn green subBtn" data-loading-text="正在处理..." type="submit" id="sub"><i class="fa fa-check"></i>保存</button>
              <button href="<?php echo url('Content/articleList', array('catid' => $category['catid'])); ?>" class="ajaxifyContent btn default" type="button" id="gobackBtn"><i class="fa fa-reply"></i>返回</button>
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

  $("#relationBut").click(function(){
      var url = $(this).attr('url');
      art.dialog.open(
          url, 
          {
              id : 'relationList',
              drag: false,
              resize: false,
              fixed: true,
              lock: true,
              background: '#600', // 背景色
              title : '添加相关',
              width: 800,
              height: 400,
              top:60,
              left:300,
              opacity: 0.87,	// 透明度
              cancelVal: '关闭',
              cancel: true
          }
      );
  });
  
  /*
  * 表单校验 提交
  */
  jQuery(document).ready(function($){
      

      $.validator.setDefaults({
          submitHandler: function(form){
              var $btn = $(".subBtn").button('loading');
              //提交表单
              $.post("<?php echo url('Content/articleEdit'); ?>", $('#form1').serialize(), function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    App.doContentShow("<?php echo url('Content/articleList', array('catid' => $category['catid'])); ?>");
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
                //"data[test]": {required:"请填写"},
                  }
      });
      
  });

</SCRIPT>
