<div id="sub_menu">
    <?php echo anchor('sites/create', lang('add_site'), array('data-toggle'=>"modal", 'title'=>lang('add_site'), 'class'=>' auto-close btn btn-mini btn-success')); ?>   
</div>

<?php if ($element->exists()): ?>
    <table id="<?php echo $this->ctrlr_name ?>_list" class="table-striped table-bordered table-condensed">
            <?php foreach ($element as $item) : ?>
            <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">
                    <td class="icon"><img src="<?php echo $item->get_favicon() ?>"></td>
                    <td><a href="<?php echo $item->url ?>" target="_blank"><?php echo $item->name ?></a></td>
                    <td><a class='edit btn btn-mini btn-info auto-close' data-toggle='modal' title="<?php echo lang('edit_site') ?>" href="<?php echo site_url('sites/edit/' . $item->id) ?>"><?php echo lang('edit_site') ?></a></td>
                    <td><a class='delete btn btn-mini btn-danger auto-close' data-id="<?php echo $item->id ?>" data-model="<?php echo $ctrlr ?>" href="<?php echo site_url('sites/delete/' . $item->id) ?>"><?php echo lang('delete') ?></a></td>                    
                </tr>
            <?php endforeach; ?>
    </table>
<?php endif; ?>
