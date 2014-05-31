<?php $this->load->view('template/header', $header); ?>

<div>
    <a href="<?php echo site_url('/selection_process/create/') ?>"><?php lang('create_selection_process') ?></a>
</div>

<?php

require_once('views/table_init.php');

$this->table->set_heading(lang('view'), lang('name'), lang('selection_process_description'), lang('start_date'), lang('end_date'), lang('start_date_subscription'), lang('end_date_subscription'), lang('edict'), lang('start_date_pre_incubation'), lang('end_date_pre_incubation'), lang('start_date_incubation'), lang('end_date_incubation'), lang('edit'), lang('delete'));
foreach ( $result as $row ):

    $this->table->add_row(
            '<a href="' . site_url('/selection_process/show_detail/' . $row->id) . '">' . lang('view_details') . '</a>',
            $row->name,
            !empty($row->description) ? $row->description : '-',
            brazilian_datetime($row->start_date),
            brazilian_datetime($row->end_date),
            brazilian_datetime($row->start_subscription),
            brazilian_datetime($row->end_subscription),
            '<a href="' . base_url() . $row->file_name . '">' . lang('edict') . '</a>',
            brazilian_datetime($row->start_date_pre_incubation) != FALSE ? brazilian_datetime($row->start_date_pre_incubation) : '-',
            brazilian_datetime($row->end_date_pre_incubation) != FALSE ? brazilian_datetime($row->end_date_pre_incubation) : '-',
            brazilian_datetime($row->start_date_incubation) != FALSE ? brazilian_datetime($row->start_date_incubation) : '-',
            brazilian_datetime($row->end_date_incubation) != FALSE ? brazilian_datetime($row->end_date_incubation) : '-',
            '<a href="' . site_url('/selection_process/show_object_sought/' . $row->id) . '">' . lang('edit') . '</a>',
            '<a href="' . site_url('/selection_process/delete/' . $row->id) . '">' . lang('delete') . '</a>');

endforeach;
echo $this->table->generate();
?>

<?php $this->load->view('template/footer'); ?>