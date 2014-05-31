<?php
/**
* View for prospection creation
*
* @copyright 2012 ARQABS
* @version    
*/
?>
<?php 
if(!isset($id)){
  $id = @field($this->uri->segment(4, NULL), set_value('id',isset($prospection_object)?$prospection_object->id : 'X') , 'X');
}

?>
<h2>Gestão de Prospecção de Empreendimentos</h2>
<h3>Inserir Sumário Executivo</h3>
<br/>
 <div id="error_section" class="alert alert-error" style="display:none">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
        <?php foreach ($error as $item => $value):?>
        <p><?php echo $value;?></p>
        <?php endforeach; ?>
        <?php echo validation_errors(); ?>
</div>

<?php $attributes = array('class' => 'form-horizontal', 'onsubmit' => 'return validate_form()');
echo form_open_multipart('prospection/upload_document',$attributes, array('id' => $id));?>

    <input type="hidden" name="file_path" id="file_path" value="<?php echo set_value('file_path', isset($prospection_object)?$prospection_object->file_path : ''); ?>" />    
    <input type="hidden" name="create_date" id="create_date" value="<?php echo set_value('create_date', isset($prospection_object)?$prospection_object->create_date : ''); ?>" />
    
    <br />
    <div class="control-group">
        <label class="control-label" for="description">Ideia</label>
        <div class="controls">
            <input rows="3" class="input-xlarge" maxlength="38" id="description" name="description" value="<?php echo set_value('description', isset($prospection_object)?$prospection_object->description : ''); ?>" ></input>
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="owner">Responsável</label>
        <div class="controls">
            <input type="text" class="input-xlarge" maxlength="38" id="owner" name="owner"  value="<?php echo set_value('owner', isset($prospection_object)?$prospection_object->owner : ''); ?>">
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="action_date">Data</label>
        <div class="controls">
            <input type="text" class="input-small" id="action_date" name="action_date" placeholder="dd/mm/yyyy" value="<?php echo set_value('action_date', isset($prospection_object)?($prospection_object->action_date != NULL) ? date("d/m/Y",strtotime($prospection_object->action_date)) : '' : '');   ?>"/>
            <span class="help-inline"></span>
        </div>
    </div>
    <?php if($id == 'X') {?>
    <div class="control-group">
        <label class="control-label" for="userfile">Sumário executivo</label>
        <div class="controls">
            <input class="input-file" name="userfile" id="userfile" type="file">
            <span class="help-inline"></span>
        </div>
    </div>
    <?php } 
    else {
        $document = set_value('file_path', isset($prospection_object)?$prospection_object->file_path : '');
        $document_viewer_display = ($document != '') ? 'display:block' : 'display:none'; 
        $document_uploader_display = ($document == '') ? 'display:block' : 'display:none';
    ?>
    <div id="document_viewer" class="control-group" style="<?php echo  $document_viewer_display;?>">
        <label class="control-label">Documento</label>
        <div class="controls">
            <a target="_blank" href="<?php echo $document; ?>">Ver sumário executivo</a> | <a href="#" onclick="show_upload('document'); return false;">Carregar novo sumário executivo</a>
            <span class="help-inline"></span>
        </div>
    </div>
  
    <div id="document_uploader" class="control-group" style="<?php echo $document_uploader_display;  ?>">
        <label class="control-label" for="userfile">Sumário executivo</label>
        <div class="controls">
            <input class="input-file" name="userfile" id="userfile" type="file">
            <span style="<?php echo $document_viewer_display;  ?>" class="help-inline"><a href="#" onclick="hide_upload('document'); return false;">Cancelar</a></span>
        </div>
    </div>    

    <?php }?>
    <p><button type="reset" class="btn" onclick="location.href='<?php echo site_url('prospection/index/2');?>'">Cancelar</button>&nbsp;<button type="submit" class="btn btn-primary">Salvar</button></p>
<?php form_close() ?>

<script type="text/javascript">

     $(document).ready(function() {
        <?php
            if(!empty($error)){ 
                echo "$('#error_section').alert();";
                echo "$('#error_section').show();";
            }
            
            if(form_error('description') != null)
            {
                ?>
                set_error("description", "<?php echo form_error('description'); ?>");
                <?php
            }
            if(form_error('owner') != null)
            {
                ?>
                set_error("owner", "<?php echo form_error('owner'); ?>");
                <?php
            }
            if(form_error('action_date') != null)
            {
                ?>
                set_error("action_date", "<?php echo form_error('action_date'); ?>");
                <?php
            }
            
        ?>
        
    });

	$(function() {
		$("#action_date").datepicker({ dateFormat: 'dd/mm/yy' });
	});
    
    function validate_form()
    {
        return true;
    }
    
    function set_error(field, message)
    {
         $("#" + field).closest('div.control-group').addClass('error');
         $("#" + field).next('span.help-inline').text(message);
    }
 
    function show_upload(type)
    {
        $("#" + type + "_viewer").hide();
        $("#" + type + "_uploader").show();
    }
    
    function hide_upload(type)
    {
        $("#" + type + "_uploader").hide();
        $("#" + type + "_viewer").show();
    }
</script>
