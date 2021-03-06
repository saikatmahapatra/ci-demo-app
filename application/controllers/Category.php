<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends CI_Controller {

    var $data;
    var $id;
    var $sess_user_id;

    function __construct() {
        parent::__construct();

        //Check if any user logged in else redirect to login
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            if($this->data['is_admin'] === TRUE){
                redirect($this->router->directory.'admin/login');
            }else{
                redirect($this->router->directory.'user/login');
            }
        }

        //Has logged in user permission to access this page or method?        
        $this->app_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access'
        ));

        // Get logged  in user id
        $this->sess_user_id = $this->app_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->app_lib->init_template_elements('admin');

        // Load required js files for this controller
        $javascript_files = array(
            $this->router->class
        );
        $this->data['app_js'] = $this->app_lib->add_javascript($javascript_files);

        $this->load->model('category_model');
        $this->id = $this->uri->segment(3);
        
        $this->data['category_dropdown'] = $this->category_model->get_category_dropdown();
        $this->data['page_title'] = $this->router->class.' : '.$this->router->method;
        
        // load Breadcrumbs
		$this->load->library('breadcrumbs');
		
		// Status flag indicator for showing in table grid etc
		$this->data['status_flag'] = array(
            'Y'=>array('text'=>'Active', 'css'=>'text-success', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-success" aria-hidden="TRUE"></i>'),
            'N'=>array('text'=>'Inactive', 'css'=>'text-warning', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-warning" aria-hidden="TRUE"></i>'),
            'A'=>array('text'=>'Archived', 'css'=>'text-danger', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-danger" aria-hidden="TRUE"></i>')
        );
		
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Dashboard', '/admin');
		$this->breadcrumbs->push('Product Category', '/admin/category');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
    }

    function index() {
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		$this->data['page_title'] = 'Product Category';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function render_datatable() {
        //Total rows - Refer to model method definition
        $result_array = $this->category_model->get_rows();
        $total_rows = $result_array['num_rows'];

        // Total filtered rows - check without limit query. Refer to model method definition
        $result_array = $this->category_model->get_rows(NULL, NULL, NULL, TRUE, FALSE);
        $total_filtered = $result_array['num_rows'];

        // Data Rows - Refer to model method definition
        $result_array = $this->category_model->get_rows(NULL, NULL, NULL, TRUE);
        $data_rows = $result_array['data_rows'];
        $data = array();
        $no = $_REQUEST['start'];
        foreach ($data_rows as $result) {
            $no++;
            $row = array();
            $row[] = $result['category_name'];
            $row[] = isset($result['category_status']) ? $this->data['status_flag'][$result['category_status']]['text'] : '';
            //add html for action
            $action_html = '';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit/' .$result['id']), '<i class="fa fa-fw fa-pencil" aria-hidden="TRUE"></i>', array(
                'class' => 'btn btn-sm btn-outline-secondary',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit',
                'title' => 'Edit',
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/delete/' .$result['id']), '<i class="fa fa-fw fa-trash-o" aria-hidden="TRUE"></i>', array(
                'class' => 'btn btn-sm btn-outline-danger btn-delete',
				'data-confirmation'=>TRUE,
				'data-confirmation-message'=>'Are you sure, you want to delete this?',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Delete',
                'title' => 'Delete',
            ));

            $row[] = $action_html;
            $data[] = $row;
        }

        /* jQuery Data Table JSON format */
        $output = array(
            'draw' => isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '',
            'recordsTotal' => $total_rows,
            'recordsFiltered' => $total_filtered,
            'data' => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function validate_category_form_data($action = NULL) {
        if ($action == 'add') {
            $this->form_validation->set_rules('category_name', 'category name', 'required');
        } elseif ($action == 'edit') {
            $this->form_validation->set_rules('category_name', 'category name', 'required|callback_is_category_name_exists');
        }
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function is_category_name_exists($str) {
        //echo $str; die();
        $result = $this->category_model->check_category_name($str);
        if ($result == FALSE) {
            $this->form_validation->set_message('is_category_name_exists', $str . ' is already exists !');
            return FALSE;
        }
        return TRUE;
    }

    function add() {
		$this->breadcrumbs->push('Add','/');
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        if ($this->input->post('form_action') == 'insert') {
            if ($this->validate_category_form_data('add') == TRUE) {
                $parent_cat_id = ($this->input->post('category_parent') == '') ? NULL : $this->input->post('category_parent');
                $postdata = array(
                    'category_name' => $this->input->post('category_name'),
                    'category_parent' => $parent_cat_id,
                );
                $insert_id = $this->category_model->insert($postdata);
                if ($insert_id) {
                    $this->app_lib->set_flash_message('Data added successfully.', 'alert-success');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = 'Add Product Category';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function edit() {
		$this->breadcrumbs->push('Edit','/');
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        if ($this->input->post('form_action') == 'update') {
            if ($this->validate_category_form_data('edit') == TRUE) {
                $parent_cat_id = ($this->input->post('category_parent') == '') ? NULL : $this->input->post('category_parent');
                $postdata = array(
                    'category_name' => $this->input->post('category_name'),
                    'category_parent' => $parent_cat_id,
                    'category_status' => $this->input->post('category_status'),
                );
                $where_array = array('id' => $this->input->post('id'));
                $res = $this->category_model->update($postdata, $where_array);

                if ($res) {
                    $this->app_lib->set_flash_message('Data updated successfully.', 'alert-success');
                    redirect(current_url());
                }
            }
        }
        $result_array = $this->category_model->get_rows($this->id);
        $this->data['rows'] = $result_array['data_rows'];
		$this->data['page_title'] = 'Edit Product Category';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function delete() {
        $where_array = array('id' => $this->id);
        $res = $this->category_model->delete($where_array);
        if ($res) {
            $this->app_lib->set_flash_message('Data deleted successfully.', 'alert-success');
            redirect($this->router->directory.$this->router->class.'');
        }
    }

}

?>
