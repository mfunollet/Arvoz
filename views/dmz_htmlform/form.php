<?php
if (!isset($save_button)) {
    $save_button = 'save';
}
if (!isset($reset_button)) {
    $reset_button = FALSE;
} else {
    if ($reset_button === TRUE) {
        $reset_button = 'reset';
    }
}
?>
<?php if (!empty($object->error->all)): ?>
    <div class="error">
        <p><?php echo lang('there_was_an_error_saving_the_form') ?></p>
        <ul><?php foreach ($object->error->all as $k => $err): ?>
                <li><?php echo $err; ?></li>
    <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php $form_class = isAjax() ? 'ajax' : ''; ?>
<?php if (isset($this->thumbnails)): ?>
    <?php echo form_($this->config->site_url($url), 'class="form-horizontal '.$form_class.'"'); ?>
<?php else: ?>
    <?php echo form_open_multipart($this->config->site_url($url), 'class="form-horizontal '.$form_class.'"'); ?>
<?php endif; ?>
<div class="well">
<?php echo $rows; ?>
    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?php echo lang('form_' . $save_button); ?>" /><?php
if ($reset_button !== FALSE) {
    ?> <input class="btn" type="reset" value="<?php echo lang('form_' . $reset_button); ?>" /><?php
}
?>
    </div>
</div>
</form>
