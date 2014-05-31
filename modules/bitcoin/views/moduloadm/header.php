<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Admin</title>
    <link href="<?=base_url().'inc/css/adm.css';?>" rel="stylesheet" type="text/css">
	<? //=$xajax_js?>
	<script src="<?=base_url()?>inc/3party/jquery-latest.js"></script>
	<script src="<?=base_url()?>inc/3party/jquery/jquery.tablesorter.min"></script>
	<script src="<?=base_url()?>inc/3party/jquery/jquery.tablesorter.pager"></script>
	<script language="javascript" src="<?=base_url()?>inc/js/js.js"></script>
	<script language="javascript" src="<?=base_url()?>inc/3party/calendario/calendar.js"></script>    
    <script language="javascript">
	$(document).ready(function() { 
    	$("table") 
		.tablesorter({widthFixed: true, widgets: ['zebra']}) 
		.tablesorterPager({container: $("#pager")}); 
	});
	</script>
</head>
<body>
    <div id="conteiner">
		<div id="conteiner-header">
			<a href="<?=base_url()?>adm">
				<div id="header">
					<h1>Partido Social Crist&atilde;o</h1>
				</div>
			</a>
		</div>
		<br class="clear" />
        <div id="conteiner-top"></div>
        <div id="conteiner-meio">
            <div id="content">
