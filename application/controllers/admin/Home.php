<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    var $data;
    var $id;
    var $sess_user_id;

    function __construct() {
        parent::__construct();

        //Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }

        //Has logged in user permission to access this page or method?        
        $is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access'
        ));

        // Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements('admin');

        // Load required js files for this controller
        $javascript_files = array();
        $this->data['app_js'] = $this->common_lib->add_javascript($javascript_files);

        $this->load->model('home_model');
        $this->id = $this->uri->segment(3);

        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
        $this->data['page_heading'] = $this->router->class.' : '.$this->router->method;
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Home', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();

    }
	
	function index() {		
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->data['page_heading'] = "Dashboard";
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/index', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

}

?>
