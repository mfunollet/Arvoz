<div class="well">
<?php
$hidden = array('id' => $id);
echo form_open($this->ctrlr_name.'/contact/'.$id, '', $hidden); ?>

    <div class="control-group">
        <label class="control-label"><?php echo lang('message'); ?>:</label>
	<div class="controls">
		<?php echo form_textarea(array(
              'name'        => 'message',
              'id'          => 'msg',
              'style'       => 'width:50%',
            )); ?>
	</div>
    </div>
    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?php echo lang('form_send'); ?>" />
    </div>
</form>
</div>