<?php $this->load->view('template/header'); ?>

<?php

if ( strcmp($path, 'create') != 0 )
{//path == update
    $hidden = array(
        'id' => $result->id,
        'old_file_name' => $result->file_name,
        'incubation_id' => $result->incubation_id,
        'pre_incubation_id' => $result->pre_incubation_id
    );
    echo form_open(site_url('/selection_process/update/'), '', $hidden);
    echo form_fieldset(lang('register_selection_process'));
    echo '<br />';

    echo form_error('name');
    echo form_label(lang('selection_process_name') . ':', 'name');
    echo form_input(array('name' => 'name', 'value' => $result->name));
    echo '<br />';

    echo form_error('description');
    echo form_label(lang('selection_process_description') . ':', 'description');
    echo '<br />';
    echo form_textarea(array('name' => 'description', 'value' => $result->description));
    echo '<br />';

    echo form_error('start_date');
    echo form_label(lang('start_date') . ':', 'start_date');
    $sp_start_date = brazilian_datetime($result->start_date) != FALSE ? brazilian_datetime($result->start_date) : '';
    echo form_input(array('name' => 'start_date', 'id' => 'datepicker_start', 'class' => 'datepicker', 'value' => $sp_start_date));
    echo '<br />';

    echo form_error('end_date');
    echo form_label(lang('end_date') . ':', 'end_date');
    $sp_end_date = brazilian_datetime($result->end_date) != FALSE ? brazilian_datetime($result->end_date) : '';
    echo form_input(array('name' => 'end_date', 'id' => 'datepicker_end', 'class' => 'datepicker', 'value' => $sp_end_date));
    echo '<br />';

    echo form_error('start_subscription');
    echo form_label(lang('start_date_subscription') . ':', 'start_subscription');
    $sp_start_subscription = brazilian_datetime($result->start_subscription) != FALSE ? brazilian_datetime($result->start_subscription) : '';
    echo form_input(array('name' => 'start_subscription', 'id' => 'datepicker_start_subscription', 'class' => 'datepicker', 'value' => $sp_start_subscription));
    echo '<br />';

    echo form_error('end_subscription');
    echo form_label(lang('end_date_subscription') . ':', 'end_subscription');
    $sp_end_subscription = brazilian_datetime($result->end_subscription) != FALSE ? brazilian_datetime($result->end_subscription) : '';
    echo form_input(array('name' => 'end_subscription', 'id' => 'datepicker_end_subcription', 'class' => 'datepicker', 'value' => $sp_end_subscription));
    echo '<br />';

    echo '<a href="' . base_url() . $result->file_name . '">' . lang('view_edict_current') . '</a> <br />';

    //PRE-INCUBATION CHECKBOX
    if ( isset($_POST['pre_incubation_start_date']) )
    {
        $array_pre_incubation = TRUE;
        $pi_start_date = set_value('pre_incubation_start_date');
        $pi_end_date = set_value('pre_incubation_end_date');
    }
    else if ( empty($result->start_date_pre_incubation) )
    {
        $array_pre_incubation = FALSE;
        $pi_start_date = brazilian_datetime($result->start_date_pre_incubation) != FALSE ? brazilian_datetime($result->start_date_pre_incubation) : '';
        $pi_end_date = brazilian_datetime($result->end_date_pre_incubation) != FALSE ? brazilian_datetime($result->end_date_pre_incubation) : '';
    }
    else
    {
        $array_pre_incubation = TRUE;
        $pi_start_date = brazilian_datetime($result->start_date_pre_incubation) != FALSE ? brazilian_datetime($result->start_date_pre_incubation) : '';
        $pi_end_date = brazilian_datetime($result->end_date_pre_incubation) != FALSE ? brazilian_datetime($result->end_date_pre_incubation) : '';
    }
    echo form_fieldset(lang('select_pre_incubation'));
    echo form_label(lang('pre_incubation'), 'pre_incubation_checkbox');
    echo form_checkbox(array('name' => 'pre_incubation_checkbox', 'value' => 'pre_incubation', 'checked' => set_checkbox('pre_incubation_checkbox', 'pre_incubation', $array_pre_incubation)));
    echo '<br />';

    echo form_error('pre_incubation_start_date');
    echo form_label(lang('start_date_pre_incubation') . ':', 'pre_incubation_start_date');
    echo form_input(array('name' => 'pre_incubation_start_date', 'id' => 'datepicker_pre_incubation_start', 'class' => 'datepicker', 'value' => $pi_start_date));
    echo '<br />';

    echo form_error('pre_incubation_end_date');
    echo form_label(lang('end_date_pre_incubation') . ':', 'pre_incubation_end_date');
    echo form_input(array('name' => 'pre_incubation_end_date', 'id' => 'datepicker_pre_incubation_end', 'class' => 'datepicker', 'value' => $pi_end_date));
    echo form_fieldset_close();


    //INCUBATION CHECKBOX
    if ( isset($_POST['incubation_start_date']) )
    {
        $array_incubation = TRUE;
        $i_start_date = set_value('incubation_start_date');
        $i_end_date = set_value('incubation_end_date');
    }
    else if ( empty($result->start_date_incubation) )
    {
        $array_incubation = FALSE;
        $i_start_date = brazilian_datetime($result->start_date_incubation) != FALSE ? brazilian_datetime($result->start_date_incubation) : '';
        $i_end_date = brazilian_datetime($result->end_date_incubation) != FALSE ? brazilian_datetime($result->end_date_incubation) : '';
    }
    else
    {
        $array_incubation = TRUE;
        $i_start_date = brazilian_datetime($result->start_date_incubation) != FALSE ? brazilian_datetime($result->start_date_incubation) : '';
        $i_end_date = brazilian_datetime($result->end_date_incubation) != FALSE ? brazilian_datetime($result->end_date_incubation) : '';
    }
    echo form_fieldset(lang('select_incubation'));
    echo form_label(lang('incubation'), 'incubation_checkbox');
    echo form_checkbox(array('name' => 'incubation_checkbox', 'value' => 'incubation', 'checked' => set_checkbox('incubation_checkbox', 'incubation', $array_incubation)));
    echo '<br />';

    echo form_error('incubation_start_date');
    echo form_label(lang('start_date_incubation') . ':', 'incubation_start_date');
    echo form_input(array('name' => 'incubation_start_date', 'id' => 'datepicker_incubation_start', 'class' => 'datepicker', 'value' => $i_start_date));
    echo '<br />';

    echo form_error('incubation_end_date');
    echo form_label(lang('end_date_incubation') . ':', 'incubation_end_date');
    echo form_input(array('name' => 'incubation_end_date', 'id' => 'datepicker_incubation_end', 'class' => 'datepicker', 'value' => $i_end_date));
    echo form_fieldset_close();


    echo form_fieldset_close();
    echo form_submit('submit', lang('edit_save'));
    echo form_close();
}
else
{
    //CREATE
    echo form_open_multipart(site_url('/selection_process/create/'));
    echo form_fieldset(lang('register_selection_process'));
    echo '<br />';

    echo form_error('name');
    echo form_label(lang('selection_process_name') . ':', 'name');
    echo form_input(array('name' => 'name', 'value' => set_value('name')));
    echo '<br />';

    echo form_error('description');
    echo form_label(lang('selection_process_description') . ':', 'description');
    echo '<br />';
    echo form_textarea(array('name' => 'description', 'value' => set_value('description')));
    echo '<br />';

    echo form_error('start_date');
    echo form_label(lang('start_date') . ':', 'start_date');
    echo form_input(array('name' => 'start_date', 'id' => 'datepicker_start', 'class' => 'datepicker', 'value' => set_value('start_date')));
    echo '<br />';

    echo form_error('end_date');
    echo form_label(lang('end_date') . ':', 'end_date');
    echo form_input(array('name' => 'end_date', 'id' => 'datepicker_end', 'class' => 'datepicker', 'value' => set_value('end_date')));
    echo '<br />';


    echo form_error('start_subscription');
    echo form_label(lang('start_date_subscription') . ':', 'start_subscription');
    echo form_input(array('name' => 'start_subscription', 'id' => 'datepicker_start_subscription', 'class' => 'datepicker', 'value' => set_value('start_subscription')));
    echo '<br />';

    echo form_error('end_subscription');
    echo form_label(lang('end_date_subscription') . ':', 'end_subscription');
    echo form_input(array('name' => 'end_subscription', 'id' => 'datepicker_end_subscription', 'class' => 'datepicker', 'value' => set_value('end_subscription')));
    echo '<br />';


    echo form_fieldset(lang('marque o checkbox para cadastrar o periodo de pré-incubação'));
    echo form_label(lang('pre_incubation'), 'pre_incubation_checkbox');
    echo form_checkbox(array('name' => 'pre_incubation_checkbox', 'value' => 'pre_incubation', 'checked' => set_checkbox('pre_incubation_checkbox', 'pre_incubation', TRUE)));
    echo '<br />';

    echo form_error('pre_incubation_start_date');
    echo form_label(lang('start_date_pre_incubation') . ':', 'pre_incubation_start_date');
    echo form_input(array('name' => 'pre_incubation_start_date', 'id' => 'datepicker_pre_incubation_start', 'class' => 'datepicker', 'value' => set_value('pre_incubation_start_date')));
    echo '<br />';

    echo form_error('pre_incubation_end_date');
    echo form_label(lang('end_date_pre_incubation') . ':', 'pre_incubation_end_date');
    echo form_input(array('name' => 'pre_incubation_end_date', 'id' => 'datepicker_pre_incubation_end', 'class' => 'datepicker', 'value' => set_value('pre_incubation_end_date')));
    echo form_fieldset_close();




    echo form_fieldset(lang('select_incubation'));
    echo form_label(lang('incubation'), 'incubation_checkbox');
    echo form_checkbox(array('name' => 'incubation_checkbox', 'value' => 'incubation', 'checked' => set_checkbox('incubation_checkbox', 'incubation', TRUE)));
    echo '<br />';

    echo form_error('incubation_start_date');
    echo form_label(lang('start_date_incubation') . ':', 'incubation_start_date');
    echo form_input(array('name' => 'incubation_start_date', 'id' => 'datepicker_incubation_start', 'class' => 'datepicker', 'value' => set_value('incubation_start_date')));
    echo '<br />';

    echo form_error('incubation_end_date');
    echo form_label(lang('end_date_pre_incubation') . ':', 'incubation_end_date');
    echo form_input(array('name' => 'incubation_end_date', 'id' => 'datepicker_incubation_end', 'class' => 'datepicker', 'value' => set_value('incubation_end_date')));
    echo form_fieldset_close();




    echo form_fieldset_close();
    echo form_submit('submit', lang('register'));
    echo form_close();
}
?>

<?php $this->load->view('template/footer'); ?>