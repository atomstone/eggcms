<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"E:\www\zbzx\public/../application/admin\view\content\contentIndex.html";i:1488556635;}*/ ?>

<table style="width:100%;" border="0">
  <tr>
    <td style="width:10%;vertical-align:top;"><ul id="treeDemo" class="ztree"></ul></td>
    <td style="width:90%;vertical-align:top;">
      <div id="contentRightView" class="contentRightView">
        <span style="text-align:center;font-weight:bold;font-size:16px;color:green;">
          <span><p><br><p><br><p><br><p><br><i style="color:green;font-size:24px;" class="fa fa-hand-o-left"></i>&nbsp;&nbsp;&nbsp;请点击左侧栏目进行操作</span>
        </span>         
      </div>
    </td>
  </tr>
</table>

<SCRIPT type="text/javascript">
  
      var setting = {
          data: {
              simpleData: {
                  enable: true
              }
          },
          callback: {
		          onClick: zTreeOnClick
	        }
      };
      var zNodes =[
      <?php 
      foreach($categorys as $k => $v){ 
          $iconClose = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/10.png";
          $iconOpen = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/11.png";
          if($v['type'] == 0){
              $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/12.png";
              $url =   url('content/articleList', array('catid'=>$v['catid']));
          }elseif($v['type'] == 1){
              $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/2.png";
              $url =   url('content/pageEdit', array('catid'=>$v['catid']));
          }elseif($v['type'] == 2){
              $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/3.png";
              $url =   url('content/linkEdit', array('catid'=>$v['catid']));
          }
          //if($v['parentid'] == 0){
          //    echo '{ id:'.$v['catid'].', pId:'.$v['parentid'].', name:"'.$v['catname'].'", open:true, iconClose:"'.$iconClose.'", iconOpen:"'.$iconOpen.'" },';
          //}else{
              echo '{ id:'.$v['catid'].', pId:'.$v['parentid'].', name:"'.$v['catname'].'", tourl:"'.$url.'", open:true, icon:"'.$ico.'", iconClose:"'.$iconClose.'", iconOpen:"'.$iconOpen.'" },';
          //}
      }
      ?>
      ];

      function zTreeOnClick(event, treeId, treeNode) {
          var zTree = $.fn.zTree.getZTreeObj("treeDemo");
		      //zTree.expandNode(treeNode);
		    	//如果没有下级
		    	//if(treeNode.isParent == false){
              $("#contentRightView").html('<div class="page-loading"><img src="/static/admin/assets/img/loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>正在加载......</span></div>');
              $.ajax({
                  type: "GET",
                  cache: false,
                  url: treeNode.tourl,
                  dataType: "html",
                  success: function (res) {
                      $("#contentRightView").html(res);
                  },
                  error: function (xhr, ajaxOptions, thrownError) {
                      $("#contentRightView").html('<h4>Could not load the requested content.</h4>');
                  }
              });
          //}
      }

      $(document).ready(function(){
          $.fn.zTree.init($("#treeDemo"), setting, zNodes);
          var zTree = $.fn.zTree.getZTreeObj("treeDemo");
      });
      
      //2秒钟后收起左侧菜单
      function packupLeft(){
          $(".sidebar-toggler").click();
      }
      //setTimeout("packupLeft();",2000);
    
</SCRIPT>