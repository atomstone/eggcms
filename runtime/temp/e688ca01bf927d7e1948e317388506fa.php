<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"E:\www\zbzx\public/../application/index\view\widget\ad\showAd.html";i:1488556629;}*/ ?>


<?php if($space['space_type'] == 'code'){ 
      	    foreach($ads as $k => $v){ 
      	        $adsetting = json_decode($v['ad_setting'],true);
      	        echo $adsetting['content'];
      	    }
      	    }elseif($space['space_type'] == 'imagechange'){ 
  		    $setting = json_decode($space['space_setting'],true);
  ?>
    <script type="text/javascript">
    //设置
    myFocus.set({
    	id:"myFocus<?=$space['space_id'];?>",//ID
    	pattern:"<?=$setting['myfoucs_pattern'];?>",//风格
    	txtHeight:"<?=$setting['myfoucs_txtHeight'];?>",
    	trigger:"<?=$setting['myfoucs_trigger'];?>",
    	wrap:"<?=$setting['myfoucs_wrap'];?>",
    	loadIMGTimeout:"<?=$setting['myfoucs_loadIMGTimeout'];?>",
    });
    </script>
    <div id="myFocus<?=$space['space_id'];?>" style="width:<?php echo $space['space_width']; ?>px;height:<?php echo $space['space_height']; ?>px;margin:0 auto;"><!--焦点图盒子-->
      <div class="loading"><img src="<?php echo \think\Config::get('patch_public_js'); ?>/myfocus/img/loading.gif" alt="请稍候..." /></div><!--载入画面(可删除)-->
      <div class="pic"><!--图片列表-->
      	<ul>
      	    <?php 
      	    foreach($ads as $k => $v){ 
      	        $adsetting = json_decode($v['ad_setting'], true);
      	    ?>
                <li><a href="#1"><img src="<?php echo $adsetting['src']; ?>" thumb="" alt="<?php echo $adsetting['title']; ?>" text="<?php echo $adsetting['desc']; ?>"/></a></li>
      	    <?php } ?>
      	</ul>
      </div>
    </div>
<?php } ?>


