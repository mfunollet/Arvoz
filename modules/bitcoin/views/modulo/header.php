<? $CI =& get_instance(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?=$this->seo->output()?>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link href="<?=base_url().'inc/css/layout.css';?>" rel="stylesheet" type="text/css" />
		
		<?=$CI->xajax->getJavascript(base_url().'inc/3party/xajax');?>
		<script src="<?=base_url()?>inc/3party/jquery-latest.js"></script>
 <? // google.load("jquery", "1.4.2")  google.load("jqueryui", "1.8.0");
 ?>

		<? if($this->uri->segment(1) == 'projetos'):?>
			<link href="<?=base_url().'inc/3party/coda-slider/coda-slider.css';?>" rel="stylesheet" type="text/css" />
			<script src="<?=base_url()?>inc/3party/coda-slider/jquery.scrollTo-1.3.3.js" type="text/javascript"></script>
			<script src="<?=base_url()?>inc/3party/coda-slider/jquery.localscroll-1.2.5.js" type="text/javascript" charset="utf-8"></script>
			<script src="<?=base_url()?>inc/3party/coda-slider/jquery.serialScroll-1.2.1.js" type="text/javascript" charset="utf-8"></script>
			<script src="<?=base_url()?>inc/3party/coda-slider/coda-slider.js" type="text/javascript" charset="utf-8"></script>
		<? endif;?>

		<? if($this->uri->segment(1) == 'fotos'):?>
			<script type="text/javascript" src="<?=base_url()?>inc/3party/fancyzoom/js/FancyZoom.js"></script>
			<script type="text/javascript" src="<?=base_url()?>inc/3party/fancyzoom/js/FancyZoomHTML.js"></script>
		<? endif;?>
		
		<!-- Menu fix IE 7 -->
		<!--[if lte IE 7]>
		<script type="text/javascript" src="http://www.cssnolanche.com.br/lab/jquery.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#menu li").hover(function(){
				$(this).addClass("over");					  
			},function(){
				$(this).removeClass("over");
			});
		});
		</script>
		<![endif]-->

	<? if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'chrome')):?>
	<!-- Fix para o Google Crome =-->
	<style type="text/css">
		.contemfloat:after{display:inline; } 
	</style>
	<? endif; ?>
	
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-15945037-1");
		pageTracker._trackPageview();
		} catch(err) {}
	</script>
    <script type="text/javascript" src="<?=base_url()?>inc/3party/jquery/jquery.corner.js"></script>
    <script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
   <script type="text/javascript">
		$(document).ready(function() {
		});
    </script> 
</head>
	<body>

<div id="bg_header"></div>
  <div id="limites">
    <div id="conteudo" class="contemfloat">
		<div id="header">
			<?=anchor('','<div id="logo"></div>')?>
			<div id="menu">
				<ul>
					<? $url = $this->uri->segment(1);
						$class = ' id="menuAtual"';?>
					<li <?=($url=='index' || $url=='')?$class:'' ?> class="arredondarL"><strong><?=anchor('','Home')?></strong></li>
					<? /*<li <?=($url=='pedido')?$class:'' ?>><strong><?=anchor('pedido','Comprar	')?></strong></li>*/?>
					<li <?=($url=='modulos')?$class:'' ?>><strong><?=anchor('modulos','M&oacute;dulos')?></strong></li>
					<li <?=($url=='tecnologia')?$class:'' ?>><strong><?=anchor('tecnologia','Tecnologia')?></strong></li>
					<li <?=($url=='projetos')?$class:'' ?>><strong><?=anchor('projetos','Projetos')?></strong></li>
					<li <?=($url=='requisitos')?$class:'' ?>><strong><?=anchor('requisitos','Requisitos')?></strong></li>
					<li <?=($url=='contato')?$class:'' ?> class="arredondarR" style="border-right:0"><strong><?=anchor('contato','Contato')?></strong></li>
				</ul>
			</div><!-- #menu-->
		</div>
<br />

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="995" height="220" id="FlashID" accesskey="d" title="destaques">
	<param name="movie" value="<?=base_url()?>inc/swf/flash_destaque.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="opaque" />
	<param name="swfversion" value="8.0.35.0" />
	<!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
	<param name="expressinstall" value="<?=base_url()?>scripts/expressInstall.swf" />
	<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
	<!--[if !IE]>-->
	<object type="application/x-shockwave-flash" data="<?=base_url()?>inc/swf/flash_destaque.swf" width="995" height="220">
		<!--<![endif]-->
		<param name="quality" value="high" />
		<param name="wmode" value="opaque" />
		<param name="swfversion" value="8.0.35.0" />
		<param name="expressinstall" value="<?=base_url()?>scripts/expressInstall.swf" />
		<!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
		<div>
			<h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
			<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
		</div>
		<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
</object>
<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
//-->
</script>
