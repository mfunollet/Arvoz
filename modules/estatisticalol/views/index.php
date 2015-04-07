<div id="link">
	<?php echo form_open("link/adicionar_Link"); ?>
		<?php echo form_label("Nome :","lbl_nome"); ?>
		<?php echo form_input("nome",$nome); ?>
			<br />
		<?php echo form_label("Site :","lbl_url"); ?>
		<?php echo form_input("url",$url); ?>
			<br />
		<?php echo form_label("Estado :","lbl_status"); ?>
		<?php echo form_input("status",$status); ?>
			<br />
		<?php echo form_label("Categoria :","lbl_categoria"); ?>
		<select name="categoria" id="categoria">
			<option value="" >-/-</option>
			<?php foreach($categorias as $categoria)
			{
				echo '<option value="'.$categoria->id.'" ';
					echo ($categoria->id==$categoria_id)? 'selected="selected"' : '';
				echo ' >'.$categoria->nome.'</option>';
			}
			?>
			<option value="outros" >Outros ...</option>
		</select>
			<br />
		<?php echo form_label("Categoria Nome :","lbl_categoria_nome"); ?>
		<?php echo form_input("categoria_nome"); ?>

		<?php echo form_submit("salvar","Salvar"); ?>
	</form>
</div>
<div id="categoria_edit">
	<?php echo form_open("link/atualizar_Categoria"); ?>
		<?php echo form_label("Nome :","lbl_nome"); ?>
		<?php echo form_input("nome_categoria",$nome_categoria); ?>
			<br />
		<?php echo form_label("Estado :","lbl_status"); ?>
		<?php echo form_input("status_categoria",$status_categoria); ?>
			<br />
		<?php echo form_submit("salvar","Salvar"); ?>
	</form>
</div>
<?php
	//echo $categorias;
	//echo $nome.'<br />';
	//echo $url.'<br />';
	//echo $status.'<br />';
	//echo $categoria_id.'<br />';
?>