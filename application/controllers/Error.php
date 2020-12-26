<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Error extends CI_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        //Loggedin user details
        $this->sess_user_id = $this->app_lib->get_sess_user('id');
        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->app_lib->init_template_elements('site');
        // Load required js files for this controller
        $javascript_files = array();
        $this->data['app_js'] = $this->app_lib->add_javascript($javascript_files);
		$this->data['page_title'] = $this->router->class.' : '.$this->router->method;
    }

    function index() {
        $this->page_not_found();
    }

    function page_not_found() {
        $data = array();
		$this->data['page_title'] = "404 Page not found";
        $this->data['maincontent'] = $this->load->view('errors/error_404', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function auth() {
		$data = array();
		$this->data['page_title'] = "Authorization Error Occured";
        $this->data['maincontent'] = $this->load->view('errors/auth_error', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

}
