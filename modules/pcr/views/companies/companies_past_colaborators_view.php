

<div id="sub_menu">
    <?php echo anchor('companies/invite', lang('add_colaborator'), array('data-toggle'=>"modal", 'title'=>lang('add_site'), 'class'=>'btn btn-mini btn-success')); ?>   
</div>
<?php if ($element->exists()): ?>
    <table id="<?php echo $this->ctrlr_name ?>_list" class="table table-striped table-bordered table-condensed">
        <tr id="<?php echo $ctrlr ?>_<?php echo $element->id ?>">                   
            <th><?php echo lang('name') ?></h3></td>
            <th><?php echo lang('job') ?></td>
            <th><?php echo lang('start_date') ?></td>
            <th><?php echo lang('end_date') ?></td>
            <th><?php echo lang('actions') ?></td>
        </tr>
        <?php foreach ($element as $item) : ?>
        <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">                   
                <td><?php echo anchor('people/view/' . $item->person->id, $item->person->first_name . ' ' . $item->person->last_name) ?></h3></td>
                <td><?php echo $item->job; ?></td>
                <td><?php echo $item->start_date; ?></td>
                <td><?php echo $item->end_date; ?></td>
                <td><?php echo anchor('companies/re_invite/' . $item->person->id.'/'.$item->role->id, lang('re_invite'), array('class'=>'btn btn-warning btn-mini delete', 'data-id'=>$item->id, 'data-model'=>$ctrlr)) ?>
                    
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>





