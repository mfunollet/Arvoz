<?php
/**
* View for management planning show
*
* @copyright 2012 ARQABS
* @version    
*/
?>
<h2>Módulo Gestão do Planejamento</h2>
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
<p><a href="<?php echo site_url('planning/create')?>">Inserir novo planejamento</a></p>
<?php return; }?>

<table id="planning_table" class="table table-striped table-bordered table-condensed">
    <tr>
        <th>Planejamento</th>
        <th>Empreendimento</th>
        <th>Empreendedor</th>
        <th>Versão</th>
        <th>Data da Aprovação</th>
        <th></th>
        
    </tr>
    <?php foreach($query as $item):?>
    <tr>
        <td><?php echo $item->planning_type;?></td>
        <td><?php echo wordwrap($item->venture, 14, '<br />', 1);?></td>
        <td><?php echo wordwrap($item->entrepreneur,14,'<br />',1);?></td>
        <td><?php echo $item->version;?></td>
        <td><?php 
            if($item->approval_date != NULL)
            {
                echo date ("d/m/Y",strtotime($item->approval_date));
            }?></td>
        <td style="width:60px">
            <div class="btn-group">
                <button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-th-list icon-white"></i> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a <?php echo (empty($item->file_path)) ? ' class="disabled"' : ' target="_blank" href="'. base_url($item->file_path) . '"'; ?>"><i class="icon-list-alt"></i> Baixar documento</a></li>
                    <li><a href="<?php echo site_url('planning/edit/' . $item->id) ;?>"><i class="icon-pencil"></i> Atualizar</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="return delete_checked('<?php echo site_url('planning/delete/' . $item->id);?>');"><i class="icon-trash"></i> Excluir</a></li>
                </ul>
            </div>
        </td>
        <!--<td></td>-->
        
    </tr>
    
    <?php endforeach;?>
</table>

<p><a href="<?php echo site_url('planning/create')?>">Inserir novo planejamento</a></p>


<script type="text/javascript">
    
     $(document).ready(function() {
        <?php
        
            if( $this->uri->segment(3) == "success"){
                echo "$('#success_alert').show();";
            }
        ?>
        
    });
    
</script>