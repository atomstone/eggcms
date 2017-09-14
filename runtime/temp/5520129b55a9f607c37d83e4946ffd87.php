<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"E:\www\zbzx\public/../application/admin\view\content\categoryList.html";i:1488556634;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>栏目管理<i class="fa fa-angle-right"></i></li>
    <li>栏目列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<!--检索 start-->
  	<form id="searchForm" action="<?php echo url('content/categoryList'); ?>">
  	</form>
<!--检索 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i>列表</div>
        <div class="actions">
          <a href="<?php echo url('Content/categoryAdd'); ?>" class="toEditForm btn blue"><i class="fa fa-plus"></i> 添加栏目</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
          <table class="table table-striped table-bordered table-hover" id="list-table">
            <thead>
              <tr>
                <th style="width:10%;">排序号</th>
                <th class="sorting" style="width:10%;">catid</th>
                <th class="sorting" style="width:25%;">栏目名称</th>
                <th class="sorting" style="width:10%;">栏目类型</th>
                <th class="sorting" style="width:10%;">所属模型</th>
                <th class="sorting" style="width:25%;">管理操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($categorys) || $categorys instanceof \think\Collection): $i = 0; $__LIST__ = $categorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              <tr id="<?php echo $v['catid']; ?>" parent="<?php echo $v['parentid']; ?>">
                <td><input type="text" name="setorder[<?php echo $v['catid']; ?>]" value="<?php echo $v['listorder']; ?>" style="width:35px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
                <td><?=$v['catid'];?></td>
                <td><img src="<?php echo \think\Config::get('web_res_root'); ?>img/close.gif"  onclick="displayData(this);" alt="关闭" border="0" style="margin-left:<?php echo $v['level']; ?>em;cursor:pointer;"/><span style="<?=$v['parentid'] == 0 ? 'font-weight:bold;' : '';?>"><?php echo $v['catname']; ?></span></td>
                <td><?php echo config('category.type')[$v['type']]; ?></td>
                <td><?=isset($v['model']) ? $v['model']['name'] : '';?>[<?=$v['modelid'];?>]</td>
                <td>
                  <a class="toEditForm btn default btn-xs green" href="<?php echo url('Content/categoryAdd', array('parentid' => $v['catid'],'modelid' => $v['modelid'])); ?>">添加子栏目 </a>
                  <a class="toEditForm btn default btn-xs purple" href="<?php echo url('Content/categoryEdit', array('catid' => $v['catid'])); ?>"><i class="fa fa-edit"></i>修改 </a>
                  <a class="categoryDel" val="<?php echo $v['catid']; ?>" class="btn default btn-xs black" href="#this"><i class="fa fa-trash-o"></i> 删除</a>
                </td>
              </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
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
  });
			

  //排序
  $("#btn-setorder").click(function(){
      var $btn = $("#btn-setorder").button('loading');
      $.post("<?php echo url('Content/categorySetOrder'); ?>", $('#form1').serialize(), function(data){
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


  //删除栏目
  $(".categoryDel").click(function(){
      catid = $(this).attr('val');
      art.dialog({
          lock: true,
          background: '#600', // 背景色
          opacity: 0.87,	// 透明度
          icon: 'question2',
          content: "您确定要删除此栏目？",
          cancelVal: '取消',
          ok: function () {
              $.post("<?php echo url('Content/categoryDel'); ?>", {catid:catid}, function(data){
                  data = eval(data);
                  if(data.code == 1){
                	    success(data.msg);
                	    App.doShow("<?php echo url('Content/categoryList'); ?>");
                  }else{
                      error(data.msg);
                  }
              });
          },
          cancel: true //为true等价于function(){}
      });
            
  });

  //折叠展示
  function displayData(_self){
      if(_self.alt == "关闭"){
          jqshow($(_self).parent().parent().attr('id'), 'hide');
          $(_self).attr("src", "<?php echo \think\Config::get('web_res_root'); ?>img/open.gif");
          _self.alt = '打开';
      }else{
          jqshow($(_self).parent().parent().attr('id'), 'show');
          $(_self).attr("src", "<?php echo \think\Config::get('web_res_root'); ?>img/close.gif");
          _self.alt = '关闭';
      }
  }

  function jqshow(id,isshow){
      var obj = $("#list-table tr[parent='"+id+"']");
      if(obj.length > 0){
          obj.each(function(i){
              jqshow($(this).attr('id'),isshow);
          });
          if(isshow == 'hide'){
              obj.hide();
          }else{
              obj.show();
          }
      }
  }

</script>

