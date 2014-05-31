<?php
/**
 * View for process document management
 *
 * @copyright 2012 ARQABS
 * @version    
 */
?>
<script type="text/javascript">
    
    $(document).ready(function() {
<?php
if (!empty($error)) {
    echo "$('#error_section').alert();";
    echo "$('#error_section').show();";
    echo "$('#upload_modal').modal('show');";
}

if ($this->uri->segment(3) == "success") {
    echo "$('#success_alert').show();";
}

if (!empty($history_documents)) {
    echo "$('#history_modal').modal();";
}
?>
        
            });
    
            function insert(key_process_id, key_process, maturity_level, key_practice_elements)
            {
                $('#modal_title').text('Carregar documento');
    
                $('#key_process_id').val(key_process_id);
        
                $('#version').val('');
                $('#approval_date').val('');
                $('#approver').val('');
                $('#userfile').val('');
                $('#key_practice_id').val(1);
        
                $("#userfile").closest('div.control-group').show();
        
                fill_dropdown('key_practice_id', key_practice_elements);
        
                open_upload_modal(key_process, maturity_level);   
            }
        
    
            function update(key_process_id, key_process, maturity_level, process_document_id, key_practice_id, version, approval_date, approver, file_path, create_date, key_practice_elements)
            {
                $('#modal_title').text('Atualizar registro');
        
<?php $update_url = site_url("process/update"); ?>
        
                $('form').get(0).setAttribute('action','<?php echo $update_url; ?>' + '/' + process_document_id);
                
                $('#key_process_id').val(key_process_id);
                $('#file_path').val(file_path);
                $('#create_date').val(create_date);
                
                $('#version').val(version);
                $('#approval_date').val(approval_date.substr(8,2) + "/" + approval_date.substr(5,2) + "/" + approval_date.substr(0,4)); 
                $('#approver').val(approver);
                //$('#userfile').val('');
                $('#key_practice_id').val(key_practice_id);
        
                $("#userfile").closest('div.control-group').hide();
        
                fill_dropdown('key_practice_id', key_practice_elements, key_practice_id);
        
                open_upload_modal(key_process, maturity_level);
            }
    
            function open_upload_modal(key_process, maturity_level)
            {
                $('#error_section').hide();
                $('#upload_form_title').html(maturity_level + ' - ' + key_process);
                $('#upload_modal').modal();
            }
    
            function fill_dropdown(id, elements, selected)
            {
                var array = elements.split(',');
        
                var html = "";
                for (var i = 0; i < array.length; i++) {
                
                    var keyValue = array[i].split('|');
                    
                    html += "<option value=\"";
                    html += keyValue[0] + "\">";
                    html += keyValue[1] + "</option>";
                }
                
                $('#' + id).empty().append(html);
                
                $('#' + id).val(selected);
            }
        
    
    
</script>

<h2>Gestão de Documentação de Processos</h2>
<br/>
<div id="success_alert" class="alert alert-success" style="display:none">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
    Operação realizada com sucesso!
</div>
<table id="process_table" class="table table-striped table-bordered table-condensed">
    <tr>
        <th>Maturidade</th>
        <th>Nome do processo</th>
        <th>Prática-chave</th>
        <th>Versão</th>
        <th>Data Aprovação</th>
        <th>Aprovador</th>
        <th></th>
    </tr>
    <?php $previous_key_process_id = 0; ?>
    <?php foreach ($query as $item): ?>
        <?php
        if ($item->key_process_id == $previous_key_process_id) {
            continue;
        }
        ?>
        <tr>
            <td><?php echo $item->maturity_level; ?></td>
            <td><?php echo $item->key_process; ?></td>
            <td><?php echo is_null($item->key_practice) ? "Não Implantada" : $item->key_practice; ?></td>
            <td><?php echo $item->version; ?></td>
            <td><?php
        if ($item->approval_date != NULL) {
            echo date("d/m/Y", strtotime($item->approval_date));
        }
        ?>
            </td>
            <td><?php echo $item->approver; ?></td>
            <td class="icon">
    <?php if ($item->process_document_id != NULL): ?>
                    <div class="btn-group">
                        <a class="btn btn-info" title="Visualizar documento" href="<?php echo base_url($item->file_path);?>"><i class="icon-file icon-white"></i></a>
                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url($item->file_path);?>"><i class="icon-file"></i> Visualizar documento</a></li>
                            <li><a href="#" onclick="update(<?php echo $item->key_process_id;?>,'<?php echo $item->key_process;?>','<?php echo $item->maturity_level;?>', <?php echo $item->process_document_id;?>, <?php echo $item->key_practice_id;?>, <?php echo $item->version;?>, '<?php echo $item->approval_date;?>', '<?php echo $item->approver;?>', '<?php echo $item->file_path;?>', '<?php echo $item->create_date;?>','<?php echo $item->key_practice_has_maturity_level;?>')"><i class="icon-pencil"></i> Atualizar registro</a></li>
                            <li><a href="#" onclick="insert(<?php echo $item->key_process_id;?>,'<?php echo $item->key_process;?>','<?php echo $item->maturity_level;?>', '<?php echo $item->key_practice_has_maturity_level;?>'); return false;"><i class="icon-upload"></i> Carregar documento</a></li>
                            <li class="divider"></li>
                            <li><?php echo anchor('process/history/' . $item->key_process_id, '<i class="icon-time"></i> Histórico de registros', 'title="Histórico de registros"');?></li>
                        </ul>
                    </div>
                <?php else:?>
                <div style="text-align:center;">
                    <a class="btn btn-info" title="Carregar documento" onclick="insert(<?php echo $item->key_process_id;?>,'<?php echo $item->key_process;?>','<?php echo $item->maturity_level;?>','<?php echo $item->key_practice_has_maturity_level;?>'); return false;"><i class="icon-upload icon-white"></i></a>
                </div>
                <?php endif;?>   
            </td>
        </tr>    
        <?php $previous_key_process_id = $item->key_process_id; ?>
