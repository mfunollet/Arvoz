<?
	if(!isset($row_id))
	{
		if( ! empty($id))
		{
			$row_id = ' id="row_' . $id . '"';
		}
		else
		{
			$row_id = '';
		}
	}
	else
	{
		$row_id = ' id="' . $row_id .'"';
	}
	
	if(!isset($label_for))
	{
		if( ! empty($id))
		{
			$label_for = ' for="' . $id . '"';
		}
		else
		{
			$label_for = '';
		}
	}
	else
	{
		$label_for = ' for="' . $label_for .'"';
	}
	
	if( ! empty($row_class))
	{
		$row_class = ' ' . $row_class;
	}
	else
	{
		$row_class = '';
	}
	
	if( ! empty($error))
	{
		$row_class .= ' error';
	}
	
	if($required)
	{
		$row_class .= ' required';
	}
	
	$req = (in_array('required', $object->validation[$field]['rules']) ) ? '*' : '';
if(isset($object->validation[$field]['type']) && $object->validation[$field]['type'] == 'CKtextarea'){
	// Se o campo precisar de 2 linhas
	?>
	<tr class="row<?= $row_class ?>"<?= $row_id ?>>
		<td class="label"><label<?= $label_for ?>><?= $label ?><?=$req;?>:</label></td>
	</tr>
	<tr id="row_<?= $id ?>2">
		<td class="field" colspan="2">
			<? //=($object->validation[$field]['validation'] == )? '*' : ''?>
			<?= $content ?>
			<? // Exibe descrição caso houver
			echo (isset($object->desc[$field])) ? '<br />'.$object->desc[$field] : '';
			?>

			<? /*
			// Enable this section to print errors out for each row.
			if( ! empty($error)): ?>
			<span class="error"><?= $error ?></span>
			<? endif; */ ?>			
		</td>
	</tr><?
}else{
	// Se o campo não precisar de 2 linhas
	?>
	<tr class="row<?= $row_class ?>"<?= $row_id ?>>
		<td class="label"><label<?= $label_for ?>><?= $label ?><?=$req;?>:</label></td>
		<td class="field">
			<?= $content ?>
			<? // Exibe descrição caso houver
			echo (isset($object->desc[$field])) ? '<br />'.$object->desc[$field] : '';
			?>

			<? /*
			// Enable this section to print errors out for each row.
			if( ! empty($error)): ?>
			<span class="error"><?= $error ?></span>
			<? endif; */ ?>			
		</td>
	</tr><?
}
?>
