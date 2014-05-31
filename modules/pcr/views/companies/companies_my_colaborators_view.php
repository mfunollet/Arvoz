<div id="sub_menu">
    <?php echo anchor('companies/invite', lang('add_colaborator'), array('data-toggle' => "modal", 'title' => lang('add_colaborator'), 'class' => 'btn btn-mini btn-success')); ?>   
</div>
<?php if ($element->exists()): ?>
    <table id="<?php echo $this->ctrlr_name ?>_list" class="table table-striped table-bordered table-condensed">
        <tr id="<?php echo $ctrlr ?>_<?php echo $element->id ?>">                   
            <th><?php echo lang('name') ?></h3></td>
            <th><?php echo lang('role') ?></td>
            <th><?php echo lang('job') ?></td>
            <th><?php echo lang('status') ?></td>
            <th><?php echo lang('start_date') ?></td>
            <th><?php echo lang('actions') ?></td>
        </tr>
        <?php foreach ($element as $item) : ?>
            <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">                   
                <td><?php echo anchor('people/view/' . $item->person->id, $item->person->first_name . ' ' . $item->person->last_name) ?></h3></td>
                <td><?php echo $item->role->name; ?></td>
                <td><?php echo $item->job; ?></td>
                <td><?php echo $item->status; ?></td>
                <td><?php echo $item->start_date; ?></td>
                <td> 
                    <span class="right">
                        <?php echo anchor('companies/invite/' . $item->id, lang('edit'), array('class' => 'btn btn-info btn-mini edit', 'data-id' => $item->id, 'data-toggle' => 'modal', 'data-model' => $ctrlr, 'title' => lang('edit'))) ?>
                        <?php echo anchor('companies/remove_user/' . $item->person->id . '/' . $item->role->id, lang('remove_from_role'), array('class' => 'btn btn-warning btn-mini delete', 'data-id' => $item->id, 'data-model' => $ctrlr)) ?>
                        <?php if ($item->person->id != $logged_company->owner->id): ?>
                            <?php echo anchor('companies/invite_delete/' . $item->id, lang('delete'), array('class' => 'btn btn-danger btn-mini delete', 'data-id' => $item->id, 'data-model' => $ctrlr)) ?>
                        <?php endif; ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>





