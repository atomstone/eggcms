<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"E:\www\zbzx\public/../application/admin\view\widget\linksMenu.html";i:1488556637;}*/ ?>

<div class="row" style="margin-bottom:10px;">
  <div class="col-md-12">
      <a href="<?php echo url('Links/linksList'); ?>" class="toEditForm btn  <?=($tag==1)?' btn-primary':'';?>">友情链接管理</a>
      <a href="<?php echo url('Links/typeList'); ?>" class="toEditForm btn  <?=($tag==0)?' btn-primary':'';?>">分类管理</a>
	</div>
</div>



      

