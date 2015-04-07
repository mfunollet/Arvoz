<div class="row">
  <div class="col-xs-6 col-md-4">
	<h1><?php echo $p->title;?></h1><br />
	<?php echo $p->description;?>
  </div>
  <div class="col-xs-12 col-md-8">
	<?php foreach ($p->images as $image):?>
		<img src="<?php echo $image;?>" class="img-responsive" /><br />
	<?php endforeach; ?>
  </div>
</div>