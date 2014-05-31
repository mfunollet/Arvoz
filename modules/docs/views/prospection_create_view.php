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
<h3>Inserir Ação</h3>
<br/>
 <div id="error_section" class="alert alert-error" style="display:none">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
        <?php foreach ($error as $item => $value):?>
        <p><?php echo $value;?></p>
        <?php endforeach; ?>
        <?php echo validation_errors(); ?>
</div>

<?php $attributes = array('class' => 'form-horizontal', 'onsubmit' => 'return validate_form()');
echo form_open_multipart('prospection/insert', $attributes, array('id' => $id));?>

    <br />
    <input type="hidden" name="venture_prospection_type_id" id="venture_prospection_type_id" value="<?php echo $venture_prospection_type_id; ?>" />    
    <input type="hidden" name="document_path" id="document_path" value="<?php echo set_value('document_path', isset($prospection_object)?$prospection_object->document_path : ''); ?>" />    
    <input type="hidden" name="photo_path" id="photo_path" value="<?php echo set_value('photo_path', isset($prospection_object)?$prospection_object->photo_path : ''); ?>" />    
    <input type="hidden" name="create_date" id="create_date" value="<?php echo set_value('create_date', isset($prospection_object)?$prospection_object->create_date : ''); ?>" />
    
    <div class="control-group">
        <label class="control-label" for="venture_action_id">Ação</label>
        <div class="controls">
            <?php echo form_dropdown('venture_action_id', $venture_actions, set_value('venture_action_id', isset($prospection_object)?$prospection_object->venture_action_id : $this->input->post('venture_action_id')));?>
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="description">Tema</label>
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
    <div class="control-group">
        <label class="control-label" for="place">Local</label>
        <div class="controls">
            <input type="text" class="input-xlarge" maxlength="38" id="place" name="place"  value="<?php echo set_value('place', isset($prospection_object)?$prospection_object->place : ''); ?>">
            <span class="help-inline"></span>
        </div>
    </div>
     <div class="control-group">
        <label class="control-label" for="workload">Carga horária (horas)</label>
        <div class="controls">
            <input type="text" class="input-mini" id="workload" name="workload" value="<?php echo set_value('workload', isset($prospection_object)?$prospection_object->workload : ''); ?>">
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="attendees_number">Total de participantes</label>
        <div class="controls">
            <input type="text" class="input-mini" id="attendees_number" name="attendees_number"  value="<?php echo set_value('attendees_number', isset($prospection_object)?$prospection_object->attendees_number : ''); ?>">
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="content_text">Conteúdo sumarizado</label>
        <div class="controls">
            <textarea rows="6" maxlength="400" style="width:350px" id="content_text" name="content_text"><?php echo set_value('content_text', isset($prospection_object)?$prospection_object->content_text : ''); ?></textarea>
            <span class="help-inline"></span>
        </div>
    </div>
    <?php if($id == 'X') {?>
    <div class="control-group">
        <label class="control-label" for="userfile">Lista de participantes</label>
        <div class="controls">
            <input class="input-file" name="userfile" id="userfile" type="file">
            <span class="help-inline"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="userfile1">Foto da ação</label>
        <div class="controls">
            <input class="input-file" name="userfile1" id="userfile1" type="file">
            <span class="help-inline"></span>
        </div>
    </div>
    <?php }
    else {
    ?>
    <?php $document = set_value('document_path', isset($prospection_object)?$prospection_object->document_path : ''); 
        $document_viewer_display = ($document != '') ? 'display:block' : 'display:none'; 
        $document_uploader_display = ($document == '') ? 'display:block' : 'display:none';
    ?>
    <div id="document_viewer" class="control-group" style="<?php echo  $document_viewer_display;?>">
        <label class="control-label">Documento</label>
        <div class="controls">
            <a target="_blank" href="<?php echo $document; ?>">Ver lista</a> | <a href="#" onclick="show_upload('document'); return false;">Carregar nova lista</a>
            <span class="help-inline"></span>
        </div>
    </div>
  
    <div id="document_uploader" class="control-group" style="<?php echo $document_uploader_display;  ?>">
        <label class="control-label" for="userfile">Lista de participantes</label>
        <div class="controls">
            <input class="input-file" name="userfile" id="userfile" type="file">
            <span style="<?php echo $document_viewer_display;  ?>" class="help-inline"><a href="#" onclick="hide_upload('document'); return false;">Cancelar</a></span>
        </div>
    </div>
    
    <?php 
    $foto = set_value('photo_path', isset($prospection_object)?$prospection_object->photo_path : '');
    $photo_viewer_display = ($foto != '') ? 'display:block' : 'display:none'; 
    $photo_uploader_display = ($foto == '') ? 'display:block' : 'display:none';
    ?>
    <div id="photo_viewer" class="control-group" style="<?php echo $photo_viewer_display;?>">
        <label class="control-label">Foto</label>
        <div class="controls">
            <a target="_blank" href="<?php echo $foto; ?>">Ver foto</a> | <a href="#" onclick="show_upload('photo'); return false;">Carregar nova foto</a>
            <span class="help-inline"></span>
        </div>
    </div>

    <div id="photo_uploader" class="control-group" style="<?php echo $photo_uploader_display; ?>">
        <label class="control-label" for="userfile1">Foto da ação</label>
        <div class="controls">
            <input class="input-file" name="userfile1" id="userfile1" type="file">
            <span  class="help-inline" style="<?php echo $photo_viewer_display;?>"><a href="#" onclick="hide_upload('photo'); return false;">Cancelar</a></span>
        </div>
    </div>
    
    <?php 
    
    }
       ?>
    <p><button type="reset" class="btn" onclick="location.href='<?php echo site_url('prospection/index/' . $venture_prospection_type_id);?>'">Cancelar</button>&nbsp;<button type="submit" class="btn btn-primary">Salvar</button></p>
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
            if(form_error('place') != null)
            {
                ?>
                set_error("place", "<?php echo form_error('place'); ?>");
                <?php
            }
            if(form_error('action_date') != null)
            {
                ?>
                set_error("action_date", "<?php echo form_error('action_date'); ?>");
                <?php
            }
            if(form_error('attendees_number') != null)
            {
                ?>
                set_error("attendees_number", "<?php echo form_error('attendees_number'); ?>");
                <?php
            }
            if(form_error('content_text') != null)
            {
                ?>
                set_error("content_text", "<?php echo form_error('content_text'); ?>");
                <?php
            }
            if(form_error('workload') != null)
            {
                ?>
                set_error("workload", "<?php echo form_error('workload'); ?>");
                <?php
            }
            
        ?>
        
    });

	$(function() {
		$("#action_date").datepicker({ dateFormat: 'dd/mm/yy' });
	});
    
    jQuery(function($){
        $("#attendees_number").numeric();
        $("#workload").numeric();
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
