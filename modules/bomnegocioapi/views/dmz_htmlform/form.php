<?
	if( ! isset($save_button))
	{
		$save_button = 'OK';
	}
	if( ! isset($reset_button))
	{
		$reset_button = FALSE; 
	}
	else
	{
		if($reset_button === TRUE)
		{
			$reset_button = 'Limpar'; //Reset
		}
	}
$time = time();
$atributos = array('name' => 'form'.$time, 
					'id' => 'form'.$time);
?>
<?=$object->render_errors();?>

<?=($object->multipart) ? form_open_multipart($this->config->site_url($url), $atributos) : form_open($this->config->site_url($url), $atributos);?>
<table class="form">
<?= $rows ?>
	<tr class="buttons">
		<td colspan="2" align="right">
		<input type="<?=(isset($ajax_button))? 'button':'submit'?>" value="<?= $save_button ?>"  class="enviar" 
		<?=(isset($ajax_button))? 'onclick="javascript:xajax_send(\''. $ajax_button[0] .'\', \''. $ajax_button[1] .'\', \''. $ajax_button[2] .'\', xajax.getFormValues(\''. $atributos['name'] .'\'));" ' : '' ?>/><?
			if($reset_button !== FALSE)
			{
				?> <input type="reset" value="<?= $reset_button ?>" /><?
			}		
		?></td>
	</tr>
</table>
</form>