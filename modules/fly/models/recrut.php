<?php

class Recrut extends DataMapperExt {

    var $table = 'recruts';
    var $default_order_by = array('id' => 'DESC');
    public $has_one = array(
        'conta'
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    function is_recrute_done($logged_dt, $check_online = FALSE, $cod = '') {
        if ($check_online) {
            $html = $logged_dt->execute_recrute($cod);
            if (strpos($html, 'You have reached the maximum amount of credits from the recruiter for today.')) {
                return TRUE;
            }
            return $html;
        }
        // Uma query para receber o maximo
        $this->select('max(clicked) as clicked_max');
        $this->where('create_time > ', date('Y-m-d 03:00:00', time()));
        $this->where_related($logged_dt);
        $this->get(1);

        // Outra para receber os demais campos
        $this->where('create_time > ', date('Y-m-d 03:00:00', time()));
        $this->where_related($logged_dt);
        $this->get(1);

        $this->clicked = $this->clicked_max;

        if ($this->clicked_max == 375) {
            return TRUE;
        }
        return FALSE;
    }

}

?>