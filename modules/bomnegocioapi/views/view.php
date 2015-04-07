<meta charset="utf-8">
Resultados: <?php echo $products->result_count();?> produtos
<br />
<table>
	<tr>
		<td>preço</td>
		<td>image</td>
		<td>titulo</td>
		<td width="400">desc</td>
		<td>data</td>
	</tr>
<?php foreach($products as $p):?>
	<?php 
	$image_properties = array(
          'src' => $p->image,
          'width' => '200',
          'height' => '200'
	);?>
	<tr>
		<td>
			R$ <?php echo $p->price;?>
		</td>
		<td>
			<?php echo anchor($p->url, img($image_properties));?>
		</td>
		<td>
			<?php echo $p->title;?>
		<td width="400">
			<?php echo $p->description;?>
		</td>
		</td>
		<td>
			<?php echo $p->date;?>
		</td>
<?php endforeach;?>
</table>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="<?php echo base_url();?>cjs/isotope/isotope.pkgd.min.js"></script>
<style>
	.item { width: 25%; }
</style>
Resultados: <?php echo $products->result_count();?> produtos
<br />
<div id="container">
	<?php foreach($products as $p):?>
	<?php 
	$image_properties = array(
		'src' => $p->image,
		'width' => '200',
		'height' => '200'
		);?>
		<div class="element-item" data-category="transition">
			<h3 class="title"><?php echo $p->title;?></h3>
			<h3 class="price">R$ <?php echo $p->price;?></h3>
			<h3 class="img"><?php echo anchor($p->url, img($image_properties));?></h3>
			<p class="description"><?php echo $p->description;?></p>
			<p class="date"><?php echo $p->date;?></p>
		</div>
	<?php endforeach;?>
</div>

<script type="text/javascript">
var $container = $('#container');
$container.isotope({
	itemSelector: '.element-item',
	layoutMode: 'fitRows',
})
</script>