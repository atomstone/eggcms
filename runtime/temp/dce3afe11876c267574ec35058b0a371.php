<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"E:\www\zbzx\public/../application/admin\view\content\articleList.html";i:1505377934;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>内容管理<i class="fa fa-angle-right"></i></li>
    <li><?php echo $category['catname']; ?>-栏目内容管理</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<!--检索 start-->
<div class="row" style="margin-bottom:10px;">
  <div class="col-md-12">
	<form id="searchForm" action="<?php echo url('Content/articleList', array('catid' => $category['catid'])); ?>">
	  <?php echo widget('Form/getArticleListSearchForm', array($model['modelid'],$search)); ?>	  
	  <?php echo widget('Form/getArticleListSearchPosition', array($category['catid'],$search)); ?>	  
	  <input name="p" id="p" type="hidden" value="<?php echo \think\Request::instance()->param('p'); ?>">
    <?php echo widget('Form/pageSelect', array(\think\Request::instance()->param('pagesize'))); ?>
    <input type="hidden" name="ajaxShowAreaClass" id="ajaxShowAreaClass" value="contentRightView">
    <input type="hidden" name="orderby" id="orderby" value="">
    <input type="hidden" name="dir" id="dir" value="">
    <button type="button" data-loading-text="正在查询..." id="btnSearchForm" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> 查询</button>
    <button type="button" data-loading-text="正在重置..." id="btnRetSearchForm" class="btn btn-sm filter-submit margin-bottom"><i class="fa fa-refresh"></i>重置</button>
	</form>
	</div>
</div>
<!--检索 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">
      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i>列表</div>
        <div class="actions">
          <a href="<?php echo url('Content/articleAdd', array('catid' => $category['catid'])); ?>" class="toContentEditForm btn blue"><i class="fa fa-plus"></i> 添加</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
        <input type="hidden" name="catid" value="<?php echo $category['catid']; ?>">
        <table class="table  table-bordered table-hover" id="sample_1">
          <thead>
            <tr>
              <th class="sorting" style="width:5%;">排序</th>
              <th class="sorting" style="width:5%;">id</th>
              <th class="sorting" style="width:40%;"><?=$listFiels['title']['name'];?></th>
              <?php
              unset($listFiels['title']);
              foreach($listFiels as $v){
                  echo '<th class="sorting" style="width:20%;">'.$v['name'].'</th>';
              }
              ?>
              <th class="sorting" style="width:15%;">管理操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($articles as $k => $v){ ?>
            <tr class="odd gradeX">
              <td><input type="text" name="setorder[<?php echo $v['id']; ?>]" value="<?php echo $v['listorder']; ?>" style="width:40px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
              <td><?php echo $v['id']; ?></td>
              <td>
                <a href="<?=$currentsite['domain'].url('/index/cms/detail',array('catid'=>$v['catid'], 'articleid'=>$v['id']))?>" target="_blank">
                <?php 
                if(!empty($v['url'])){
                    echo '<i class="fa fa-link"></i>';
                }
                echo $v['title'];
                if($v['stick'] == 1){
                    echo '<span class="badge badge-danger badge-roundless">顶</span>';
                }
                $pos = widget('Common/getPositions', array($siteid, $v['catid'], $v['id']));
                if($pos){
                    echo '<span class="badge badge-primary badge-roundless">荐</span>';
                }
                if(!empty($v['thumb'])){
                    echo '<span class="badge badge-warning badge-roundless">图</span>';
                }
                if( (isset($v['video']) && !empty($v['video'])) || (isset($v['videooutlink']) && !empty($v['videooutlink'])) ){
                    echo '<span class="badge badge-roundless">视</span>';
                }
                ?>
                </a>
              </td>
              <?php
              foreach($listFiels as $f){
                  echo '<td>';
                  if(in_array($f['formtype'], array("text","title","keyword","number","textarea","editor"))) {
                      echo $v[$f['field']];
                  }elseif(in_array($f['formtype'], array("date"))){
                      echo date('Y-m-d', $v[$f['field']]);
                  }elseif(in_array($f['formtype'], array("datetime"))){
                      echo date('Y-m-d H:i:s', $v[$f['field']]);
                  }elseif(in_array($f['formtype'], array("box"))){
                      $setting = json_decode($f['setting'],true);
                      if($setting['boxtype'] == 'radio'){
                          echo isset($setting['options'][$v[$f['field']]]) ? $setting['options'][$v[$f['field']]] : '';  
                      }elseif($setting['boxtype'] == 'select'){
                          echo isset($setting['options'][$v[$f['field']]]) ? $setting['options'][$v[$f['field']]] : '';  
                      }elseif($setting['boxtype'] == 'checkbox'){
                      }
                  }elseif($f['formtype'] == 'image'){
                      echo "<img src='".$v[$f['field']]."' style='width:60px;height:30px'>";
                  }
                  echo '</td>';
              }
              ?>
              <td>
                <a class="toContentEditForm btn default btn-xs purple" href="<?php echo url('Content/articleEdit', array('articleid' => $v['id'], 'catid' => $category['catid'])); ?>"><i class="fa fa-edit"></i>修改 </a>
                <a class="delBtn btn default btn-xs black" catid="<?php echo $v['catid']; ?>" aid="<?php echo $v['id']; ?>" href="#this"><i class="fa fa-trash-o"></i> 删除</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        </form>
        <div class="form-actions">
          <table border="0" style="width:100%;">
            <tr>
              <td><button id="btn-setorder" data-loading-text="正在处理排序..."  type="button" class="btn red">批量排序</button></td>
              <td style="text-align:right;"><?php echo $page->show(); ?></td>
            </tr>
          </table>
          
          
        </div>
      </div>        
    </div>
  </div>
</div>


			
<script>
  
  jQuery(document).ready(function() {
      TableList.init();
  });
  
  //排序
  $("#btn-setorder").click(function(){
      var $btn = $("#btn-setorder").button('loading');
      $.post("<?php echo url('content/articleSetOrder'); ?>", $('#form1').serialize(), function(data){
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

  //删除
  $(".delBtn").click(function(){
      catid = $(this).attr('catid');
      aid = $(this).attr('aid');
      art.dialog({
          lock: true,
          background: '#600', // 背景色
          opacity: 0.87,	// 透明度
          icon: 'question2',
          content: "您确定要删除此文章？",
          cancelVal: '取消',
          ok: function () {
              $.post("<?php echo url('content/articleDel'); ?>", {catid:catid, aid:aid}, function(data){
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