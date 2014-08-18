<h1><?php echo $p->title;?></h1>
<br />
<?php foreach ($p->images as $image):?>
	<img src="<?php echo $image;?>" class="image" /><br />
<?php endforeach; ?>
<?php echo $p->title;?><br />