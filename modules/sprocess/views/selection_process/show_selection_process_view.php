<?php $this->load->view('template/header'); ?>

<?php

require_once('views/table_init.php');

$this->table->add_row(lang('name'), $result->name);
$this->table->add_row(lang('selection_process_description'), !empty($result->description) ? $result->description : '-');
$this->table->add_row(lang('start_date'), brazilian_datetime($result->start_date));
$this->table->add_row(lang('end_date'), brazilian_datetime($result->end_date));
$this->table->add_row(lang('start_date_subscription'), brazilian_datetime($result->start_subscription));
$this->table->add_row(lang('end_date_subscription'), brazilian_datetime($result->end_subscription));
$this->table->add_row(lang('edict'), !empty($result->file_name) ? '<a href="' . base_url() . $result->file_name . '">' . lang('view_edict') . '</a>' : lang('not_exist_edict'));
$this->table->add_row(lang('start_date_pre_incubation'), brazilian_datetime($result->start_date_pre_incubation) != FALSE ? brazilian_datetime($result->start_date_pre_incubation) : '-');
$this->table->add_row(lang('end_date_pre_incubation'), brazilian_datetime($result->end_date_pre_incubation) != FALSE ? brazilian_datetime($result->end_date_pre_incubation) : '-');
$this->table->add_row(lang('start_date_incubation'), brazilian_datetime($result->start_date_incubation) != FALSE ? brazilian_datetime($result->start_date_incubation) : '-');
$this->table->add_row(lang('end_date_incubation'), brazilian_datetime($result->end_date_incubation) != FALSE ? brazilian_datetime($result->end_date_incubation) : '-');
echo $this->table->generate();

echo '<br />';
$hidden = array(
    'id' => $result->id);
echo form_open(site_url('/selection_process/show_submit_file_name/'), '', $hidden);
echo form_submit('submit', lang('submit_edict'));
echo form_close();

echo form_open(site_url('/selection_process/show_object_sought/' . $result->id));
echo form_submit('submit', lang('update_selection_process'));
echo form_close();

echo form_open(site_url('/selection_process/delete/' . $result->id));
echo form_submit('submit', lang('delete_selection_process'));
echo form_close();

echo '<br />';
echo '<h2>' . lang('operations') . '</h2>';
echo '<br />';

echo form_open(site_url('/enterprise/create_enterprise/' . $result->id));
echo form_submit('submit', lang('register_enterprise'));
echo form_close();

echo form_open(site_url('/delivery/mass_request_document_delivery_call/' . $result->id));
echo form_submit('submit', lang('document_delivery_request'));
echo form_close();

echo form_open(site_url('/interview/show_by_selection_process/' . $result->id));
echo form_submit('submit', lang('view_interviews_scheduled'));
echo form_close();

echo form_open(site_url('/enterprise/pre_select_enterprise/' . $result->id));
echo form_submit('submit', lang('pre_select_enterprises'));
echo form_close();

echo form_open(site_url('/enterprise/evaluate_enterprise_classified/' . $result->id));
echo form_submit('submit', lang('evaluate_enterprise_classified'));
echo form_close();

echo form_open(site_url('/enterprise/classify_enterprises_pre_incubation/' . $result->id));
echo form_submit('submit', lang('classify_enterprises_pre_incubation'));
echo form_close();

echo form_open(site_url('/enterprise/classify_enterprises_incubation/' . $result->id));
echo form_submit('submit', lang('classify_enterprises_incubation'));
echo form_close();
?>

<?php $this->load->view('template/footer'); ?>