<?php endforeach; ?>
</table>
<div id="upload_modal" class="modal hide fade">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3 id="modal_title">Carregar documento</h3>
    </div>
    <div class="modal-body">
        <div id="error_section" class="alert alert-error" style="display:none">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            <?php foreach ($error as $item => $value): ?>
                <p><?php echo $value; ?></p>
        <?php endforeach; ?>
        </div>
        <?php $attributes = array('class' => 'form-horizontal', 'onsubmit' => 'return validate_form()');
        echo form_open_multipart('process/insert', $attributes);
        ?>
        <input type="hidden" name="key_process_id" id="key_process_id" value="<?php echo set_value('key_process_id', ''); ?>" />
        <input type="hidden" name="file_path" id="file_path" value="<?php echo set_value('file_path', ''); ?>" />
        <input type="hidden" name="create_date" id="create_date" value="<?php echo set_value('create_date', ''); ?>" />

        <h4 align="center" id="upload_form_title"></h4>
        <br/>
        <div class="control-group">
            <label class="control-label" for="key_practice_id">Prática-chave</label>
            <div class="controls">
                <select id="key_practice_id" name="key_practice_id">
                    <option value="1" <?php echo set_select('key_practice_id', '1', TRUE); ?> >Em Implantação</option>
                    <option value="2" <?php echo set_select('key_practice_id', '2'); ?> >Inicial</option>
                    <option value="3" <?php echo set_select('key_practice_id', '3'); ?> >Definida</option>
                    <option value="4" <?php echo set_select('key_practice_id', '4'); ?> >Estabelecida</option>
                    <option value="5" <?php echo set_select('key_practice_id', '5'); ?> >Sistematizada</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="version">Versão</label>
            <div class="controls">
                <input type="text" class="input-mini" id="version" name="version" placeholder="00.00" value="<?php echo set_value('version', ''); ?>">
                <span class="help-inline"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="approval_date">Data aprovação</label>
            <div class="controls">
                <input type="text" class="input-small" id="approval_date" name="approval_date" placeholder="dd/mm/yyyy" value="<?php echo set_value('approval_date', ''); ?>">
                <span class="help-inline"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="approver">Aprovador</label>
            <div class="controls">
                <input type="text" class="input" id="approver" name="approver" placeholder="ex. João da Silva" value="<?php echo set_value('approver', ''); ?>">
                <span class="help-inline"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="name="userfile"">Documento</label>
            <div class="controls">
                <input class="input-file" name="userfile" id="userfile" type="file">
                <span class="help-inline"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <button type="reset" class="btn" onclick="$('#upload_modal').modal('hide');">Cancelar</button>
    </div>   
</form>
</div>
<div id="history_modal" class="modal hide fade">
<?php if (isset($history_documents)): ?>
        <div class="modal-header">
            <a class="close" data-dismiss="modal" >&times;</a>
            <h3>Histórico de registros</h3>
        </div>
        <div class="modal-body">
            <h4 align="center" id="upload_form_title"><?php echo $history_documents[0]->maturity_level . " - " . $history_documents[0]->key_process ?></h4>
            <br/>
            <table id="history_table" class="table table-striped table-bordered table-condensed">
                <tr>  
                    <th>Data</th>
                    <th>Ação</th>
                    <th>Prática-chave</th>
                    <th>Versão</th>
                    <th>Data Aprovação</th>
                    <th>Aprovador</th>
                    <th></th>
                </tr>
    <?php foreach ($history_documents as $item): ?>
                    <tr>
                        <td><?php echo date("d/m/Y H:i:s", strtotime($item->create_date)); ?></td>
                        <td><?php echo ($item->action == "insert") ? "Carga de novo documento" : "Atualização de registro"; ?></td>
                        <td><?php echo $item->key_practice; ?></td>
                        <td><?php echo $item->version; ?></td>
                        <td><?php echo $item->approval_date; ?></td>
                        <td><?php echo $item->approver; ?></td>
                        <td class="icon">
                            <?php if (!is_null($item->file_path)) { ?>
                                <a class="btn" title="Visualizar documento" href="<?php echo base_url($item->file_path); ?>"><i class="icon-file"></i></a>
        <?php } ?>
                        </td>

                    </tr>
        <?php endforeach; ?>
            </table>
        </div>
<?php endif; ?>
</div>
<script type="text/javascript">
    $(function() {
        $( "#approval_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
    });
    
    jQuery(function($){
        $("#version").numeric();
    });

    function validate_form()
    {
        clear_form();
        var error_count = 0;
        if( !$("#version").val())
        {
            $("#version").closest('div.control-group').addClass('error');
            $("#version").next('span.help-inline').text('Digite a versão');
            error_count += 1;
        }
        if( $("#approval_date").val())
        {
            if(!valid_date($("#approval_date").val()))
            {
                $("#approval_date").closest('div.control-group').addClass('error');
                $("#approval_date").next('span.help-inline').text('Data inválida.');
                error_count += 1;
            }
           
        }
        else
        {
            $("#approval_date").closest('div.control-group').addClass('error');
            $("#approval_date").next('span.help-inline').text('Digite a data de aprovação.');
            error_count += 1;
        }
        
        if( !$("#approver").val())
        {
            $("#approver").closest('div.control-group').addClass('error');
            $("#approver").next('span.help-inline').text('Digite o aprovador.');
            error_count += 1;
        }
        
        if(error_count > 0)
            return false;
        
    }
    
    function clear_form()
    {
        $("div.control-group").removeClass('error');
        $("span.help-inline").text('');
    }
    
  

</script>