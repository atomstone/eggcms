<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"E:\www\zbzx\public/../application/admin\view\position\positionList.html";i:1488556640;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>推荐位管理<i class="fa fa-angle-right"></i></li>
    <li>推荐位列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i>列表</div>
        <div class="actions">
          <a href="<?php echo url('position/positionAdd'); ?>" class="toEditForm btn blue"><i class="fa fa-plus"></i> 添加推荐位</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
          <thead>
            <tr>
              <th class="sorting" style="width:5%;">排序</th>
              <th class="sorting" style="width:5%;">posid</th>
              <th class="sorting" style="width:30%;">推荐位名称</th>
              <th class="sorting" style="width:10%;">保存条数</th>
              <th class="sorting" style="width:25%;">栏目</th>
              <th class="sorting" style="width:10%;">状态</th>
              <th class="sorting" style="width:15%;">管理操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($positions as $k => $v){ ?>
            <tr class="odd gradeX">
              <td><input type="text" name="setorder[<?php echo $v['posid']; ?>]" value="<?php echo $v['listorder']; ?>" style="width:35px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
              <td><?=$v['posid'];?></td>
              <td><?=$v['name'];?></td>
              <td><?=$v['maxnum'];?></td>
              <td><?=$v['catid'];?></td>
              <td><?=config('position.enable')[$v['enable']];?></td>
              <td>
                <a class="toEditForm btn default btn-xs purple" href="<?php echo url('position/dataList', array('posid' => $v['posid'])); ?>">信息管理 </a>
                <a class="toEditForm btn default btn-xs purple" href="<?php echo url('position/positionEdit', array('posid' => $v['posid'])); ?>"><i class="fa fa-edit"></i>修改 </a>
                <!--
                <a class="btn default btn-xs black" href=""><i class="fa fa-trash-o"></i> 删除</a>
                -->
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
  
  //排序
  $("#btn-setorder").click(function(){
      var $btn = $("#btn-setorder").button('loading');
      $.post("<?php echo url('position/positionSetOrder'); ?>", $('#form1').serialize(), function(data){
          data = eval(data);
          if(data.code == 1){
        	    success(data.msg);
        	    App.doShow("<?php echo url('position/positionList'); ?>");
          }else{
              error(data.msg);
          }
          $btn.button('reset');
      });
  });
  

</script>