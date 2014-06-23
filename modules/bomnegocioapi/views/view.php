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