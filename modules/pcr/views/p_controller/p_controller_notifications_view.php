<?php if (!empty($element->notifications)): ?>
    <?php foreach ($element->notifications as $id => $not): ?>
        <div class="notification">
            <?php echo sprintf(lang('notification_invite'), $not['name'], $not['role']); ?> 
            <?php echo anchor('people/respond_invite/'.$not['type'].'/'. $id . '/true', lang('accept'), 'class="btn btn-mini btn-success"'); ?> 
            <?php echo anchor('people/respond_invite/'.$not['type'].'/'. $id, lang('refuse'), 'class="btn btn-mini btn-danger"'); ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?php echo lang('you_have_no_notifications') ?>
<?php endif; ?>