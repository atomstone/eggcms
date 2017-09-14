<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"E:\www\zbzx\public/../application/index\view\site_1\index\index_default.html";i:1505059092;s:51:"../application/index/view/site_1/public/header.html";i:1505215572;s:51:"../application/index/view/site_1/public/footer.html";i:1505057297;}*/ ?>
<?php
if(isset($seo_title) && !empty($seo_title)){
    $seo_title = $seo_title . '==' . $site['seo_title'];
}else{
    $seo_title = $site['seo_title'];
}
if(isset($seo_keywords) && !empty($seo_keywords)){
    $seo_keywords = $seo_keywords;
}else{
    $seo_keywords = $site['seo_keywords'];
}
if(isset($seo_description) && !empty($seo_description)){
    $seo_description = $seo_description;
}else{
    $seo_description = $site['seo_description'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
	<head>
		<meta name="viewport" content="width=320px, user-scalable=no, initial-scale=1, maximum-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	  <title><?php echo $seo_title; ?></title>
	  <meta name="keywords" content="<?php echo $seo_keywords; ?>">
	  <meta name="description" content="<?php echo $seo_description; ?>" >
	  <link rel="shortcut icon" href="<?php echo $site['ico']; ?>"/>
		<link href="<?php echo $site['patch_css']; ?>/default.css" rel="stylesheet" type="text/css">
	  <!--图片轮换插件 需放在头部调用-->
		<script type="text/javascript" src="<?php echo \think\Config::get('patch_public_js'); ?>/myfocus/js/myfocus-2.0.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $site['patch_js']; ?>/jquery.pack.js"></script>
		<script type="text/javascript" src="<?php echo $site['patch_js']; ?>/jquery.SuperSlide.js"></script>
</head>

<body>

  <div id="wrap">
    <!--
		<div class="top">
			<div class="siteWidth">
				<div class="welcome">欢迎来到教育部教育装备研究与发展中心！</div>
				<div class="topL">
					<a class="a" href="#this" onclick="javascript:alert('demo版暂不支持此功能');">设为首页</a> |
					<a class="b" href="#this" onclick="javascript:alert('demo版暂不支持此功能');">加入收藏</a>
				</div>
			</div>
		</div>
    -->
		<div id="header">
			
			<div id="banner">
				<h1 id="logo"></h1>
				<div class="weather"></div>
				<div class="search">
					<div class="siteSearch">
					  <form action="javascript:alert('demo版暂不支持此功能');" class="searchform" target="_self">
							<input name="searchType" class="searchType" value="" type="hidden">
							<input class="keyword" autocomplete="off" name="keyword" placeholder="请输入搜索关键字" type="text">
							<input class="submit" value="" type="submit">
						</form>
					</div>
				</div>
			</div>
			
			<div id="nav">
				<ul id="mainNav" class="mainNav">
        <?php 
        $menus = widget('Menu/getMenus', array($siteid, 'master'));
        $i = 0; foreach($menus as $k => $v){ 
        	$onthis = '';
        	if(isset($topcat['catid'])){
              $ids =  widget('Content/getChildIds', [$siteid, $topcat['catid']]);
              array_push($ids, $topcat['catid']);
              if(in_array($v['catid'], $ids)){
                  $onthis = 'on1';
              }
          }
        	if(!empty($v['list'])){ ?>
							<li class="li1 <?=$onthis;?>" id="liID<?=$k?>">
								<h4 class="h1" id="hID<?=$k?>">
									<a target="<?php echo $v['target']; ?>" class="a1" id="aID<?=$k?>" href="<?php echo $v['url']; ?>"><?php echo $v['menuname']; ?></a>
							  </h4>
							  <ul class="ul1" id="ulID<?=$k?>">
							  	<?php foreach($v['list'] as $sub){ ?>
								      <li class="li2 first2" id="liID<?=$i?>"><h4 class="h2" id="hID<?=$i?>"><a target="<?php echo $sub['target']; ?>" class="a2" id="aID<?=$i?>" href="<?php echo $sub['url']; ?>"><?php echo $sub['menuname']; ?></a></h4></li>
							  	<?php } ?>
								</ul>
						  </li>
          <?php }else{ ?>
							<li class="li1 <?=$onthis;?>" id="liID<?=$k?>">
								<h4 class="h1" id="hID<?=$k?>"><a target="<?php echo $v['target']; ?>" class="a1" id="aID<?=$k?>" href="<?php echo $v['url']; ?>"><?php echo $v['menuname']; ?></a></h4>
							</li>
          <?php } } ?>
					
				</ul>
			</div>
	  
	  </div>
	  
	<script>
		  jQuery(document).ready(function($){
					var navST;
					var name='mainNav';
					var t=200;
					var type='2';
					var removeOn='false';
					var effect='slide';
					var appendItem = '#';
					var li="#"+name+" li";
					var index = 0; 
			
					if( !$("#"+name+" .li1").hasClass("on1") ){ $("#"+name+" .li1").first().addClass("on1"); } //默认第一个加.on1类
					index = $("#"+name+" .li1").index( $("#"+name+" .on1") );
					$("#"+name).hover(function(){}, function(){ $(".li1",this).removeClass("on1").eq(index).addClass("on1") }); //鼠标离开导航后，回复默认.on1类位置
			
					if(type=='1'){ li="#"+name+" .li1"; }
					if( appendItem!='#'){
					var appendHtml = $(appendItem).html();  $(li).first().append( appendHtml );  $(appendItem).remove(); 
					}
			
					if(type=='3'){ $("#"+name+" .on1").find("ul").first().show(); }
			
					$(li).hover(function(){
						var curItem = $(this);
						var onNum = (curItem.attr("class").split(" "))[0].replace("li","");
						$(li).removeClass("on"+onNum); curItem.addClass("on"+onNum);
						navST = setTimeout(function(){
						
						if( $("ul:first",curItem).css("display") !="block" ){ $(li+" .ul"+onNum).hide(); 
							if( effect=='fade') $("ul:first",curItem).fadeIn(t);
							else $("ul:first",curItem).slideDown(t);
						};
						navST = null;
						},t);
					}, function(){
						if(navST!=null)clearTimeout(navST);
						if(type=='1' || type=='2'){ 
							if( effect=='fade') $(this).find("ul").first().fadeOut(t); 
							else $(this).find("ul").first().slideUp(t); 
						}
						if (removeOn=='true') {  $(this).removeClass("on1"); }
						},t);
		});
	</script>


  
  <link href="<?php echo $site['patch_css']; ?>/index.css" rel="stylesheet" type="text/css">

	<div id="content">
		
		<!-- row1 S -->
		<div class="row1">
			
			<!--焦点轮播图 S-->
			<div class="focusBox" id="focusNews">			
			  <?php echo widget('Ad/showAd', [1,1,5]); ?>
			</div>
			<!--焦点轮播图 E-->
		
			<!-- 新闻动态 S -->
			<dl class="boxGzdt">
				<div class="hd">
				<ul>
				<li class="on">
					<a href="<?php echo url('Cms/index', ['catid'=>14]); ?>" class="more">更多</a>
					<h3 class="title"><a href="<?php echo url('Cms/index', ['catid'=>14]); ?>">工作动态</a></h3>
				</li>
				</ul>
				</div>
				<div class="bd">
					<ul class="infoList infoListA">
		       <?php 
		       $news = widget('Position/getDatas', [1,7]);
		       if($news){
		       foreach($news as $k => $v){ 
		       ?>
		        <li class="first">
							<span class="date"><?php echo $v['inputdate']; ?></span>
							<a href="<?php echo url('Cms/detail',array('catid'=>$v['catid'],'articleid'=>$v['aid'])); ?>" target="_blank" title="<?php echo cut_str($v['title'],25); ?>"><?php echo cut_str($v['title'],25); ?></a>
						</li>
		      <?php 
		      }
		      } 
		      ?>
					</ul>
				</div>
			</dl>
			<!-- 新闻动态 E -->
		
			<!-- 通知公告 S -->
			<dl class="boxTzgg">
				<div class="xxgkHd">
					<h3><a href="#this">教育与装备研究</a></h3>
				</div>
				<div class="bd">
					<ul class="xxgkL">
						<li class="li1 first"><a href="#this">编辑部与编委会</a><i></i></li>
						<li class="li2"><a href="#this">刊物简介</a><i></i></li>
						<li class="li3 last"><a href="#this">栏目设置</a><i></i></li>
					</ul>
					<ul class="links">
						<li class="li1"><a href="#this" class="qlqd"><i></i><span>订阅方式</span></a></li>
						<li class="li2"><a href="#this" class="zrqd"><i></i><span>投稿指南</span></a></li>
					</ul>
				</div>
			</dl>
			<!-- 通知公告 E -->
			
		</div>
		<!-- row1 E -->
	
		<!-- row2 S -->
		<div class="row2">
		<div class="ztzl">
		<div class="hd">
		<a href="#this">专题栏目</a>
		</div>
		<div class="bd">
		<div class="tempWrap" style="overflow:hidden; position:relative;">
			<ul style="width: 3252px; position: relative; overflow: hidden; padding: 0px; margin: 0px; left: -535px;">
				<li class="li7 last clone" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li1 first" style="float: left; width: 261px;"><a class="pic" title="" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li2" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li3" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li4" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li5" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li6" style="float: left; width: 261px;"><a class="pic" title="" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li7 last" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li1 first clone" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li2 clone" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li3 clone" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
				<li class="li4 clone" style="float: left; width: 261px;"><a class="pic" href="#this"><img style="width:261px;height:80px;" src="<?php echo $site['patch_img']; ?>/zt1.png"></a></li>
			</ul>
			</div>
</div>
		</div>
		
		</div>
		<script type="text/javascript">
		jQuery(".ztzl").slide({mainCell:".bd ul",autoPlay:true,effect:"leftMarquee",vis:4,interTime:50,trigger:"click"});
		</script>
		<!-- row2 E -->
	
		<!-- row3 S -->
		<div class="row3">
			<dl class="boxHgjj">
				<div class="hd">
					<ul>
						<li class="on">
							<a href="<?php echo url('Cms/index', ['catid'=>4]); ?>" class="more">更多</a>
							<h3 class="title"><a href="<?php echo url('Cms/index', ['catid'=>4]); ?>">装备前沿</a></h3>
						</li>
					</ul>
				</div>
				<div class="bd">
					<ul class="infoList">
						<?php 
						$news = widget('Position/getDatas', [2,7]);
						if($news){
						foreach($news as $k => $v){ 
						?>
						<li class="<?=$k==0?'first':'';?>">
							<span class="date"><?php echo $v['inputdate']; ?></span>
							<a href="<?php echo url('Cms/detail',array('catid'=>$v['catid'],'articleid'=>$v['aid'])); ?>" target="_blank" title="<?php echo cut_str($v['title'],25); ?>"><?php echo cut_str($v['title'],25); ?></a>
						</li>
						<?php 
						}
						} 
						?>
					</ul>
				</div>
			</dl>
			<dl class="boxZcfg">
				<div class="hd">
					<ul>
						<li class="on">
							<a href="<?php echo url('Cms/index', ['catid'=>23]); ?>" class="more">更多</a>
							<h3 class="title"><a href="<?php echo url('Cms/index', ['catid'=>23]); ?>">装备管理</a></h3>
						</li>
					</ul>
				</div>
				<div class="bd">
					<ul class="infoList">
						<?php 
						$news = widget('Position/getDatas', [3,7]);
						if($news){
						foreach($news as $k => $v){ 
						?>
						<li class="<?=$k==0?'first':'';?>">
							<span class="date"><?php echo $v['inputdate']; ?></span>
							<a href="<?php echo url('Cms/detail',array('catid'=>$v['catid'],'articleid'=>$v['aid'])); ?>" target="_blank" title="<?php echo cut_str($v['title'],25); ?>"><?php echo cut_str($v['title'],25); ?></a>
						</li>
						<?php 
						}
						} 
						?>
					</ul>
				</div>
			</dl>
		<dl class="boxJgsz">
		<div class="hd">
		<ul>
		<li class="on">
		<a href="#this" class="more">更多</a>
		<h3 class="title"><a href="#this">机构设置</a></h3>
		</li>
		</ul>
		</div>
		<div class="bd">
		<ul class="infoListJ">
		<li class="li1 first"><a href="#this">机构职能</a></li>
		<li class="li2"><a href="#this">领导班子</a></li>
		<li class="li3"><a href="#this">机构设置</a></li>
		<li class="li4"><a href="#this">下属机构</a></li>
		</ul>
		</div>
		<div class="ts">
		<img src="<?php echo $site['patch_img']; ?>/ts.jpg" style="width:250px;height:100px;">
		</div>
		</dl>
		</div>
		<!-- row3 E -->
	
		<!-- row4 S -->
		<div class="row4">
			<div class="bd">
				<ul>
					<li class="li1" style="display: list-item;">
						<div class="pic"><img src="<?php echo $site['patch_img']; ?>/banner1.jpg" style="width:1161px;height:100px;"></div>
						<div class="con">
							<div class="title"><a title="gyxc02" href="#this">…</a></div>
							<div class="bg"></div>
						</div>
					</li>
					<li class="li2" style="display: none;">
						<div class="pic"><img src="<?php echo $site['patch_img']; ?>/banner2.png" style="width:1161px;height:100px;"></div>
						<div class="con">
							<div class="title"><a title="gyxc01" href="#this">…</a></div>
							<div class="bg"></div>
						</div>
					</li>
				</ul>
			</div>
			<div class="hd">
				<ul>
					<li class="li1 on"><span>1</span></li>
					<li class="li2"><span>2</span></li>
				</ul>
			</div>      
		</div>
		<script type="text/javascript">
		jQuery(".row4").slide({mainCell:".bd ul",autoPlay:true});
		</script>
		<!-- row4 E -->
	
		<!-- row5 S -->
		<div class="row5">
	
			<dl class="boxHgjj">
				<div class="hd">
					<ul>
						<li class="on">
							<a href="<?php echo url('Cms/index', ['catid'=>32]); ?>" class="more">更多</a>
							<h3 class="title"><a href="<?php echo url('Cms/index', ['catid'=>32]); ?>">政策制度</a></h3>
						</li>
					</ul>
				</div>
				<div class="bd">
					<ul class="infoList">
						<?php 
						$news = widget('Position/getDatas', [4,7]);
						if($news){
						foreach($news as $k => $v){ 
						?>
						<li class="<?=$k==0?'first':'';?>">
							<span class="date"><?php echo $v['inputdate']; ?></span>
							<a href="<?php echo url('Cms/detail',array('catid'=>$v['catid'],'articleid'=>$v['aid'])); ?>" target="_blank" title="<?php echo cut_str($v['title'],25); ?>"><?php echo cut_str($v['title'],25); ?></a>
						</li>
						<?php 
						}
						} 
						?>
					</ul>
				</div>
			</dl>
		
			<dl class="boxZcfg">
				<div class="hd">
					<ul>
						<li class="on">
							<a href="<?php echo url('Cms/index', ['catid'=>36]); ?>" class="more">更多</a>
							<h3 class="title"><a href="<?php echo url('Cms/index', ['catid'=>36]); ?>">互动交流</a></h3>
						</li>
					</ul>
				</div>
				<div class="bd">
					<ul class="infoList">
						<?php 
						$news = widget('Position/getDatas', [5,7]);
						if($news){
						foreach($news as $k => $v){ 
						?>
						<li class="<?=$k==0?'first':'';?>">
							<span class="date"><?php echo $v['inputdate']; ?></span>
							<a href="<?php echo url('Cms/detail',array('catid'=>$v['catid'],'articleid'=>$v['aid'])); ?>" target="_blank" title="<?php echo cut_str($v['title'],25); ?>"><?php echo cut_str($v['title'],25); ?></a>
						</li>
						<?php 
						}
						} 
						?>
					</ul>
				</div>
			</dl>
			
			<dl class="boxWsbs">
				<div class="hd">
					<ul>
				  	<li class="on"><h3 class="title">网上办事</h3></li>
					</ul>
				</div>
				<div class="bd">
					<ul class="link">
						<li class="li1"><a href="#this" class="blsx"><i></i><span>办理事项</span></a></li>
						<li class="li2"><a href="#this" class="zxzx"><i></i><span>在线咨询</span></a></li>
						<li class="li3"><a href="#this" class="jdcx"><i></i><span>进度查询</span></a></li>
					</ul>
					<ul class="wsbsLink">
						<a href="#this" class="hbxmw"></a>
						<a href="#this" class="spxt"></a>
					</ul>
				</div>
			</dl>
			
		</div>
		<!-- row5 E -->
		
	<!-- 友情链接 S -->
	
	<!-- 友情链接 E -->
	
	</div>

  </div>
  
		<div id="footer">
		<p>
		教育部教育装备研究与发展中心主办    嘉运达提供技术支持 
		</p>
		<p>
		地址：北京市海淀区中关村大街35号
		</p>
		</div>
		



</body>
</html>

