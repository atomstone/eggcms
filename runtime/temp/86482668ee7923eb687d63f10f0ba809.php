<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"E:\www\zbzx\public/../application/admin\view\ad\spaceList.html";i:1488556639;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>广告位管理<i class="fa fa-angle-right"></i></li>
    <li>广告位列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->


<!--检索 start-->
  	<form id="searchForm" action="<?php echo url('ad/spaceList'); ?>">
  	</form>
<!--检索 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i>广告位列表</div>
        <div class="actions">
          <a href="<?php echo url('ad/spaceAdd'); ?>" class="toEditForm btn btn-primary">添加广告位</a>
        </div>
      </div>
  
      <div class="portlet-body">
        <form id="form1">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
          <thead>
            <tr>
              <th class="sorting" style="width:8%;">space_id</th>
              <th class="sorting" style="width:20%;">广告位名称</th>
              <th class="sorting" style="width:10%;">类型</th>
              <th class="sorting" style="width:10%;">宽高</th>
              <th class="sorting" style="width:25%;">调用代码</th>
              <th class="sorting" style="width:8%;">启用</th>
              <th class="sorting" style="width:20%;">管理操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($spaces as $k => $v){ ?>
            <tr class="odd gradeX">
              <td><?php echo $v['space_id']; ?></td>
              <td><?php echo $v['space_name']; ?></td>
              <td><?=config('ad.space_type')[$v['space_type']];?></td>
              <td><?php echo $v['space_width']; ?> X <?php echo $v['space_height']; ?></td>
              <td>
                {:widget('Ad/showAd', array($siteid, <?=$v['space_id'];?>))}
              </td>
              <td><?=config('ad.space_enable')[$v['space_enable']];?></td>
              <td>
                <a class="toEditForm btn default btn-xs green" href="<?php echo url('ad/adList', array('space_id' => $v['space_id'])); ?>">管理广告 </a>
                <a class="toEditForm btn default btn-xs purple" href="<?php echo url('ad/spaceEdit', array('space_id' => $v['space_id'])); ?>"><i class="fa fa-edit"></i>修改 </a>
                <a class="spaceDel" val="<?php echo $v['space_id']; ?>" class="btn default btn-xs black" href="#this"><i class="fa fa-trash-o"></i> 删除</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        </form>
      </div>        
    </div>
  </div>
</div>



<script>
  
  jQuery(document).ready(function() {
      TableList.init();
  });
  
  
    //删除广告位
  $(".spaceDel").click(function(){
      space_id = $(this).attr('val');
      art.dialog({
          lock: true,
          background: '#600', // 背景色
          opacity: 0.87,	// 透明度
          icon: 'question2',
          content: "您确定要删除此广告位？",
          cancelVal: '取消',
          ok: function () {
              $.post("<?php echo url('ad/spaceDel'); ?>", {space_id:space_id}, function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    TableList.listRefresh();
                  }else{
                      error(data.msg);
                  }
              });
          },
          cancel: true //为true等价于function(){}
      });
            
  });
  
  
</script>
