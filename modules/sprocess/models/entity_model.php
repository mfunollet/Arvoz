<?php

/**
 * Base file to implement a model.
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
class Entity_model extends CI_Model {

    /**
     * @var string contains the primary key of the table
     */
    var $id = 'id';

    /**
     * @var string name of the table
     */
    var $table;

    /**
     * @var string contains the name of table view for this entity
     */
    var $table_view = NULL;

    /**
     * @var string contains the name class that maps a row in this table
     */
    var $data_type = 'Data_object';

    /**
     * @var array contains list of date fields in this table
     */
    var $date_fields = array();

    /**
     * entity model construct - If the variable data_type is populated,
     * will be included a file with your name
     *
     */
    function __construct()
    {
        parent:: __construct();
        if ( $this->data_type != '' )
        {
            include_once(strtolower($this->data_type) . '.php');
        }
        $this->rel = $this->config->item('rel');
    }

    /**
     * Function to get name of table or view for use in retreive querys
     *
     * @access private
     * @return string return the name of the table or view for this entity
     */
    function _get_read_table()
    {
        if ( $this->table_view )
            return $this->table_view;
        return $this->table;
    }

    /**
     * function to be extended to define the fields that the entity needs, 
     * to define the fields to search with the function search()
     *    
     * @return array
     */
    function get_search_columns()
    {
        return array();
    }

    /**
     * Returning the search where there are records of occurrence specified 
     * in the search string
     *
     * @param  string  $match contains the piece of string or text that you need in the result query
     * @return array
     */
    function search($match)
    {
        $terms = $this->get_search_columns();
        foreach ( $terms as $term )
        {
            $this->db->or_like($term, $match, 'both');
        }

        $this->table = $this->table_view;
        return $this->get_all();
    }

    /**
     * returns the number lines that a table has
     *
     * @return integer
     */
    function count()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * Generates a paginated query.
     *
     * You can modify the number of lines of the page with $limit parameter,
     *  and, inform the page that you need in the $offset parameter.
     *
     * @param  int $limit Number of lines on a page
     * @param  int $offset Page you want to display/obtain
     * @return array
     */
    function get_page($limit = 10, $offset = 0)
    {
        $this->db->limit($limit, $offset);
        return $this->get_all();
    }

    /**
     * Inserts a row into a table in the database
     *
     * The param "$data" accept 2 types of variable, array or object, if the 
     * param is an object, the function filterInputArray() will transform 
     * this for array.
     *
     * @param  array  $data  array of data for insert
     * @return integer
     */
    function insert($data)
    {
        $date_time = date("Y-m-d H:i:s", time());
        $data['create_time'] = $date_time;
        $data['update_time'] = $date_time;
        $new_data = $this->filterInputArray($data);
        $this->db->insert($this->table, $new_data);
        $id = $this->db->insert_id();
        return $id == 0 ? FALSE : $id;
    }

    /**
     * Inserts many rows into a table in the database
     *
     * @param  array  $data  array of data for insert
     * @param  boolean  $filter  to filter the data
     * @param  boolean  $xss_clean  to filter xss in the data
     * @return integer
     */
    function mass_insert($data, $filter = FALSE, $xss_clean = FALSE)
    {
        if ( $filter === TRUE )
        {
            $new_data = array();
            foreach ( $data as $row )
            {
                array_push($new_data, $this->filterInputArray($row, $xss_clean));
            }
            return $this->db->insert_batch($this->table, $new_data);
        }
        else
        {
            return $this->db->insert_batch($this->table, $data);
        }
    }

    /**
     * Deletes a set of records
     *
     * Use this function to delete many records of the database.
     * The parameter "$data" receives a set of filters to delete the records.
     *
     * @param  array  $data  array of parameters type "column => value"
     * @return integer
     */
    function delete($data)
    {
        $new_data = $this->filterInputArray($data);
        $this->db->delete($this->table, $new_data);
        $affected_rows = $this->db->affected_rows();
        return $affected_rows > 0 ? $affected_rows : FALSE;
    }

    /**
     * Delete one record
     *
     * @param  int $id identifier of the record that you need delete
     * @return boolean
     */
    function delete_by_id($id)
    {
        $this->db->delete($this->table, array($this->id => $id));
        $affected_rows = $this->db->affected_rows();
        return $affected_rows == 1 ? TRUE : FALSE;
    }

    /**
     * Function to update one record of the table
     *
     * For use this function you need to pass the id of record/line/object that 
     * you need to update and set the parameter "$data" as an array of type 
     * "field => value".
     *
     * @param  int  $id  Identifier of the record to be updated
     * @param  array  $data  Contains the data for update the record
     * @return integer
     */
    function update_by_id($id, $data)
    {
        $date_time = date("Y-m-d H:i:s", time());
        $data['update_time'] = $date_time;
        $new_data = $this->filterInputArray($data);
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $new_data);
        $affected_rows = $this->db->affected_rows();
        return $affected_rows == 1 ? TRUE : FALSE;
    }

    /**
     * Function to select any records where not in the array $values 
     * in the field especified in the parameter $col
     *
     * @param  array  $array  array of strings with the values to filter
     * @param  string $string Description of string
     * @return array
     */
    function get_not_in($values, $col = 'id')
    {
        $this->db->where_not_in($col, $values);
        return $this->get_all();
    }

    /**
     * Function to select all data of the table
     *
     * If you need to retrieve an array of objects by specifying their type 
     * you must set up the data_type of this entity.
     *
     * @return array
     */
    function get_all()
    {
        $result = $this->_get_all();
        if ( $this->data_type )
            return $result->result($this->data_type);
        return $result->result();
    }

    /**
     * Central point for create the query using the _get_read_table()
     *
     * @access private
     * @return string
     */
    private function _get_all()
    {
        return $this->db->get($this->_get_read_table());
    }

    /**
     * Function to retrieve the object specified in the parameter $id
     *
     * @param  int $id identifier of the object/record in the table
     * @return Entity_model
     */
    function get($id)
    {
        $this->db->limit(1);
        $this->db->where(array($this->_get_read_table() . '.' . $this->id => $id));

        if ( $this->data_type )
            return $this->_get_all()->row(0, $this->data_type);
        return $this->_get_all()->row();
    }

    /**
     * Function to retrieve many objects filtered by array parameter $where
     *
     * @param  array  $where  array of parameters type "column => value"
     * @return array
     */
    function get_where($where, $type = "AND")
    {
        if ( $type == "AND" )
            $this->db->where($where);

        if ( $type == "OR" )
            $this->db->or_where($where);

        return $this->get_all();
    }

    /**
     * Function to retrieve an unique object filtered by array parameter $where
     *
     * @param  array  $where  array of parameters type "column => value"
     * @return Entity_model
     */
    function get_where_unique($where, $type = "AND")
    {
        $this->db->limit(1);
        if ( $type == "AND" )
            $this->db->where($where);

        if ( $type == "OR" )
            $this->db->or_where($where);

        if ( $this->data_type )
            return $this->_get_all()->row(0, $this->data_type);
        return $this->_get_all()->row();
    }

    /**
     * Verify if one record(s)/object(s) exists in the table
     *
     * @param  array  $data  array of parameters type "column => value"
     * @return boolean
     */
    function exists($data)
    {
        $result = $this->get_where($data);

        if ( count($result) > 0 )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * 
     * Function to filter the received data. to delete the array of all 
     * items/fields that do not exist in the table to be updated.
     * 
     * The function eliminates the possibility of receiving injection attacks.
     *
     * The param "$data" accept 2 types of variable, array or object, 
     * if this function receive a variable object, the function transform this 
     * to array.
     *
     * @param  array  $data  data for filtering
     * @param  boolean $xss_clean Description of string
     * @return array
     */
    function filterInputArray($data, $xss_clean = TRUE, $table = NULL)
    {
        $table = $table ? $table : $this->table;
        if ( is_object($data) )
        {
            $data = get_object_vars($data);
        }
        $fields = $this->db->list_fields($table);

        foreach ( $data as $k => $v )
        {
            if ( in_array($k, $fields) == FALSE )
            {
                unset($data[$k]);
            }
            else
            {
                // treat dates
                if ( in_array($k, $this->date_fields) )
                {
                    if ( empty($v) )
                    {
                        unset($data[$k]);
                        continue;
                    }
                    else
                    {
                        $v = transform_datetime($v);
                        if ( $xss_clean === FALSE )
                        {
                            $data[$k] = $v;
                        }
                    }
                }

                if ( $k == 'username' )
                    $data[$k] = underscore(strtolower($data[$k]));

                // clean xss
                if ( $xss_clean === TRUE )
                {
                    if ( $v === NULL )
                    {
                        $data[$k] = $v;
                    }
                    else
                    {
                        $data[$k] = $this->security->xss_clean($v);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * CNPJ Number Validation - To validation the CNPJ
     *
     * @param string $cnpj The cnpj number to validate
     * @return boolean
     */
    function cnpj_validation($cnpj)
    {
        if ( !empty($cnpj) )
        {
            if ( $this->cnpj_number_validation($cnpj) == TRUE )
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('external_callbacks', lang('enter_valid_cnpj'));
                return FALSE;
            }
        }
        else
        {
            //$this->form_validation->set_message('external_callbacks', lang('the_field') . '%s' . lang('is_required'));
            return TRUE;
        }
    }

    /**
     * CPF Number Validation - To validation the CPF
     *
     * @param string $cpf The cpf number to validate
     * @return boolean
     */
    function cpf_validation($cpf)
    {

        if ( !empty($cpf) )
        {

            if ( cpf_number_validation($cpf) == TRUE )
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('external_callbacks', lang('not_have_valid_cpf'));
                return FALSE;
            }
        }
        else
        {
            //$this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('is_required'));
            return TRUE;
        }
    }

    /**
     * URL Validation - To validation the URL
     *
     * @param string $url The url to validate
     * @return boolean
     */
    function url_validation($url)
    {
        $validation = preg_match('/^(http|https|ftp):\/\/([\w]*)\.([\w]*)\.(com|net|org|biz|info|mobi|us|cc|bz|tv|ws|name|co|me)(\.[a-z]{1,3})?\z/i', $url) ||
                preg_match('/^([\w]*)\.([\w]*)\.(com|net|org|biz|info|mobi|us|cc|bz|tv|ws|name|co|me)(\.[a-z]{1,3})?\z/i', $url);
        if ( $validation )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('not_have_valid_url'));
            return FALSE;
        }
    }

    /**
     * date or datetime Validation - To validation the date or datetime
     *
     * @param string $datetime The date or datetime to validate
     * @return boolean
     */
    function datetime_validation($datetime)
    {
        if ( !empty($datetime) )
        {
            if ( is_datetime($datetime) == TRUE )
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('not_have_valid_date'));
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * phone required validation
     * 
     * PHONE[0] required and is numeric Validation - To validation the required 
     * input for one phone number
     *
     * @param array $phones The array of phone numbers to validate
     * @return boolean
     */
    function phone_required_validation($phones)
    {
        if ( isset($phones) )
        {

            for ( $i = 0; $i < count($phones); $i++ )
            {
                if ( isset($phones[$i]) )
                {
                    if ( (!is_numeric($phones[$i]) ) )
                    {
                        $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone' . $i) . nbs() . lang('accept_olnly_numbers_parentheses_hyphen'));
                        return FALSE;
                    }
                }
            }

            if ( isset($phones[0]) && is_numeric($phones[0]) )
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone1') . nbs() . lang('is_necessary'));
                return FALSE;
            }
        }
        else
        {
            $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone1') . nbs() . lang('is_necessary'));
            return FALSE;
        }
    }

    /**
     * type required validation
     * 
     * type required and is alphabet Validation - To validation the required 
     * input for one type alphabet
     *
     * @param object $type The object of type numbers to validate
     * @return boolean
     */
    function type_required_validation($type)
    {
        if ( isset($type) )
        {
            if ( (!is_alpha($type) ) )
            {
                $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('type') . nbs() . lang('accept_olnly_alphabet_parentheses_hyphen'));
                return FALSE;
            }


            if ( isset($type) && is_alpha($type) )
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('type') . nbs() . lang('is_necessary'));
                return FALSE;
            }
        }
        else
        {
            $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('type') . nbs() . lang('is_necessary'));
            return FALSE;
        }
    }

    /**
     * extension required validation
     * 
     * To transform the phone number to required if the extension are filled
     *
     * @param array $extensios The array of extension numbers to validate
     * @return boolean
     */
    function extension_required_validation($extensions)
    {
        if ( isset($extensions) )
        {
            $phones = $this->input->post('phone', TRUE);
            for ( $i = 0; $i < count($extensions); $i++ )
            {
                if ( isset($extensions[$i]) )
                {
                    if ( !isset($phones[$i]) )
                    {
                        $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone' . $i) . nbs() . lang('accept_olnly_numbers_parentheses_hyphen'));
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }
        else
        {
            return TRUE;
        }
    }

    function is_unique_validation($value, $field_names)
    {
        $field_name = $field_names[0];
        $record = $this->get_where_unique(array($field_name => $value));
        if ( !isset($record->id) || $record->id == $_POST['id'] )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('external_callbacks', lang('this') . nbs() . lang($field_name) . nbs() . lang('is_not_available'));
            return FALSE;
        }
    }

    function do_photo_upload($id)
    {
        //parametriza as preferências
        $config["upload_path"] = "upload/person_photos/";
        $config["allowed_types"] = "gif|jpg|png";
        $config["overwrite"] = TRUE;
        $this->load->library("upload", $config);
        //em caso de sucesso no upload

        if ( !is_dir($config['upload_path']) )
        {
            mkdir(APPPATH . '/upload/' . $folder_name . '/');
            mkdir(APPPATH . '/upload/' . $folder_name . '/edict/');
        }

        $field_name = 'file_name_photo';
        if ( $this->upload->do_upload($field_name) )
        {
            //renomeia a foto
            $nomeorig = $config["upload_path"] . $this->upload->file_name;
            $nomedestino = $config["upload_path"] . "person_" . $id . $this->upload->file_ext;
            rename($nomeorig, $nomedestino);

            //define opções de crop
            $config["image_library"] = "gd2";
            $config["source_image"] = $nomedestino;
            $config["width"] = 300;
            $config["height"] = 300;
            $this->load->library("image_lib", $config);
            $this->image_lib->crop();
            return $nomedestino;
        }
        else
        {
            $error = array('error' => $this->upload->display_errors());
            $msg = lang('document_upload_error');
            $this->msg_error($msg);
            return FALSE;
        }
    }

}