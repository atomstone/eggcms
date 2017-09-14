<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"E:\www\zbzx\public/../application/admin\view\position\dataList.html";i:1488556640;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>推荐位管理<i class="fa fa-angle-right"></i></li>
    <li>数据列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i><?php echo $position['name']; ?>-列表</div>
        <div class="actions">
          <a href="<?php echo url('position/positionList'); ?>" class="toEditForm btn default"><i class="fa fa-plus"></i> 返回</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
          <thead>
            <tr>
              <th class="sorting" style="width:5%;">选择</th>
              <th class="sorting" style="width:5%;">排序</th>
              <th class="sorting" style="width:5%;">dataid</th>
              <th class="sorting" style="width:20%;">栏目</th>
              <th class="sorting" style="width:10%;">文章id</th>
              <th class="sorting" style="width:40%;">标题</th>
              <th class="sorting" style="width:10%;">管理操作</th> 
            </tr>
          </thead>
          <tbody>
            <?php 
            foreach($datas as $k => $v){  
                $data = json_decode($v['data'],true);
            ?>
            <tr class="odd gradeX">
              <td><input class="infocheck" type="checkbox" name="dataids[]" value="<?php echo $v['dataid'];?>" /></td>
              <td><input type="text" name="setorder[<?php echo $v['dataid']; ?>]" value="<?php echo $v['listorder']; ?>" style="width:35px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
              <td><?=$v['dataid'];?></td>
              <td><?=$v['catid'];?></td>
              <td><?=$v['aid'];?></td>
              <td>
                <?php 
                echo $data['title'];
                if(!empty($v['thumb'])){
                  echo '<img src="'.$v['thumb'].'" style="width:20px;height:20px;">';
                }
                ?>
              </td>
              <td>
                <a class="toEditForm btn default btn-xs purple" href="<?php echo url('position/dataEdit', array('dataid' => $v['dataid'])); ?>"><i class="fa fa-edit"></i>修改 </a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="form-actions">
          <button id="btn-setorder" data-loading-text="正在处理排序..."  type="button" class="btn red">批量排序</button>
          <button id="btn-del" data-loading-text="正在处理移除..."  type="button" class="btn red">批量移除</button>
          <button href="<?php echo url('position/positionList'); ?>" class="ajaxify btn default" type="button" id="gobackBtn">返回</button>
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
      $.post("<?php echo url('position/dataSetOrder'); ?>", $('#form1').serialize(), function(data){
          data = eval(data);
          if(data.code == 1){
        	    success(data.msg);
        	    App.doShow("<?php echo url('position/dataList',array('posid'=>$position['posid'])); ?>");
          }else{
              error(data.msg);
          }
          $btn.button('reset');
      });
  });
  
  //删除
  $("#btn-del").click(function(){
      art.dialog({
          lock: true,
          background: '#600', // 背景色
          opacity: 0.87,	// 透明度
          icon: 'question2',
          content: "您确定要执行移除操作？",
          cancelVal: '取消',
          ok: function () {
              var $btn = $("#btn-del").button('loading');
              $.post("<?php echo url('position/dataDel'); ?>", $('#form1').serialize(), function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    App.doShow("<?php echo url('position/dataList',array('posid'=>$position['posid'])); ?>");
                  }else{
                      error(data.msg);
                  }
                  $btn.button('reset');
              });
          },
          cancel: true //为true等价于function(){}
      });
  });
</script>