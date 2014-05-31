<?php

/**
 * Crud_controller Class to extends in a Base_Controller class
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

include_once("base_controller.php");

abstract class CRUD_Controller extends Base_Controller {

    /**
     * The construct of crud_controller
     *
     * Definitions of what is done in this constructor:
     * Load the model file
     * 
     * add the delete, edit, view in the l_sidebar 
     *
     * Load the form_validation file
     * 
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model($this->ctrlr_name . '_model', 'model');

        $this->_add_to_l_sidebar($this->ctrlr_name . '/delete', sprintf(lang('delete_item'), $this->ctrlr_name));
        $this->_add_to_l_sidebar($this->ctrlr_name . '/edit', sprintf(lang('edit_item_info'), $this->ctrlr_name));
        $this->_add_to_l_sidebar($this->ctrlr_name . '/view', sprintf(lang('view_item'), $this->ctrlr_name));

        $this->_add_to_r_sidebar($this->ctrlr_name . '/edit', sprintf(lang('edit_item_info'), $this->ctrlr_name));

        $this->load->library('form_validation');
    }

    /**
     * Function that puts data in the rules for the new domain elements form_validation
     * seta os dados do form_validation para para os novos elementos de domínio
     */
    function _set_create_rules()
    {
        return;
    }

    /**
     * Function that puts data in the rules for updating the elements of the domain form_validation
     * seta os dados do form_validation para editar os elementos de domínio
     */
    function _set_update_rules()
    {
        return;
    }

    /**
     * Function that calls function show
     */
    public function index()
    {
        $this->show();
    }

    /**
     * Function that shows all objects registered
     */
    function show()
    {
        //show all items
        $this->data['items'] = $this->model->get_all();
        $this->_view($this->ctrlr_name . '_show');
    }

    /**
     * Load aditional data in $this->data to the view.     
     */
    function _set_extra_view_data($id)
    {
        return array();
    }
    
    /**
     * Function that loads the view to be presented
     */
    function view($id)
    {
        $item = $this->model->get($id);
        if ( $item )
        {
            if ( $item->can_view() )
            {
                if ( $item->can_edit() )
                {
                    $this->data['r_sidebar'] = $this->_r_sidebar();
                }
                $this->data[$this->ctrlr_name] = $item;

                $this->data['header']->title = $item->get_view_title();

                $this->_set_extra_view_data($id);
                $this->_view($this->ctrlr_name);
            }
            else
            {
                $this->msg_error(sprintf(lang('user_not_owns_item'), $this->ctrlr_name));
                redirect(plural($this->ctrlr_name));
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('item_not_found'), $this->ctrlr_name));
            redirect(plural($this->ctrlr_name));
        }
    }

    /**
     * Set aditional data in $this->data to the create view.     
     */
    function _set_extra_create_data()
    {
        return array();
    }

    /**
     * Function that creates an object.
     * to successfully call the show.
     * failure if it occurred again calls create_view
     */
    function create()
    {
        $this->data['header']->title = sprintf(lang('new_item'), $this->ctrlr_name);
        $this->_set_create_rules();

        if ( $this->form_validation->run() == FALSE )
        {
            if ( $this->input->post() )
            {
                $this->data = array_merge($this->data, $this->input->post());
            }

            $this->_set_extra_create_data();
            $this->_view($this->ctrlr_name . '_create');
        }
        else
        {

            if ( ($this->model->insert($this->input->post(NULL, TRUE))) > 0 )
            {
                // Lançar Mensagem: Inserção com sucesso
                if ( isset($this->url_return) && !empty($this->url_return) )
                {
                    redirect($this->url_return, 'refresh');
                }
                else
                {
                    //controller show
                    $this->msg_ok($this->ctrlr_name . nbs() . lang('create_success'));
                    redirect(plural($this->ctrlr_name), 'refresh');
                }
            }
            else
            {
                // Erro na inserção
                $this->msg_ok(lang('create_error') . nbs() . lang('the') . nbs() . lang($this->ctrlr_name));
                redirect($this->ctrlr_name . '/create', 'refresh');
            }
        }
    }

    /**
     * Set aditional data in $this->data to the edit view.     
     */
    function _set_extra_edit_data($id = NULL)
    {
        return array();
    }

    /**
     * Function that shows the edit form of an object and updates the info.     
     */
    public function edit($id)
    {
        $item = $this->model->get($id);

        if ( $item )
        {
            if ( $item->can_edit() )
            {
                $this->data[$this->ctrlr_name] = $item;
                $this->data['l_sidebar'] = $this->_l_sidebar();
                $this->data['header']->title = sprintf(lang('edit_item_info'), $this->ctrlr_name);

                $this->_set_extra_edit_data($id);

                $this->_set_update_rules();

                if ( $this->form_validation->run() == FALSE )
                {
                    $this->data[$this->ctrlr_name] = $item;
                    $this->_view($this->ctrlr_name . '_update');
                }
                else
                {
                    $data = $this->input->post(NULL, TRUE);
                    if ( $this->model->update_by_id($id, $data) )
                    {
                        $this->msg_ok(sprintf(lang('item_info_saved'), $this->class_name));
                        // Lançar Mensagem: Inserção com sucesso
                        if ( isset($this->url_return) && !empty($this->url_return) )
                        {
                            redirect($this->url_return, 'refresh');
                        }
                        else
                        {
                            //$this->data[$this->ctrlr_name] = $this->model->get($id);
                            //$this->_view($this->ctrlr_name . '_update');
                            redirect($this->ctrlr_name . '/view/' . $id, 'refresh');
                        }
                    }
                }
            }
            else
            {
                $this->msg_error(sprintf(lang('user_not_owns_item'), $this->ctrlr_name));
                redirect(plural($this->ctrlr_name));
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('item_not_found'), $this->class_name));
            redirect(plural($this->ctrlr_name));
        }
    }

    /**
     * Function that calls the delete view
     */
    public function delete($id)
    {
        $item = $this->model->get($id);

        if ( $item )
        {
            if ( $item->can_delete() )
            {
                if ( $this->input->post('save') )
                {
                    if ( ($this->model->delete_by_id($id)) > 0 )
                    {
                        $this->msg_ok(sprintf(lang('item_deleted'), $this->class_name));
                        redirect($this->ctrlr_name . '/show');
                    }
                }
                else
                {
                    $this->data['l_sidebar'] = $this->_l_sidebar();
                    $this->data['id'] = $id;
                    $this->data['msg'] = sprintf(lang('delete_item_msg'), $this->ctrlr_name);
                    $this->load->view('delete_view', $this->data);
                }
            }
            else
            {
                $this->msg_error(sprintf(lang('user_not_owns_item'), $this->ctrlr_name));
                redirect($this->ctrlr_name . '/view/' . $id);
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('item_not_found'), $this->class_name));
            redirect($this->ctrlr_name . '/show');
        }
    }

    /**
     * Function that deletes an object on a table using ajax
     */
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $item = $this->model->get($id);

        if ( $item )
        {
            if ( $item->can_delete() )
            {
                if ( ($this->model->delete_by_id($id)) > 0 )
                {
                    $this->msg_ok(sprintf(lang('item_deleted'), $this->class_name));
                }
                else
                {
                    $this->msg_error(sprintf(lang('item_not_deleted'), $this->ctrlr_name));
                }
            }
            else
            {
                $this->msg_error(sprintf(lang('user_not_owns_item'), $this->ctrlr_name));
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('item_not_found'), $this->class_name));
        }
    }

    /**
     * Function that saves an attribute in a table using ajax
     */
    function save()
    {
        if ( $this->authlib->logged_in() )
        {
            $id = $this->input->post('id');
            $key = $this->input->post('key');
            $value = $this->input->post('value');
            $item = $this->model->get($id);

            if ( $item )
            {
                if ( $item->can_edit() )
                {
                    if ( $key && $value )
                    {
                        $this->model->update_by_id(array($key => $value), $id);
                        /* TODO: Some returns need to be transformed to the view when using inline editables like with combo boxes.
                          if ( $this->input->post('transform') )
                          {
                          $value = $this->model->transform($key, $value);
                          }
                         */
                        echo $value;
                    }
                }
            }
        }
    }

    /*
     * external_callbacks method handles form validation callbacks that are not located
     * in the controller where the form validation was run.
     *
     * $param is a comma delimited string where the first value is the name of the model
     * where the callback lives. The second value is the method name, and any additional 
     * values are sent to the method as a one dimensional array.
     *
     * EXAMPLE RULE:
     *  callback_external_callbacks[some_model,some_method,some_string,another_string]
     */

    public function external_callbacks($postdata, $param)
    {
        $param_values = explode(',', $param);

        // Make sure the model is loaded
        $model = $param_values[0];
        $this->load->model($model);

        // Rename the second element in the array for easy usage
        $method = $param_values[1];

        // Check to see if there are any additional values to send as an array
        if ( count($param_values) > 2 )
        {
            // Remove the first two elements in the param_values array
            array_shift($param_values);
            array_shift($param_values);

            $argument = $param_values;
        }

        // Do the actual validation in the external callback
        if ( isset($argument) )
        {
            $callback_result = $this->$model->$method($postdata, $argument);
        }
        else
        {
            $callback_result = $this->$model->$method($postdata);
        }

        return $callback_result;
    }

    /**
     * Debugs the content of a table or of one Data_object. This is for test.
     *
     * @param  integer $id Optional. The id of a Data_object.
     */
    function g($id = '')
    {
        //TODO: Only admins can execute this method! Need to set this up.
        $this->output->enable_profiler(TRUE);
        if ( $id === '' )
            debug($this->model->get_all());
        else
            debug($this->model->get($id));
    }

}
