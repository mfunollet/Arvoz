<?php
/**
* View for management planning creation
*
* @copyright 2012 ARQABS
* @version    
*/
?>
<?php 
if(!isset($id)){
  $id = @field($this->uri->segment(3, NULL), set_value('id',isset($planning_object)?$planning_object->id : 'X') , 'X');
}

?>
<h2>Módulo Gestão do Planejamento</h2>
<h3>Inserir registro</h3>
<br/>
 <div id="error_section" class="alert alert-error" style="display:none">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
        <?php foreach ($error as $item => $value):?>
        <p><?php echo $value;?></p>
        <?php endforeach; ?>
        <?php echo validation_errors(); ?>
</div>

<?php $attributes = array('class' => 'form-horizontal', 'onsubmit' => 'return validate_form()');
echo form_open_multipart('planning/insert', $attributes, array('id' => $id));?>

    <input type="hidden" name="file_path" id="file_path" value="<?php echo set_value('file_path', isset($planning_object)?$planning_object->file_path : ''); ?>" />    
    <input type="hidden" name="create_date" id="create_date" value="<?php echo set_value('create_date', isset($planning_object)?$planning_object->create_date : ''); ?>" />

    <br />
    <div class="control-group">
        <label class="control-label" for="planning_type_id">Planejamento</label>
        <div class="controls">
            <?php echo form_dropdown('planning_type_id', $planning_types, set_value('planning_type_id', isset($planning_object)?$planning_object->planning_type_id : $this->input->post('planning_type_id')));?>
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="venture">Empreendimento</label>
        <div class="controls">
            <input rows="3" maxlength="38" class="input-xlarge" id="venture" name="venture" value="<?php echo set_value('venture', isset($planning_object)?$planning_object->venture : ''); ?>" ></input>
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="entrepreneur">Empreendedor</label>
        <div class="controls">
            <input rows="3" maxlength="38" class="input-xlarge" id="entrepreneur" name="entrepreneur" value="<?php echo set_value('entrepreneur', isset($planning_object)?$planning_object->entrepreneur : ''); ?>" ></input>
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="version">Versão</label>
        <div class="controls">
            <input type="text" class="input-mini" id="version" name="version" placeholder="00.00" value="<?php echo set_value('version', isset($planning_object)?$planning_object->version : ''); ?>">
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="approval_date">Data da Aprovação</label>
        <div class="controls">
            <input type="text" class="input-small" id="approval_date" name="approval_date" placeholder="dd/mm/yyyy" value="<?php echo set_value('approval_date', isset($planning_object)?($planning_object->approval_date != NULL) ? date("d/m/Y",strtotime($planning_object->approval_date)) : '' : '');   ?>"/>
            <span class="help-inline"></span>
        </div>
    </div>
    <?php if($id == 'X') {?>
    <div class="control-group">
        <label class="control-label" for="userfile">Documento</label>
        <div class="controls">
            <input class="input-file" name="userfile" id="userfile" type="file">
            <span class="help-inline"></span>
        </div>
    </div>
    <?php } 
    else {
        $document = set_value('file_path', isset($planning_object)?$planning_object->file_path : '');
        $document_viewer_display = ($document != '') ? 'display:block' : 'display:none'; 
        $document_uploader_display = ($document == '') ? 'display:block' : 'display:none';
    ?>
    <div id="document_viewer" class="control-group" style="<?php echo  $document_viewer_display;?>">
        <label class="control-label">Documento</label>
        <div class="controls">
            <a target="_blank" href="<?php echo $document; ?>">Ver documento</a> | <a href="#" onclick="show_upload('document'); return false;">Carregar novo documento</a>
            <span class="help-inline"></span>
        </div>
    </div>
  
    <div id="document_uploader" class="control-group" style="<?php echo $document_uploader_display;  ?>">
        <label class="control-label" for="userfile">Documento</label>
        <div class="controls">
            <input class="input-file" name="userfile" id="userfile" type="file">
            <span style="<?php echo $document_viewer_display;  ?>" class="help-inline"><a href="#" onclick="hide_upload('document'); return false;">Cancelar</a></span>
        </div>
    </div>    

    <?php }?>
    <p><button type="reset" class="btn" onclick="location.href='<?php echo site_url('planning/index');?>'">Cancelar</button>&nbsp;<button type="submit" class="btn btn-primary">Salvar</button></p>
<?php form_close() ?>

<script type="text/javascript">

     $(document).ready(function() {
        <?php
            if(!empty($error)){ 
                echo "$('#error_section').alert();";
                echo "$('#error_section').show();";
            }
            
            if(form_error('entrepreneur') != null)
            {
                ?>
                set_error("entrepreneur", "<?php echo form_error('entrepreneur'); ?>");
                <?php
            }
             if(form_error('venture') != null)
            {
                ?>
                set_error("venture", "<?php echo form_error('venture'); ?>");
                <?php
            }
            if(form_error('version') != null)
            {
                ?>
                set_error("version", "<?php echo form_error('version'); ?>");
                <?php
            }
            if(form_error('approval_date') != null)
            {
                ?>
                set_error("approval_date", "<?php echo form_error('approval_date'); ?>");
                <?php
            }
        ?>
        
    });

	$(function() {
		$("#approval_date").datepicker({ dateFormat: 'dd/mm/yy' });
	});
    
    jQuery(function($){
        $("#version").numeric();
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
