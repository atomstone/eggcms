<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"E:\www\zbzx\public/../application/admin\view\links\linksList.html";i:1488556638;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>友情管理<i class="fa fa-angle-right"></i></li>
    <li>友情链接列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<?php echo widget('Common/linksMenu',array('tag'=>1)); ?>


<!--检索 start-->
<div class="row" style="margin-bottom:10px;">
  <div class="col-md-12">
  	<form id="searchForm" action="<?php echo url('Links/linksList'); ?>">
  	  <?php echo widget('Form/linksTypeSelect', array($siteid, 'typeid', 'typeid',isset($typeid)?$typeid:'')); ?>
  	</form>
	</div>
</div>
<!--检索 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i>友情链接列表</div>
        <div class="actions">
          <a href="<?php echo url('Links/linksAdd'); ?>" class="toEditForm btn btn-primary">添加友情链接</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
          <thead>
            <tr>
              <th class="sorting" style="width:8%;">排序号</th>
              <th class="sorting" style="width:8%;">linksid</th>
              <th class="sorting" style="width:8%;">分类</th>
              <th class="sorting" style="width:20%;">网站名称</th>
              <th class="sorting" style="width:20%;">图片LOGO</th>
              <th class="sorting" style="width:20%;">网站网址</th>
              <th class="sorting" style="width:20%;">管理操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($links as $k => $v){ ?>
            <tr class="odd gradeX">
              <td><input type="text" name="setorder[<?php echo $v['linksid']; ?>]" value="<?php echo $v['listorder']; ?>" style="width:35px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
              <td><?=$v['linksid'];?></td>
              <td><?=$v['typeid'];?></td>
              <td><?=$v['name'];?></td>
              <td><img src="<?=$v['logo'];?>" style="width:100px;height:50px;"></td>
              <td><?=$v['url'];?></td>
              <td>
                <a class="toEditForm btn default btn-xs purple" href="<?php echo url('Links/linksEdit', array('linksid' => $v['linksid'])); ?>"><i class="fa fa-edit"></i>修改 </a>
                <a class="btn default btn-xs black" href=""><i class="fa fa-trash-o"></i> 删除</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="form-actions">
          <button id="btn-setorder" data-loading-text="正在处理排序..."  type="button" class="btn red">批量排序</button>
        </div>
        </form>
      </div>        
    </div>
  </div>
</div>


			
<script>
  
  jQuery(document).ready(function() {
      TableList.init();
        
      //按类型筛选
      $("#typeid").change(function(){
          TableList.listRefresh();
      });
  });
  
  //排序
  $("#btn-setorder").click(function(){
      var $btn = $("#btn-setorder").button('loading');
      $.post("<?php echo url('links/linksSetOrder'); ?>", $('#form1').serialize(), function(data){
          data = eval(data);
          if(data.code == 1){
        	    success(data.msg);
        	    TableList.listRefresh();
          }else{
              error(data.msg);
          }
          $btn.button('reset');
      });
  });

  
</script>