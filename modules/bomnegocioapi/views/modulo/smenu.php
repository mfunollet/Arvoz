<?php if(isset($smenu)): ?>
	<div id="submenu">

		<?php if(isset($smenu['header'])): // Cabeçalho do smenu cao necessario ?>
			<?=$smenu['header'];?>
		<?php endif; ?>
		
		<?php if(isset($smenu['itens'])): // Links caso necessario?>
		<ul>
			<h3><?=(isset($smenu['Lheader']))?$smenu['Lheader']:'Categorias';?></h3>
				<?php foreach($smenu['itens'] as $item):?>
					<li><?=anchor($item['url'], $item['nome']);?></li>
				<?php endforeach; ?>
		<ul>
		<?php endif; ?>
		
		<?php if(isset($smenu['footer'])): // Rocapé do smenu cao necessario ?>
			<?=$smenu['footer'];?>
		<?php endif; ?>
	</div>
<?php endif; ?>