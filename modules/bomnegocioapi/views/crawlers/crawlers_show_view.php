<div id="sub_menu">
    <?php echo anchor('crawlers/create', 'Adicionar busca', array('data-toggle'=>"modal", 'title'=>'Adicionar busca', 'class'=>' auto-close btn btn-mini btn-success')); ?>   
    <?php echo anchor('api', 'Voltar', array('title'=>lang('add_site') ) ); ?>   
</div>

<?php if ($element->exists()): ?>
    <table id="<?php echo $this->ctrlr_name ?>_list" class="table-striped table-bordered table-condensed">
            <?php foreach ($element as $item) : ?>
            <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">
                    <td><a href="<?php echo $item->getUrl() ?>" target="_blank"><?php echo $item->keyword ?></a></td>
                    <td><a class='edit btn btn-mini btn-info auto-close' data-toggle='modal' title="<?php echo lang('edit_site') ?>" href="<?php echo site_url('crawlers/edit/' . $item->id) ?>"><?php echo lang('edit_site') ?></a></td>
                    <td><a class='delete btn btn-mini btn-danger auto-close' data-id="<?php echo $item->id ?>" data-model="<?php echo $ctrlr ?>" href="<?php echo site_url('crawlers/delete/' . $item->id) ?>"><?php echo lang('delete') ?></a></td>                    
                </tr>
            <?php endforeach; ?>
    </table>
<?php endif; ?>