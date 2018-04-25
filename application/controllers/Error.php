<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Error extends CI_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        
        //Loggedin user details
        $this->sess_user_id = $this->common_lib->get_sess_user('id');        
        
        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements();
        
        //add required js files for this controller
        $app_js_src = array();         
        $this->data['app_js'] = $this->common_lib->add_javascript($app_js_src);
    }

    function index() {
        $this->page_not_found();
    }

    function page_not_found() {
        $data = array();

        $this->data['maincontent'] = $this->load->view('site/error/page_not_found', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

}
