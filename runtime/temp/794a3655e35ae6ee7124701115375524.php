<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"E:\www\zbzx\public/../application/admin\view\menu\menuList.html";i:1488556633;}*/ ?>
<!--导航 start-->
<div class="row">
  <div class="col-md-12">
    <ul class="page-breadcrumb breadcrumb">
    <li><i class="fa fa-home"></i>菜单管理<i class="fa fa-angle-right"></i></li>
    <li><?php echo $type['typename']; ?> - 菜单列表</li>
    </ul>
  </div>
</div>
<!--导航 end-->

<!--检索 start-->
  	<form id="searchForm" action="<?php echo url('menu/menuList',array('typeid'=>$type['typeid'])); ?>">
  	</form>
<!--检索 end-->

<div class="row">
  <div class="col-md-12">
    <div class="portlet box light-grey">

      <div class="portlet-title">
        <div class="caption"><i class="fa fa-case"></i><?php echo $type['typename']; ?> - 列表</div>
        <div class="actions">
          <a href="<?php echo url('menu/menuAdd',array('typeid' => $type['typeid'])); ?>" class="toEditForm btn blue"><i class="fa fa-plus"></i> 添加菜单</a>
          <a href="<?php echo url('menu/typeList'); ?>" class="ajaxify btn btn-default">返回分类列表</a>
        </div>
      </div>
      <div class="portlet-body">
        <form id="form1">
          <table class="table table-striped table-bordered table-hover" id="list-table">
            <thead>
              <tr>
                <th style="width:10%;">排序号</th>
                <th style="width:20%;">菜单名称</th>
                <th style="width:20%;">链接类型</th>
                <th style="width:20%;">打开方式</th>
                <th style="width:30%;">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($menus) || $menus instanceof \think\Collection): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              <tr id="<?php echo $v['menuid']; ?>" parent="<?php echo $v['parentid']; ?>">
                <td><input type="text" name="setorder[<?php echo $v['menuid']; ?>]" value="<?php echo $v['listorder']; ?>" style="width:35px;"  onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" ></td>
                <td><img src="<?php echo \think\Config::get('web_res_root'); ?>img/close.gif"  onclick="displayData(this);" alt="关闭" border="0" style="margin-left:<?php echo $v['level']; ?>em;cursor:pointer;"/>
                  <?php echo $v['menuname']; 
                  if(!empty($v['image'])){
                      echo '<img style="width:30px;weight:20px;" src="'.$v['image'].'">';
                  }
                  ?>
                </td>
                <td><?=config('menu.linkstype')[$v['linkstype']];?></td>
                <td><?=config('menu.target')[$v['target']];?></td>
                <td>
                  <a class="toEditForm btn btn-xs blue" href="<?php echo url('menu/menuAdd', array('parentid' => $v['menuid'],'typeid' => $type['typeid'])); ?>"><i class="fa fa-plus"></i> 新增下级</a>
                  <a class="toEditForm btn default btn-xs purple" href="<?php echo url('menu/menuEdit', array('menuid' => $v['menuid'])); ?>"><i class="fa fa-edit"></i> 编辑 </a>
                  <a class="menuDel" val="<?php echo $v['menuid']; ?>" class="btn default btn-xs black" href="#this"><i class="fa fa-trash-o"></i> 删除</a>
                </td>
              </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
          <div class="form-actions">
            <button id="btn-setorder" data-loading-text="正在处理排序..."  type="button" class="btn red">批量排序</button>
            <a href="<?php echo url('menu/typeList'); ?>" class="ajaxify btn btn-default">返回分类列表</a>
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
      $.post("<?php echo url('menu/menuSetOrder'); ?>", $('#form1').serialize(), function(data){
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

  //删除菜单
  $(".menuDel").click(function(){
      menuid = $(this).attr('val');
      typeid = <?php echo $type['typeid']; ?>;
      art.dialog({
          lock: true,
          background: '#600', // 背景色
          opacity: 0.87,	// 透明度
          icon: 'question2',
          content: "您确定要删除此菜单？",
          cancelVal: '取消',
          ok: function () {
              $.post("<?php echo url('menu/menuDel'); ?>", {menuid:menuid,typeid:typeid}, function(data){
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
