<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:59:"E:\www\zbzx\public/../application/admin\view\ad\adList.html";i:1488556639;}*/ ?>

<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>广告位管理<i class="fa fa-angle-right"></i></li>
    <li> <?php echo $space['space_name']; ?> <i class="fa fa-angle-right"></i> </li>
    <li>广告列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<!--检索 start-->
<div class="row" style="margin-bottom:10px;">
  <div class="col-md-12">
  	<form id="searchForm" action="<?php echo url('ad/adList',array('space_id'=>$space['space_id'])); ?>">
  	</form>
	</div>
</div>
<!--检索 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i><?php echo $space['space_name']; ?> - 广告列表</div>
        <div class="actions">
          <a href="<?php echo url('ad/adAdd', array('space_id'=>$space['space_id'])); ?>" class="toEditForm btn btn-primary">添加广告</a>
          <a href="<?php echo url('ad/spaceList'); ?>" class="ajaxify btn btn-default">返回广告位列表</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
          <thead>
            <tr>
              <th class="sorting" style="width:10%;">排序号</th>
              <th class="sorting" style="width:10%;">ad_id</th>
              <th class="sorting" style="width:20%;">广告名称</th>
              <th class="sorting" style="width:20%;">有效时间</th>
              <th class="sorting" style="width:10%;">点击次数</th>
              <th class="sorting" style="width:10%;">启用状态</th>
              <th class="sorting" style="width:20%;">管理操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($ads as $k => $v){ ?>
            <tr class="odd gradeX">
              <td><input type="text" name="setorder[<?php echo $v['ad_id']; ?>]" value="<?php echo $v['ad_listorder']; ?>" style="width:35px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
              <td><?=$v['ad_id'];?></td>
              <td><?=$v['ad_name'];?></td>
              <td><?=date('Y-m-d H:i:s', $v['ad_startdate']);?> 至<br> <?=date('Y-m-d H:i:s', $v['ad_enddate']);?></td>
              <td><?=$v['ad_clicks'];?></td>
              <td><?=config('ad.ad_enable')[$v['ad_enable']];?></td>
              <td>
                <a class="toEditForm btn default btn-xs purple" href="<?php echo url('ad/adEdit', array('ad_id' => $v['ad_id'])); ?>"><i class="fa fa-edit"></i>修改 </a>
                <a class="adDel" val="<?php echo $v['ad_id']; ?>" class="btn default btn-xs black" href="#this"><i class="fa fa-trash-o"></i> 删除</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="form-actions">
          <button id="btn-setorder" data-loading-text="正在处理排序..."  type="button" class="btn red">批量排序</button>
          <a href="<?php echo url('ad/spaceList'); ?>" class="ajaxify btn btn-default">返回广告位列表</a>
        </div>
        </form>
      </div>        
    </div>
  </div>
</div>


			
<script>
  
    //删除广告
  $(".adDel").click(function(){
      ad_id = $(this).attr('val');
      art.dialog({
          lock: true,
          background: '#600', // 背景色
          opacity: 0.87,	// 透明度
          icon: 'question2',
          content: "您确定要删除此广告？",
          cancelVal: '取消',
          ok: function () {
              $.post("<?php echo url('ad/adDel'); ?>", {ad_id:ad_id}, function(data){
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
  
  jQuery(document).ready(function() {
      TableList.init();      
  });

  //排序
  $("#btn-setorder").click(function(){
      var $btn = $("#btn-setorder").button('loading');
      $.post("<?php echo url('ad/adSetOrder'); ?>", $('#form1').serialize(), function(data){
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

