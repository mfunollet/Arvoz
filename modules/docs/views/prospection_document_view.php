<?php
/**
* View for prospection document submission
*
* @copyright 2012 ARQABS
* @version    
*/
?>
<h2>Gestão de Prospecção de Empreendimentos</h2>
<h3>Prospecção</h3>
<br/>
<div id="success_alert" class="alert alert-success" style="display:none">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
    Operação realizada com sucesso!
</div>
<br/>

<?php
    if(count($query) == 0)
    {
?>
<h4>Nenhum registro.</h4>
<p><a href="<?php echo site_url('prospection/create_document')?>">Inserir novo Documento</a></p>
<?php return; }?>
<table id="prospection_table" class="table table-striped table-bordered table-condensed">
    <tr>
        <th>Ideia</th>
        <th>Data</th>
        <th>Responsável</th>
        <th></th>
    </tr>
    <?php foreach($query as $item):?>
    <tr>
        <td><?php echo wordwrap($item->description, 30, '<br />', 1);?></td>
        <td><?php 
            if($item->action_date != NULL)
            {
                echo date ("d/m/Y",strtotime($item->action_date));
            }?></td>
        <td><?php echo wordwrap($item->owner, 30, '<br />', 1);?></td>
        <td style="width:60px">
            <div class="btn-group">
                <button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-th-list icon-white"></i> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a <?php echo (empty($item->file_path)) ? ' class="disabled"' : ' target="_blank" href="'. base_url($item->file_path) . '"'; ?>"><i class="icon-list-alt"></i> Baixar sumário executivo</a></li>
                    <li><a href="<?php echo site_url('prospection/edit_document/' . $item->id) ;?>"><i class="icon-pencil"></i> Atualizar</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="return delete_checked('<?php echo site_url('prospection/delete_document/' . $item->id);?>');"><i class="icon-trash"></i> Excluir</a></li>
                </ul>
            </div>
        </td>
        
    </tr>
    
    <?php endforeach;?>
</table>

<p><a href="<?php echo site_url('prospection/create_document')?>">Inserir novo sumário executivo</a></p>


<script type="text/javascript">
    
     $(document).ready(function() {
        <?php
        
            if( $this->uri->segment(4) == "success"){
                echo "$('#success_alert').show();";
            }
        ?>
        
    });
    
</script>