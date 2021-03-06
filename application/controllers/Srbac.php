<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Srbac extends CI_Controller {

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

        
        $this->load->model('cms_model');
        
        
        $this->id = $this->uri->segment(3);
        $this->data['arr_content_type'] = $this->cms_model->get_content_type();

        $this->data['page_title'] = $this->router->class.' : '.$this->router->method;
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Home', '/');
		$this->breadcrumbs->push('CMS', '/cms');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
		//Pagination
         $this->load->library('pagination');
         
         // Status flag indicator for showing in table grid etc
		$this->data['status_flag'] = array(
            'Y'=>array('text'=>'Active', 'css'=>'text-success', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-success" aria-hidden="TRUE"></i>'),
            'N'=>array('text'=>'Inactive', 'css'=>'text-warning', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-warning" aria-hidden="TRUE"></i>'),
            'A'=>array('text'=>'Archived', 'css'=>'text-danger', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-danger" aria-hidden="TRUE"></i>')
        );
		
    }

    function index() {
        // Check user permission by permission name mapped to db
        // $this->app_lib->is_auth('cms-list-view');
		
		// Get logged  in user id
        $this->sess_user_id = $this->app_lib->get_sess_user('id');
			
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        
        
		
		$this->data['page_title'] = 'SRBAC';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }
	
	function index_ci_pagination() {
        // Check user permission by permission name mapped to db
        // $this->app_lib->is_auth('cms-list-view');
			
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        
        

        // Display using CI Pagination: Total filtered rows - check without limit query. Refer to model method definition		
		$result_array = $this->cms_model->get_rows(NULL, NULL, NULL, FALSE, FALSE);
		$total_num_rows = $result_array['num_rows'];
		
		//pagination config
		$additional_segment = 'admin/cms/index_ci_pagination';
		$per_page = 10;
		$config['uri_segment'] = 4;
		$config['num_links'] = 1;
		$config['use_page_numbers'] = TRUE;
		//$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4)-1) : 0;
		$offset = ($page*$per_page);
		$this->data['pagination_link'] = $this->app_lib->render_pagination($total_num_rows, $per_page, $additional_segment);
		//end of pagination config
        

        // Data Rows - Refer to model method definition
        $result_array = $this->cms_model->get_rows(NULL, $per_page, $offset, FALSE, TRUE);
        $this->data['data_rows'] = $result_array['data_rows'];
		
		$this->data['page_title'] = 'Website Contents (CI Pagination Version)';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index_ci_pagination', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function render_datatable() {
        //Total rows - Refer to model method definition
        $result_array = $this->cms_model->get_rows();
        $total_rows = $result_array['num_rows'];

        // Total filtered rows - check without limit query. Refer to model method definition
        $result_array = $this->cms_model->get_rows(NULL, NULL, NULL, TRUE, FALSE);
        $total_filtered = $result_array['num_rows'];

        // Data Rows - Refer to model method definition
        $result_array = $this->cms_model->get_rows(NULL, NULL, NULL, TRUE);
        $data_rows = $result_array['data_rows'];
        $data = array();
        $no = $_REQUEST['start'];
        foreach ($data_rows as $result) {
            $no++;
            $row = array();
            $row[] = $result['content_type'];
            $row[] = $result['content_title'];
            $row[] = $this->app_lib->display_date($result['content_created_on'], TRUE);
            $row[] = isset($result['content_status']) ? $this->data['status_flag'][$result['content_status']]['text'] : '';
            //add html for action
            $action_html = '';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit/' . $result['id']), '<i class="fa fa-fw fa-pencil" aria-hidden="TRUE"></i>', array(
                'class' => 'btn btn-sm btn-outline-secondary',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit',
                'title' => 'Edit',
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/delete/' . $result['id']), '<i class="fa fa-fw fa-trash-o" aria-hidden="TRUE"></i>', array(
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

    function add() {
        //Check user permission by permission name mapped to db
        //$this->app_lib->is_auth('cms-add');
        //$this->data['page_title'] = "Add Page Content";
		$this->breadcrumbs->push('Add','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        
        
        if ($this->input->post('form_action') == 'insert') {
            if ($this->validate_form_data('add') == TRUE) {

                $postdata = array(
                    'content_type' => $this->input->post('content_type'),
                    'content_title' => $this->input->post('content_title'),
                    'content_text' => $this->input->post('content_text'),
                    'content_meta_keywords' => $this->input->post('content_meta_keywords'),
                    'content_meta_description' => $this->input->post('content_meta_description'),
                    'content_display_from_date' => $this->app_lib->convert_to_mysql($this->input->post('content_display_from_date')),
                    'content_display_to_date' => $this->app_lib->convert_to_mysql($this->input->post('content_display_to_date')),
                    'content_meta_author' => $this->input->post('content_meta_author'),
                    'content_created_by' => $this->sess_user_id,
					'content_status' => $this->input->post('content_status')
                );
                $insert_id = $this->cms_model->insert($postdata);
                if ($insert_id) {
                    $this->app_lib->set_flash_message('Data Added Successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/add');
                }
            }
        }
		$this->data['page_title'] = 'Add Roles | Permission';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function edit() {
        //Check user permission by permission name mapped to db
        //$this->app_lib->is_auth('cms-edit');
		//$this->data['page_title'] = "Edit Page Content";
		$this->breadcrumbs->push('Edit','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        
        
        if ($this->input->post('form_action') == 'update') {
            if ($this->validate_form_data('edit') == TRUE) {
                $postdata = array(
                    'content_type' => $this->input->post('content_type'),
                    'content_title' => $this->input->post('content_title'),
                    'content_text' => $this->input->post('content_text'),
                    'content_meta_keywords' => $this->input->post('content_meta_keywords'),
                    'content_meta_description' => $this->input->post('content_meta_description'),
                    'content_meta_author' => $this->input->post('content_meta_author'),
                    'content_status' => $this->input->post('content_status'),
					'content_display_from_date' => $this->app_lib->convert_to_mysql($this->input->post('content_display_from_date')),
                    'content_display_to_date' => $this->app_lib->convert_to_mysql($this->input->post('content_display_to_date')),
                    'content_archived' => $this->input->post('content_archived'),
					'content_created_by' => $this->sess_user_id
                );
                $where_array = array('id' => $this->input->post('id'));
                $res = $this->cms_model->update($postdata, $where_array);
                if ($res) {
                    $this->app_lib->set_flash_message('Data Updated Successfully.', 'alert-success');
                    redirect(current_url());
                }
            }
        }
        $result_array = $this->cms_model->get_rows($this->id);
        $this->data['rows'] = $result_array['data_rows'];
		$this->data['page_title'] = 'Edit Roles | Permission';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function delete() {
        //Check user permission by permission name mapped to db
        //$this->app_lib->is_auth('cms-delete');

        $where_array = array('id' => $this->id);
        $res = $this->cms_model->delete($where_array);
        if ($res) {
            $this->app_lib->set_flash_message('Data Deleted Successfully.', 'alert-success');
            redirect($this->router->directory.$this->router->class);
        }
    }

    function validate_form_data($action = NULL) {
        $this->form_validation->set_rules('content_type', 'page content type', 'required');
        $this->form_validation->set_rules('content_title', 'page content title', 'required');
        $this->form_validation->set_rules('content_text', 'page content text', 'required');

        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
