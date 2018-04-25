<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CI_Example extends CI_Controller {

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
        
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
        
    }

    function index() {
        $this->data['maincontent'] = $this->load->view('site/ci_example/index', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function form_helper() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');

        $this->data['job_role_arr'] = array(
            '' => '-Select-',
            '1' => 'Software Enginner',
            '2' => 'Consultant',
        );

        $this->data['domain_arr'] = array(
            '' => '-Select-',
            'IT Software' => array('1' => 'Software Engineering',
                '2' => 'Software Development',
                '3' => 'Web,UI,UX Development',
                '4' => 'Product Quality Analysis',
                '5' => 'Operation Management',
                '6' => 'SDLC/Process Management',
            ),
            'IT Telecom Networking' => array('1' => 'Telecom Architect',
                '2' => 'Telecom Infrastructure Support',
                '3' => 'Core Telecom',
                '4' => 'Pack Core',
                '5' => 'Network Engineering',
            ),
            'ITES/BPO' => array('1' => 'BPO',
                '2' => 'Out Sourcing',
                '3' => 'Tele Calling',
            ),
        );

        if ($this->input->post('form_action') == 'add') {
            if ($this->validate_form() == TRUE) {
                $this->session->set_flashdata('flash_message', '<strong>Ok! </strong>Validated and Ready to Insert Data.');
                $this->session->set_flashdata('flash_message_css', 'alert-info');
                redirect(current_url());
            }
        }
        $this->data['maincontent'] = $this->load->view('site/ci_example/form_helper', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function validate_form() {
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email');        
        $this->form_validation->set_rules('user_password', 'password', 'required|trim|min_length[6]|max_length[16]');
        $this->form_validation->set_rules('user_password_confirm', 'confirm password', 'required|matches[user_password]');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('gender', 'gender', 'required');
        $this->form_validation->set_rules('job_role', 'job role selection', 'required');
        $this->form_validation->set_rules('functional_domain', 'functional domain', 'required');        
        $this->form_validation->set_rules('userfile', 'resume upload', 'trim|required');
        $this->form_validation->set_rules('terms', 'terms & condition acceptance', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="validation-error">', '</p>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function validate_max_function_domain($functional_domain, $size) {
        //echo $size;die();
        //$this->form_validation->set_message('validate_max_function_domain', 'You can choose maximum 3 options');
        //return false;
    }
    
    function download_as_pdf(){
       $this->load->view('site/ci_example/dom_pdf_gen_pdf'); 
    }
            
    function dom_pdf_gen_pdf() {
        // Load all views as normal
        $this->load->view('site/ci_example/dom_pdf_gen_pdf');
        // Get output html
        $html = $this->output->get_output();
        // Load library
        $this->load->library('dompdf_gen');
        // Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("mypdf_" . time() . ".pdf");
    }

    function date_helper() {
        $this->load->helper('date');

        $this->data['maincontent'] = $this->load->view('site/ci_example/date_helper', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function directory_helper() {
        $this->load->helper('directory');
        $map = directory_map('./assets', FALSE, TRUE);
        $this->data['read_dir'] = $map;

        $map = directory_map('./assets', 1);
        $this->data['sub_folders'] = $map;

        $this->data['maincontent'] = $this->load->view('site/ci_example/directory_helper', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

}

?>
