<?php $this->load->view('template/header'); ?>

<?php

$hidden = array(
    'id' => $result->id,
    'file_name' => $result->file_name
);
echo form_open_multipart(site_url('/selection_process/submit_file_name/'), '', $hidden);
echo form_fieldset(lang('upload_edict_sp'));
echo '<br />';

if ( !empty($result->file_name) )
{
    echo '<a href="' . base_url() . $result->file_name . '">' . lang('view_edict') . '</a><br />';
}
else
{
    echo lang('upload_edict_not_exist') . '<br />';
}

echo form_error('userfile');
echo form_label(lang('upload_edict_sp') . ':', 'userfile');
echo form_upload(array('name' => 'userfile'));

echo form_fieldset_close();
echo form_submit('submit', lang('do_upload'));
echo form_close();
?>

<?php $this->load->view('template/footer'); ?>