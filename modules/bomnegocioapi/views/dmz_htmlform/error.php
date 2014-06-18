<? if( ! empty($object->error->all)): ?>
<div class="error">
	<p>There was an error saving the form.</p> 
	<ul><? foreach($object->error->all as $k => $err): ?>
		<li><?= $err ?></li>
		<? endforeach; ?>
	</ul>
</div>
<? endif; ?>