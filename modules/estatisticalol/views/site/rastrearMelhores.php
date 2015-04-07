<p>
Total de inimigos rastreados: <?=$totalInimigos?><br />
Treinando seus mineiros em Soldados voce faria um offence de: <?=$OffenceMax?></p>
<?if($inimigosFiltrados == 0):?>
	<?=anchor('site/rastrearMelhores/'.$id.'/'.($multi-1), 'Filtrar menos');?>
<?else:?>
	<?=anchor('site/rastrearMelhores/'.$id.'/'.($multi+1), 'Filtrar mais');?>
<?endif;?>
<div style="float:left;">
	<h2>Top 10 Gold/ArmySize com max de <?=$unidades?> Army Size<br />e no min <?=$goldMin?> gold</h2>
	<?=$tabela1?>
	<br />
	<h2>Top 10 Gold/ArmySize e no min <?=$goldMin?> gold</h2>
	<?=$tabela2?>
</div>

<div style="float:right;">
	<h2>Top 10 Gold com max de <?=$unidades?> Army Size</h2>
	<?=$tabela3?>
	<br />
	<h2>Top 10 Gold</h2>
	<?=$tabela4?>
	<br />
	<?=$tabela5?>
</div>

<br />
<?=anchor('http://whm.cpweb0022.servidorwebfacil.com:2082/', 'Hospedagem')?>
<?=anchor('http://whm.cpweb0022.servidorwebfacil.com:2082/3rdparty/phpMyAdmin/index.php?db=tgroch_dt&token=81b377fd022e4bbab7129b0bebcf1e29', 'Banco de dados')?>
