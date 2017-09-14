<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"E:\www\zbzx\public/../application/index\view\site_1\cms\catpage_default.html";i:1505129994;s:51:"../application/index/view/site_1/public/header.html";i:1505215572;s:51:"../application/index/view/site_1/public/footer.html";i:1505057297;}*/ ?>
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



	<link href="<?php echo $site['patch_css']; ?>/article.css" rel="stylesheet" type="text/css">

	<div id="content">
		
		<div class="topToolBox">
			<div class="pathWrap">
				<div class="path">
					<em>您现在的位置：</em>
					<a class="home" href="/">首页</a>&gt;
					<?php
			    $siteCatNavs = widget('Content/getSiteCatNav', array($siteid, $currentcat['catid'],true));
			    if($siteCatNavs){
			        $nav = '';
			        foreach($siteCatNavs as $k => $v){
			            $nav .= "<a href='".$v['url']."' target='".$v['target']."'>".$v['catname']."</a>";    
			            if(($k+1) < count($siteCatNavs)){
			                $nav .= "&gt;";
			            }
			        }
			        echo $nav;
			    }
					?>
			  </div>
			</div>
		</div>
	
		<div class="side">
			<div id="sideMenu" class="sideBox">
				<div class="hd">
					<h3><?=$topcat['catname'];?>　</h3>
				</div>
				<div class="bd">
					<ul class="menuList">
	          <?php $cats = widget('Content/getDeepArrayCats', array($siteid, $topcat['catid'])); foreach($cats as $v){ if($v['type'] == 2){ ?>
                    <a href="<?php echo $v['url']; ?>" target="_blank"><?php echo $v['catname']; ?></a>
                <?php }else{ ?>
      	            <li class="<?=($v['catid'] == $currentcat['catid']) ? 'on' : ''; ?>">
      	            	<a title="<?php echo $v['catname']; ?>" href="<?php echo url('Cms/index',array('catid'=>$v['catid'])); ?>"><?php echo $v['catname']; ?></a>
      	            </li>
                <?php } } ?>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="main">
			<div class="mainBox">
				<div class="wHd">
				  <h3><span><?php echo $page['title']; ?></span></h3>
				</div>
				<div class="conTxt">
			    <?php echo $page['content']; ?>
			  </div>
		  </div>
		</div>

  </div>

  <script>
  	if( $("#sideMenu .bd li").size()==0 ){
  		$("#sideMenu").hide(); 
    }
  </script>

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


