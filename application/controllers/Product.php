<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

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
        
        $this->load->library('cart');
        
        $this->load->model('product_model');
        $this->load->helper('text');
        $this->cart->product_name_rules = '[:print:]'; // allow any characters in product name rule
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
    }

    function index() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $products = $this->product_model->get_rows();
        $this->data['total_products'] = $products['num_rows'];
        $this->data['products'] = $products['data_rows'];
        $this->data['maincontent'] = $this->load->view('site/product/index', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

}
