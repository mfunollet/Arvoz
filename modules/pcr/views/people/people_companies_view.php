

<div id="sub_menu">
    <?php echo anchor('companies/create', lang('add_company'), array('data-toggle'=>"modal", 'title'=>lang('add_company'), 'class'=>'btn btn-mini btn-success')); ?>   
</div>
<?php if ($element->exists()): ?>
    <table id="<?php echo $this->ctrlr_name ?>_list" class="table table-striped table-bordered table-condensed">
        <tr id="<?php echo $ctrlr ?>_<?php echo $element->id ?>">                   
            <th><?php echo lang('company') ?></h3></td>
            <th><?php echo lang('role') ?></td>
            <th><?php echo lang('job') ?></td>
            <th><?php echo lang('start_date') ?></td>
            <th><?php echo lang('actions') ?></td>
        </tr>
        <?php foreach ($element as $item) : ?>
        <tr id="<?php echo $ctrlr ?>_<?php echo $item->id ?>">                   
                <td><?php echo anchor('companies/view/' . $item->company->id, $item->company->name) ?></h3></td>
                <td><?php echo $item->role->name; ?></td>
                <td><?php echo $item->job; ?></td>
                <td><?php echo $item->start_date; ?></td>
                <td>
                    <?php if ($item->person->id != $item->company->owner->id): ?>
                        <?php echo anchor('people/leave_company/' . $item->id, lang('leave_role'), array('class'=>'btn btn-danger btn-mini delete', 'data-id'=>$item->id, 'data-model'=>$ctrlr)) ?>                    
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>





