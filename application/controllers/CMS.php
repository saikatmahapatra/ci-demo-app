<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CMS extends CI_Controller {

    var $data;
    var $id;
    
    function __construct() {
        parent::__construct();
        
        //Loggedin user details
        $this->sess_user_id = $this->common_lib->get_sess_user('id');        
        
        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements();
        
        //add required js files for this controller
        $app_js_src = array();         
        $this->data['app_js'] = $this->common_lib->add_javascript($app_js_src);
        
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;        
    }

    function index() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');

        $this->data['maincontent'] = $this->load->view('site/cms/index', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

}

?>
