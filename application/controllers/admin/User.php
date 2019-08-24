<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        
        $this->load->model('user_model');
        $this->load->model('upload_model');
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;

        // Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements('admin');

        // Load required js files for this controller
        $javascript_files = array(
            $this->router->class
        );
        $this->data['app_js'] = $this->common_lib->add_javascript($javascript_files);
        
        $this->data['page_title'] = $this->router->class.' : '.$this->router->method;
        
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Home', '/home');
		//$this->breadcrumbs->push('User', '/user/manage');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
		// Address Types
		$this->data['address_type'] = array('P'=>'Permanent Address','C'=>'Present Address','S'=>'Shipping','B'=>'Billing');
		
		// DOB - DD MM YYYY drop down
        $this->data['day_arr'] = $this->calander_days();
        $this->data['month_arr'] = $this->calander_months();
        $this->data['year_arr'] = $this->calander_years();
		
		//User Roles drop down
		$this->data['arr_roles'] = $this->user_model->get_user_role_dropdown();
		$this->data['arr_designations'] = $this->user_model->get_designation_dropdown('Y');
		$this->data['arr_departments'] = $this->user_model->get_department_dropdown();
		$this->data['arr_user_title'] = array(''=>'Select Title','Mr.'=>'Mr.','Mrs.'=>'Mrs.','Dr.'=>'Dr.','Ms.'=>'Ms.');
		$this->data['blood_group'] = array(''=>'Select','O+'=>'O+','O-'=>'O-','A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-','NA'=>'Unknown');
		
		// Status flag indicator for showing in table grid etc
		$this->data['status_flag'] = array(
            'Y'=>array('text'=>'Active', 'css'=>'text-success', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-success" aria-hidden="true"></i>'),
            'N'=>array('text'=>'Inactive', 'css'=>'text-warning', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-warning" aria-hidden="true"></i>'),
            'A'=>array('text'=>'Archived', 'css'=>'text-danger', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-danger" aria-hidden="true"></i>')
        );
    }

    function index() {
        $this->login();
    }

    function manage() {        
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        $is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        
        //Download Data 
        if($this->input->post('form_action') == 'download'){
            $this->download_to_excel();
        }
        
		$this->breadcrumbs->push('View', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->data['page_title'] = 'Manage Users';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/manage', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

    function render_datatable() {
        //Total rows - Refer to model method definition
        $result_array = $this->user_model->get_rows();
        $total_rows = $result_array['num_rows'];

        // Total filtered rows - check without limit query. Refer to model method definition
        $result_array = $this->user_model->get_rows(NULL, NULL, NULL, TRUE, FALSE);
        $total_filtered = $result_array['num_rows'];

        // Data Rows - Refer to model method definition
        $result_array = $this->user_model->get_rows(NULL, NULL, NULL, TRUE);
        $data_rows = $result_array['data_rows'];
        $data = array();
        $no = $_REQUEST['start'];
        foreach ($data_rows as $result) {
            $no++;
            $row = array();
            $row[] = '<div class="">'.$result['user_firstname'] . '&nbsp;' . $result['user_lastname'].'</div>';
            $row[] = $result['user_email'];
            $row[] = $result['user_phone1'];			
			$row[] = isset($result['user_status']) ? $this->data['status_flag'][$result['user_status']]['icon'] : '';           
            
			//add html for action
            $action_html = '';
            $acc_status_icon = ($result['user_status'] == 'Y') ? '' : '';
            $acc_status_text = ($result['user_status'] == 'Y') ? 'Deactivate' : 'Activate';
            $acc_status_class = ($result['user_status'] == 'Y') ? 'btn btn-sm btn-outline-danger' : 'btn btn-sm btn-outline-success';
            $acc_status_set = ($result['user_status'] == 'Y') ? 'N' : 'Y';
            
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/profile/' . $result['id']), '<i class="fa fa-fw fa-info-circle" aria-hidden="true"></i>', array(
                'class' => 'btn btn-sm btn-outline-secondary',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'View Profile',
                'title' => 'View Profile'
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit_user_profile/' . $result['id']), '<i class="fa fa-fw fa-pencil" aria-hidden="true"></i>', array(
                'class' => 'btn btn-sm btn-outline-secondary',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit Profile',
                'title' => 'Edit Profile'
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

    function login() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == TRUE) {
            redirect($this->router->directory.'home');
        }
        ########### Validate User Auth End #############
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        
        if ($this->input->post('form_action') == 'login') {
            if ($this->validate_login_form_data() == true) {
                $email = $this->input->post('user_email');
                $password = md5($this->input->post('user_password'));
                $login_result = $this->user_model->authenticate_user($email, $password);
                //print_r($login_result);
                //die();
                if (isset($login_result)) {
                    $login_status = $login_result['status'];
                    $message = $login_result['message'];
                    $login_data = $login_result['data'];
                    if ($login_status == 'error') {
                        $this->session->set_flashdata('flash_message', $message);
                        $this->session->set_flashdata('flash_message_css', 'alert-danger');
                        redirect(current_url());
                    }
                    if ($login_status == 'success') {
                        $this->session->set_userdata('sess_user', $login_data);
                        if($this->session->userdata('sess_post_login_redirect_url')){
                            redirect($this->session->userdata('sess_post_login_redirect_url'));
                        }else{
                            redirect($this->router->directory.'home');
                        }
                    }
                }
            }
        }

		$this->data['page_title'] = 'Please sign in';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/login', $this->data, true);
        $this->load->view('admin/_layouts/layout_login', $this->data);
    }

    function home() {
        $this->profile();
    }

    function auth_error() {        
		$this->data['page_title'] = 'Authorization Error Occured';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/auth_error', $this->data, true);
        $this->load->view('admin/_layouts/layout_login', $this->data);
    }

    function validate_login_form_data() {
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_password', 'password', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
	
	function create_account() {
		########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        $is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        ########### Validate User Auth End #############
		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'create_account') {
            if ($this->validate_create_account_form_data() == true) {
                $activation_token = md5(time('Y-m-d h:i:s'));
                $dob = $this->input->post('dob_year') . '-' . $this->input->post('dob_month') . '-' . $this->input->post('dob_day');
				$user_emp_id = $this->user_model->get_new_emp_id();
				$password = $this->generate_password();				
                $postdata = array(
                    'user_title' => $this->input->post('user_title'),                    
                    'user_firstname' => ucwords(strtolower($this->input->post('user_firstname'))),                   
                    'user_lastname' => ucwords(strtolower($this->input->post('user_lastname'))),
                    'user_gender' => $this->input->post('user_gender'),
                    'user_email' => strtolower($this->input->post('user_email')),
                    'user_email_secondary' => strtolower($this->input->post('user_email_secondary')),
                    'user_dob' => $dob,
                    'user_doj' => $this->common_lib->convert_to_mysql($this->input->post('user_doj')),
                    'user_role' => $this->input->post('user_role'),
                    'user_department' => $this->input->post('user_department'),
                    'user_designation' => $this->input->post('user_designation'),
                    'user_phone1' => $this->input->post('user_phone1'),
                    'user_phone2' => $this->input->post('user_phone2'),
                    'user_password' => md5($password),
                    'user_activation_key' => $activation_token,
                    'user_registration_ip' => $_SERVER['REMOTE_ADDR'],
                    'user_status' => 'Y',
                    'user_emp_id' => $user_emp_id
                );
				//print_r($postdata); die();
                $insert_id = $this->user_model->insert($postdata);
                if ($insert_id) {
                    $message_html = '';
                    $message_html.='<div id="message_wrapper" style="font-family:Arial, Helvetica, sans-serif; border: 3px solid #5133AB; border-left:0px; border-right: 0px; font-size:13px;">';
                    $message_html.='<div id="message_header" style="display:none;background-color:#5133AB; padding: 10px;"></div>';
                    $message_html.='<div id="message_body" style="padding: 10px;">';
                    $message_html.='<h4>Hi '. ucwords(strtolower($this->input->post('user_firstname'))).' '.ucwords(strtolower($this->input->post('user_lasttname'))) .' ,</h4>';
                    $message_html.='<p>Welcome to smCI research & development. Your have been successfully registered. Please click on the below link to activate your account. Once your account is activated you will be able to login.</p>';
                    $message_html.='<p>'.anchor(base_url($this->router->class.'/activate_account/'.$insert_id.'/'.$activation_token),NULL).'</p>';
                    $message_html.='<p>Here is your details -</p>';
                    $message_html.='<p>Portal URL : '.anchor(base_url()).' <br> Username/Email : '.strtolower($this->input->post('user_email')).'<br> Password : '. $password .'</p>';
                    $message_html.='</div><!--/#message_body-->';
                    $message_html.='<div id="message_footer" style="padding: 10px; font-size: 11px;">';
                    $message_html.='<p>* This is a system generated email message. Please do not reply.</p>';
                    $message_html.='</div><!--/#message_footer-->';
                    $message_html.='</div><!--/#message_wrapper-->';
                    //echo $message_html; die();
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->to($this->input->post('user_email'));
                    $this->email->from($this->config->item('app_admin_email'), $this->config->item('app_admin_email_name'));
                    $this->email->subject('Welcome to '.$this->config->item('app_email_subject_prefix') . 'Your Account Details');
                    $this->email->message($message_html);
                    $this->email->send();
                    //echo $this->email->print_debugger();
                    $this->session->set_flashdata('flash_message', 'User account has been created successfully. <br>System generated User ID <span class="font-weight-bold h5">'.$user_emp_id.'</span>. <br>Account activation link will be sent to the registered email address.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = "Add New User";
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/create_account', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

    function validate_create_account_form_data() {
        $this->form_validation->set_rules('user_title', 'title', 'required');
        $this->form_validation->set_rules('user_firstname', 'first name', 'required|alpha|min_length[3]|max_length[25]');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required|alpha_numeric_spaces|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('user_gender', 'gender selection', 'required');
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email|callback_valid_email_domain|callback_is_email_registered');
        $this->form_validation->set_rules('user_email_secondary', 'personal email', 'valid_email|differs[user_email]');
        //$this->form_validation->set_rules('user_password', 'password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('user_phone1', 'mobile (personal)', 'required|trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_phone2', 'mobile (work)', 'trim|min_length[10]|max_length[10]|numeric|differs[user_phone1]');
        //$this->form_validation->set_rules('user_password_confirm', 'confirm password', 'required|matches[user_password]');
        $this->form_validation->set_rules('dob_day', 'birth day selection', 'required');
        $this->form_validation->set_rules('dob_month', 'birth month selection', 'required');
        $this->form_validation->set_rules('dob_year', 'birth year selection', 'required');
        //$this->form_validation->set_rules('user_dob', 'date of birth', 'required');
        //$this->form_validation->set_rules('user_doj', 'date of joining', 'required');
        $this->form_validation->set_rules('user_role', 'access group', 'required');
        //$this->form_validation->set_rules('user_designation', 'designation', 'required');
        //$this->form_validation->set_rules('user_department', 'department', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

	function valid_email_domain($str){
		/*if($str){
			if(stristr($str,'@domain.com') !== false){
				return true;
			}else{
				$this->form_validation->set_message('valid_email_domain', 'Please provide an acceptable email address.');
				return false;
			}
		}*/
        return true;
	}
	
    function is_email_registered($user_email, $action_type = NULL) {
        //echo $user_email;die();
        $result = $this->user_model->check_is_email_registered($user_email);
        if ($result) {
            $this->form_validation->set_message('is_email_registered', $user_email . ' is already registered !');
            if ($action_type == 'forgot_password') {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    function forgot_password() {		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');

        if ($this->input->post('form_action') == 'forgot_password') {
            if ($this->validate_forgot_password_form() == true) {
				//print_r($_POST);die();
                $email = $this->input->post('user_email');
                $password_reset_key = $this->generate_password();

                $postdata = array('user_reset_password_key' => md5($password_reset_key));
                $where = array('user_email' => $email);

                $result = $this->user_model->update($postdata, $where);
                if ($result) {
                    $to_email = $email;                    
                    $message_html = '';
                    $message_html.='<div id="message_wrapper" style="font-family:Arial, Helvetica, sans-serif; border: 3px solid #5133AB; border-left:0px; border-right: 0px; font-size:13px;">';
                    $message_html.='<div id="message_header" style="display:none;background-color:#5133AB; padding: 10px;"></div>';
                    $message_html.='<div id="message_body" style="padding: 10px;">';
                    $message_html.='<h4>Hi ,</h4>';
                    $message_html.='<p>Please click on the below link to create new password.</p>';
                    $message_html.='<p>'.anchor(base_url($this->router->directory.$this->router->class.'/reset_password/' . md5($password_reset_key)), NULL).'</p>';                    
                    $message_html.='</div><!--/#message_body-->';
                    $message_html.='<div id="message_footer" style="padding: 10px; font-size: 11px;">';
                    $message_html.='<p>* This is a system generated email message. Please do not reply.</p>';
                    $message_html.='</div><!--/#message_footer-->';
                    $message_html.='</div><!--/#message_wrapper-->';

                    // load email lib and email results
                    //die($message_html);
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->to($email);
                    $this->email->from($this->config->item('app_admin_email'), $this->config->item('app_admin_email_name'));
                    $this->email->subject($this->config->item('app_email_subject_prefix') . ' Password Reset Link');
                    $this->email->message($message_html);
                    $this->email->send();
                    //echo $this->email->print_debugger();

                    $this->session->set_flashdata('flash_message', 'Password reset link will be sent to ' . $email);
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = 'Forgot Password?';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/forgot_password', $this->data, true);
        $this->load->view('admin/_layouts/layout_login', $this->data);
    }

    function validate_forgot_password_form() {		
        $this->form_validation->set_rules('user_email', 'email address', 'required|valid_email|callback_is_email_valid');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function reset_password() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $this->data['password_reset_key'] = $this->uri->segment(3);

        if (!isset($this->data['password_reset_key'])) {
            $this->data['alert_message'] = 'The password reset token not found.';
            $this->data['alert_message_css'] = 'alert-danger';
        }

        if ($this->input->post('form_action') == 'reset_password') {
            if ($this->validate_reset_password_form() == true) {
                $email = $this->input->post('user_email');
                $password_reset_key = $this->input->post('password_reset_key');
                $new_password = $this->input->post('user_new_password');
                $is_valid_password_key = $this->user_model->check_user_password_reset_key($email, $password_reset_key);
                if ($is_valid_password_key == TRUE) {
                    $postdata = array('user_password' => md5($new_password),);
                    $where = array('user_email' => $email, 'user_reset_password_key' => $password_reset_key,);
                    $result = $this->user_model->update($postdata, $where);
                    if ($result) {
                        // Set user_reset_password_key to NULL on password update
                        $postdata = array('user_reset_password_key' => NULL,);
                        $where = array('user_email' => $email,);
                        $result2 = $this->user_model->update($postdata, $where);
                        // End Set user_reset_password_key to NULL on password update    

                        $this->session->set_flashdata('flash_message', 'Password changed successfully.');
                        $this->session->set_flashdata('flash_message_css', 'alert-success');
                        redirect(current_url());
                    }
                } else {
                    $this->session->set_flashdata('flash_message', 'Invalid email or password reset link.');
                    $this->session->set_flashdata('flash_message_css', 'alert-danger');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = 'Create New Password';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/reset_password', $this->data, true);
        $this->load->view('admin/_layouts/layout_login', $this->data);
    }

    function validate_reset_password_form() {
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_new_password', 'new password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_user_new_password', 'confirm password', 'required|matches[user_new_password]');

        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function generate_password($length = 6) {
        $str = "";
        $chars = "2346789ABCDEFGHJKLMNPQRTUVWX@$%!";    // Remove confuing digits, alphabets
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    function is_email_valid($user_email) {		
        if($user_email){
            $result = $this->user_model->check_is_email_registered($user_email);			
            if ($result == true) {
                return true;
            }else{
				$this->form_validation->set_message('is_email_valid', $user_email . ' is not a registered email address.');
				return false;
			}            
        }
    }

    function change_password() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        
        ########### Validate User Auth End #############

        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'change_password') {
            if ($this->validate_changepassword_form() == true) {
                $postdata = array('user_password' => md5($this->input->post('user_new_password')));
                $where = array('id' => $this->sess_user_id);
                $this->user_model->update($postdata, $where);
                $this->session->set_flashdata('flash_message', 'Password changed successfully.');
                $this->session->set_flashdata('flash_message_css', 'alert-success');
                redirect(current_url());
            }
        }
		$this->data['page_title'] = 'Change Password';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/change_password', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

    function validate_changepassword_form() {
        $this->form_validation->set_rules('user_current_password', 'current password', 'required|callback_check_current_password');        
        $this->form_validation->set_rules('user_new_password', 'new password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_user_new_password', 'confirm password', 'required|matches[user_new_password]');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function check_current_password($password) {
        if($password){
            $result = $this->user_model->check_user_password_valid(md5($password), $this->sess_user_id);
            if ($result == false) {
                $this->form_validation->set_message('check_current_password', 'The {field} field is invalid');
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    function logout() {
        if (isset($this->session->userdata['sess_user'])) {
            $this->session->unset_userdata('sess_user');
            $this->session->unset_userdata('sess_post_login_redirect_url');
            $this->session->sess_destroy();
            $this->session->set_flashdata('flash_message', 'You have been logged out successfully.');
            $this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect($this->router->directory.$this->router->class.'/login');
        } else {
            redirect($this->router->directory.'home');
        }
    }

    function profile() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        /*$is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));*/
        ########### Validate User Auth End #############
		
        //View Page Config
        $is_self_account = true;
        if(!empty($this->uri->segment(4)) && ($this->uri->segment(4) != $this->sess_user_id)){
            $is_self_account = false;
        }
        $this->data['is_self_account'] = $is_self_account;
        $this->data['page_title'] = "Profile";
        $this->breadcrumbs->push('Profile','/');
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();

        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		$user_id = $this->uri->segment(4)? $this->uri->segment(4): $this->sess_user_id;	
        $rows = $this->user_model->get_rows($user_id);		
		$res_pic = $this->user_model->get_user_profile_pic($user_id);
		$this->data['profile_pic'] = $res_pic[0]['user_profile_pic'];
        $this->data['row'] = $rows['data_rows'];
		$this->data['address'] = $this->user_model->get_user_address(NULL,$user_id,NULL);
        $this->data['education'] = $this->user_model->get_user_education(NULL, $user_id);
        $this->data['job_exp'] = $this->user_model->get_user_work_experience(NULL, $user_id);
		$this->data['page_title'] = ($is_self_account == true) ? "Profile" : "Customer Profile";
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/profile', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

	function validate_edit_profile_form() {
        //$this->form_validation->set_rules('user_firstname', 'first name', 'required');
        //$this->form_validation->set_rules('user_lastname', 'last name', 'required');
        //$this->form_validation->set_rules('user_gender', 'gender selection', 'required');        
        $this->form_validation->set_rules('user_phone1', 'personal mobile', 'required|trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_phone2', 'office mobile', 'trim|min_length[10]|max_length[10]|numeric|differs[user_phone1]');
        $this->form_validation->set_rules('user_bio', 'about you', 'max_length[100]');
        $this->form_validation->set_rules('user_email', 'registered email (work)', 'required|valid_email');
        $this->form_validation->set_rules('user_email_secondary', 'personal email', 'required|valid_email|differs[user_email]');
        $this->form_validation->set_rules('user_blood_group', 'blood group', 'required');
        /* $this->form_validation->set_rules('dob_day', 'birth day selection', 'required');
          $this->form_validation->set_rules('dob_month', 'birth month selection', 'required');
          $this->form_validation->set_rules('dob_year', 'birth year selection', 'required'); */
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
    	
	function calander_days() {
        $result = array();
        $result[''] = 'Day';
        for ($i = 0; $i < 31; $i++) {
            $result[$i + 1] = sprintf('%02d', $i + 1);
        }
        return $result;
    }

    function calander_months() {
        $result = array(
            '' => 'Month',
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        );
        return $result;
    }

    function calander_years() {
        $result = array();
        $result[''] = 'Year';
        $current_year = (date('Y')-18); // 18 years age
        $start_year = ($current_year - 100);
        for ($i = $current_year; $i > $start_year; $i--) {
            $result[$i] = $i;
        }
        return $result;
    }
	
	function edit_profile() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        /*$is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));*/
        ########### Validate User Auth End #############
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $rows = $this->user_model->get_rows($this->sess_user_id);
        $this->data['row'] = $rows['data_rows'];

        if ($this->input->post('form_action') == 'update_profile') {
            if ($this->validate_edit_profile_form() == true) {
                $postdata = array(
                    //'user_firstname' => $this->input->post('user_firstname'),
                    //'user_lastname' => $this->input->post('user_lastname'),
                    'user_bio' => $this->input->post('user_bio'),
                    //'user_gender' => $this->input->post('user_gender'),
                    //'user_dob' => $dob,
                    'user_phone1' => $this->input->post('user_phone1'),
                    'user_phone2' => $this->input->post('user_phone2'),
                    'user_email_secondary' => $this->input->post('user_email_secondary'),
                    'user_blood_group' => $this->input->post('user_blood_group'),
                );
                $where = array('id' => $this->sess_user_id);
                $res = $this->user_model->update($postdata, $where);
                if ($res) {
                    $this->session->set_flashdata('flash_message', 'Basic information has been updated successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
	
		$this->data['page_title'] = 'Edit Basic Information';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/edit_profile', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

    function validate_edit_user_profile_form() {
        $this->form_validation->set_rules('user_title', 'title', 'required');
        $this->form_validation->set_rules('user_firstname', 'first name', 'required|alpha|min_length[3]|max_length[25]');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required|alpha_numeric_spaces|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('user_gender', 'gender selection', 'required');
        $this->form_validation->set_rules('dob_day', 'birth day selection', 'required');
        $this->form_validation->set_rules('dob_month', 'birth month selection', 'required');
        $this->form_validation->set_rules('dob_year', 'birth year selection', 'required');
        //$this->form_validation->set_rules('user_dob', 'date of birth', 'required');
        //$this->form_validation->set_rules('user_doj', 'date of joining', 'required');
        //$this->form_validation->set_rules('user_role', 'access group', 'required');
        //$this->form_validation->set_rules('user_designation', 'designation', 'required');
        //$this->form_validation->set_rules('user_department', 'department', 'required');
        //$this->form_validation->set_rules('user_status', 'account status', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
	
	function profile_pic() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        /*$is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));*/
        ########### Validate User Auth End #############
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        
		$this->data['user_id'] = $this->sess_user_id;
		
		$res_pic = $this->user_model->get_user_profile_pic($this->sess_user_id);
		$this->data['profile_pic'] = $res_pic[0]['user_profile_pic'];
		
        if ($this->input->post('form_action') == 'file_upload') {
            $this->upload_file();
        }
	
		$this->data['page_title'] = 'Profile Photo';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/profile_pic', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }
	
	function validate_uplaod_form_data() {
        //$this->form_validation->set_rules('userfile', 'file selection field', 'required');        
        //$this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        //if ($this->form_validation->run() == true) {
            return true;
        //} else {
            //return false;
        //}
    }
	
	function upload_file() {
        if ($this->validate_uplaod_form_data() == true) {
            $upload_related_to = 'user';
            $upload_related_to_id = $this->sess_user_id;
            $upload_file_type_name = 'profile_pic';

            //Create directory for object specific
            $upload_path = 'assets/uploads/user/profile_pic';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }
            $allowed_ext = 'png|jpg|jpeg|doc|docx|pdf';
            if ($upload_file_type_name == 'profile_pic') {
                $allowed_ext = 'jpg|jpeg';
            }
            $upload_param = array(
                'upload_path' => $upload_path, // original upload folder
                'allowed_types' => $allowed_ext, // allowed file types,
                'max_size' => '2048', // max 1MB size,
                'file_new_name' => $upload_related_to_id . '_' . md5($upload_file_type_name . '_' . time()),
				'thumb_img_require' => TRUE,
				'thumb_img_path'=>$upload_path,
				'thumb_img_width'=>'250',
				'thumb_img_height'=>'300'
            );
            $upload_result = $this->common_lib->upload_file('userfile', $upload_param);
            if (isset($upload_result['file_name']) && empty($upload_result['upload_error'])) {
                $uploaded_file_name = $upload_result['file_name'];
                /*$postdata = array(
                    'upload_related_to' => $upload_related_to,
                    'upload_related_to_id' => $upload_related_to_id,
                    'upload_file_type_name' => $upload_file_type_name,
                    'upload_file_name' => $uploaded_file_name,
                    'upload_mime_type' => $upload_result['file_type'],
                    'upload_by_user_id' => $this->sess_user_id
                );*/
				
				$postdata = array(                    
                    'user_profile_pic' => $uploaded_file_name
                );
				$where_array = array('id'=>$this->sess_user_id);
				

                //Check if already 1 file of same upload_file_type_name is uploaded, over ride it.
				//If you do not want to override, want to keep multiple uploaded copy, 
				//add those upload_file_type_name in skip_checking_existing_doc_type_name array
                $multiple_allowed_upload_file_type = array();

                if (!in_array($upload_file_type_name, $multiple_allowed_upload_file_type)) {					
                    $cond = array(
						'upload_related_to' => $upload_related_to,
						'upload_related_to_id' => $upload_related_to_id,
						'upload_file_type_name' => $upload_file_type_name
					);
                    $upload_data = $this->upload_model->get_uploads(NULL, NULL, NULL, FALSE, FALSE, $cond);
					$uploads = $upload_data['data_rows'];
                }
                if (isset($uploads[0]) && ($uploads[0]['id'] != '')) {
                    //Unlink previously uploaded file                    
                    $file_path = $upload_param['upload_path'] . '/' . $uploads[0]['upload_file_name'];
                    if (file_exists(FCPATH . $file_path)) {
                        $this->common_lib->unlink_file(array(FCPATH . $file_path));
                    }
                    // Now update table
                    //$update_upload = $this->user_model->update($postdata, array('id' => $uploads[0]['id']), 'uploads');
                    $update_upload = $this->user_model->update($postdata, $where_array);
                    $this->session->set_flashdata('flash_message', 'Profile photo uploaded successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                } else {
                    //$upload_insert_id = $this->user_model->insert($postdata, 'uploads');
                    $update_upload = $this->user_model->update($postdata, $where_array);
                    $this->session->set_flashdata('flash_message', 'Profile photo uploaded successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                }
            } else if (sizeof($upload_result['upload_error']) > 0) {
                $error_message = $upload_result['upload_error'];
                $this->session->set_flashdata('flash_message',$error_message);
                $this->session->set_flashdata('flash_message_css', 'alert-danger');
                redirect(current_url());
            }
        }
    }
	
	function delete_profile_pic(){
		//$uploaded_file_id = $this->uri->segment(3);
		$uploaded_file_name = $this->uri->segment(3);
		//if($uploaded_file_name){
			//Unlink previously uploaded file                    
			$file_path = 'assets/uploads/user/profile_pic/'.$uploaded_file_name;
			if (file_exists(FCPATH . $file_path)) {
				$this->common_lib->unlink_file(array(FCPATH . $file_path));
				//$res = $this->user_model->delete(array('id'=>$uploaded_file_id),'uploads');
				$postdata = array(                    
                    'user_profile_pic' => NULL
                );
				$where_array = array('id'=>$this->sess_user_id);
				$res = $this->user_model->update($postdata, $where_array);
				if($res){
					$this->session->set_flashdata('flash_message', 'Profile photo has been deleted successfully.');
					$this->session->set_flashdata('flash_message_css', 'alert-success');
					redirect($this->router->directory.$this->router->class.'/profile_pic');
				}else{
					$this->session->set_flashdata('flash_message', 'Error occured while processing your request.');
					$this->session->set_flashdata('flash_message_css', 'alert-danger');
					redirect($this->router->directory.$this->router->class.'/profile_pic');
				}
			}
		//}
    }

    function download_to_excel(){
        $excel_heading = array(
            'A' => 'Sr No.',
            'B' => 'Name',
            'C' => 'Email',
            'D' => 'Mobile',
            'E' => 'Account Status'
        );
        $this->data['xls_col'] = $excel_heading;
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        //name the worksheet
        $sheet->setTitle('Users');

        $result_array = $this->user_model->get_rows(NULL, NULL, NULL, TRUE);
        $data_rows = $result_array['data_rows'];

        //echo '<pre>';
        //print_r($data_rows);
        //die();
        // read data to active sheet
        //$sheet->fromArray($data_rows);
        
        // Static Fields
        $sheet->setCellValue('A1', 'Active Account');
        $sheet->getStyle('A1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '09bc09'))));
        
        $sheet->setCellValue('A2', 'Inactive Account');
        $sheet->getStyle('A2')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ef0909'))));
        //End Static Fields

        $range = range('A', 'Z');
        $heading_row = 5;
        $index = 0;
        foreach ($excel_heading as $column => $heading_display) {
            $sheet->setCellValue($range[$index] . $heading_row, $heading_display);
            $index++;
        }


        $excel_row = 6;
        $serial_no = 1;
        foreach ($data_rows as $index => $row) {
            $sheet->setCellValue('A' . $excel_row, $serial_no);
            $sheet->setCellValue('B' . $excel_row, $row['user_firstname'].' '.$row['user_lastname']);
            $sheet->setCellValue('C' . $excel_row, $row['user_email']);
            $sheet->setCellValue('D' . $excel_row, $row['user_phone1']);
            
            $sheet->setCellValue('E' . $excel_row, $row['user_status']);
            if($row['user_status'] == 'N'){
                $color = 'ef0909'; //red
            }  
            if($row['user_status'] == 'Y'){
                $color = '09bc09'; //green
            }        
            
            if ($color) {
                $sheet->getStyle('E' . $excel_row)->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '4d4d4d')
                        )
                    ),
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => $color))));
            }                     
            $excel_row++;
            $serial_no++;
        }


        // Color Config
        $default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '0')
        );
        $style_header = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '80bfff'),
            ),
            'font' => array(
                'bold' => true
            )
        );
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '4d4d4d')
                )
            )
        );
        $sheet->getDefaultStyle()->applyFromArray($styleArray);
        $sheet->getStyle('A5:R5')->applyFromArray($style_header);
        $sheet->getStyle('A5:R5')->getFont()->setSize(9);
        $sheet->getDefaultStyle()->getFont()->setSize(10);
        $sheet->getDefaultColumnDimension()->setWidth('17');

        // // Check box
        // $required_cols = array();
        // if (isset($_POST['columns'])) {
        //     foreach ($_POST['columns'] as $key => $xls_column) {
        //         $required_cols[] = $xls_column;
        //     }
        // }

        // $all_cols = array();
        // if (isset($_POST['all_cols'])) {
        //     foreach ($_POST['all_cols'] as $key => $xls_column) {
        //         $all_cols[] = $xls_column;
        //     }
        // }

        // $removable_cols = array_diff($all_cols, $required_cols);
        // //print_r($removable_cols);

        // if (isset($removable_cols)) {
        //     foreach ($removable_cols as $key => $col) {
        //         $sheet->removeColumn($col);
        //     }
        // }
        // //die();


        $filename = 'User_Tracker' . date('d-m-Y') . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

}

?>