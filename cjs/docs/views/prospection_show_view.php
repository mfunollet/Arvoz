<?php
/**
* View for prospection show
*
* @copyright 2012 ARQABS
* @version    
*/
?>
<?php $venture_prospection_type_name = ($venture_prospection_type_id == 1)?"Sensibilização": (($venture_prospection_type_id == 2)?"Prospecção" : "Qualificação");?>
<h2>Gestão de Prospecção de Empreendimentos</h2>
<h3><?php echo $venture_prospection_type_name; ?></h3>
<br/>
<div id="success_alert" class="alert alert-success" style="display:none">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
    Operação realizada com sucesso!
</div>

<?php
    if(count($query) == 0)
    {
?>
<h4>Nenhum registro.</h4>
<p><a href="<?php echo site_url('prospection/create/' . $venture_prospection_type_id)?>">Inserir nova Ação</a></p>
<?php return; }?>

<table id="prospection_table" class="table table-striped table-bordered table-condensed">
    <tr>
        <th>Ação</th>
        <th>Tema</th>
        <th>Responsável</th>
        <th>Data</th>
        <th>Local</th>
        <th>Carga horária</th>
        <th>Total de participantes</th>
        <th>Conteúdo sumarizado</th>
        <th></th>
    </tr>
    <?php foreach($query as $item):?>
    <tr>
        <td><?php echo $item->prospection_action;?></td>
        <td><?php echo wordwrap($item->description, 8, '<br />',1);?></td>       
        <td><?php echo wordwrap($item->owner,8,'<br />',1);?></td>
        <td><?php 
            if($item->action_date != NULL)
            {
                echo date ("d/m/Y",strtotime($item->action_date));
            }?></td>
        <td><?php echo wordwrap($item->place,8,'<br />',1) ;?></td>
        <td style="text-align:right"><?php echo $item->workload;?></td>
        <td style="text-align:right"><?php echo $item->attendees_number;?></td>
        <td><?php 
            $wrapped_text = wordwrap($item->content_text, 12, '<br />', 1);
            $wrapped_text1 = wordwrap($item->content_text, 34, '<br />', 1);
        ?>
            
            <?php echo (strlen($wrapped_text) > 100) ?  substr($wrapped_text, 0, 100) . '...' : $wrapped_text;?>&nbsp;<?php if(strlen($wrapped_text) > 100) { ?> <span class="label label-info" rel="popover1" data-content="<?php echo $wrapped_text1;?>" data-original-title="Conteúdo Sumarizado">mais...</span> <?php } ?></td>
        <td style="width:60px">
            <div class="btn-group">
                <button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-th-list icon-white"></i> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a <?php echo (empty($item->document_path)) ? ' class="disabled"' : ' target="_blank" href="'. base_url($item->document_path) . '"'; ?>"><i class="icon-list-alt"></i> Baixar lista de participantes</a></li>
                    <li><a <?php echo (empty($item->photo_path)) ? ' class="disabled"' : ' target="_blank" href="'. base_url($item->photo_path) . '"'; ?>"><i class="icon-camera"></i> Ver foto da ação</a></li>
                    <li><a href="<?php echo site_url('prospection/edit/' . $venture_prospection_type_id . '/' . $item->id) ;?>"><i class="icon-pencil"></i> Atualizar</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="return delete_checked('<?php echo site_url('prospection/delete/' . $venture_prospection_type_id . '/' . $item->id);?>');"><i class="icon-trash"></i> Excluir</a></li>
                </ul>
            </div>
        </td>
        
    </tr>
    
    <?php endforeach;?>
</table>

<p><a href="<?php echo site_url('prospection/create/' . $venture_prospection_type_id)?>">Inserir nova Ação</a></p>


<script type="text/javascript">
    
     $(document).ready(function() {
        <?php
        
            if( $this->uri->segment(4) == "success"){
                echo "$('#success_alert').show();";
            }
        ?>
        $("span[rel='popover1']").popover();
    });
    
</script>