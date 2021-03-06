<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    var $data;
    var $id;
    var $sess_user_id;
    
    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('upload_model');
        
        // Get logged  in user id
        $this->sess_user_id = $this->app_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->app_lib->init_template_elements('site');

        // Load required js files for this controller
        $javascript_files = array(
            $this->router->class
        );
        $this->data['app_js'] = $this->app_lib->add_javascript($javascript_files);
        
        $this->data['page_title'] = $this->router->class.' : '.$this->router->method;

		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Home', '/home');
		//$this->breadcrumbs->push('User', '/user/manage');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
		// Address Types
        $this->data['address_type'] = array('P'=>'Permanent Address','C'=>'Present Address','S'=>'Shipping','B'=>'Billing');

		//User Roles drop down
		$this->data['arr_roles'] = $this->user_model->get_user_role_dropdown();
		$this->data['arr_designations'] = $this->user_model->get_designation_dropdown('Y');
		$this->data['arr_departments'] = $this->user_model->get_department_dropdown();
		$this->data['arr_user_title'] = array(''=>'Select Title','Mr.'=>'Mr.','Mrs.'=>'Mrs.','Dr.'=>'Dr.','Ms.'=>'Ms.');
		$this->data['blood_group'] = array(''=>'Select','O+'=>'O+','O-'=>'O-','A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-');
		
		// Status flag indicator for showing in table grid etc
		$this->data['status_flag'] = array(
            'Y'=>array('text'=>'Active', 'css'=>'text-success', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-success" aria-hidden="TRUE"></i>'),
            'N'=>array('text'=>'Inactive', 'css'=>'text-warning', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-warning" aria-hidden="TRUE"></i>'),
            'A'=>array('text'=>'Archived', 'css'=>'text-danger', 'icon'=>'<i class="fa fa-fw fa-bookmark-o text-danger" aria-hidden="TRUE"></i>')
        );
    }

    function index() {
        $this->login();
    }
    
    function login() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == TRUE) {
            if($this->data['is_admin'] === TRUE){
                redirect($this->router->directory.'admin/dashboard');
            }else{
                redirect($this->router->directory.'home');
            }
        }
        if ($this->input->post('form_action') == 'login') {
            if ($this->validate_login_form_data() == TRUE) {
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
                        $this->app_lib->set_flash_message($message, 'alert-danger');
                        redirect(current_url());
                    }
                    if ($login_status == 'success') {
                        $this->session->set_userdata('sess_user', $login_data);
                        if($this->session->userdata('sess_post_login_redirect_url')){
                            redirect($this->session->userdata('sess_post_login_redirect_url'));
                        }else{
                            if($this->data['is_admin'] === TRUE){
                                redirect($this->router->directory.'home/dashboard');
                            }else{
                                redirect($this->router->directory.'home');
                            }
                        }
                    }
                }
            }
        }
        if($this->data['is_admin'] === TRUE){
            $this->app_lib->init_template_elements('admin');
            $this->data['page_title'] = 'Please sign in';
            $this->data['maincontent'] = $this->load->view($this->router->class.'/admin_login', $this->data, TRUE);
            $this->load->view('_layouts/layout_admin_login', $this->data);
        }else{
            $this->data['page_title'] = 'Please sign in';
            $this->data['maincontent'] = $this->load->view($this->router->class.'/login', $this->data, TRUE);
            $this->load->view('_layouts/layout_default', $this->data);
        }
		
    }

    function auth_error() {
		$this->data['page_title'] = 'Authorization Error Occured';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/auth_error', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function validate_login_form_data() {
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_password', 'password', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	function registration() {
        if ($this->input->post('form_action') == 'self_registration') {
            if ($this->validate_registration_form_data() == TRUE) {
                $activation_token = md5(time('Y-m-d h:i:s'));
				$customer_id = $this->user_model->get_new_customer_id();
                $postdata = array(
                    'user_firstname' => ucwords(strtolower($this->input->post('user_firstname'))),
                    'user_lastname' => ucwords(strtolower($this->input->post('user_lastname'))),
                    'user_gender' => $this->input->post('user_gender'),
                    'user_email' => strtolower($this->input->post('user_email')),
					'user_role' => $this->input->post('user_role'),
                    'user_email_secondary' => strtolower($this->input->post('user_email_secondary')),
                    'user_dob' => $this->app_lib->convert_to_mysql($this->input->post('user_dob')),
					'user_phone1' => $this->input->post('user_phone1'),
                    'user_password' => md5($this->input->post('user_password')),
                    'user_activation_key' => $activation_token,
                    'user_registration_ip' => $_SERVER['REMOTE_ADDR'],
                    'user_status' => 'Y', // Set N for prod
                    'user_emp_id' => $customer_id
                );
				//print_r($postdata); die();
                $insert_id = $this->user_model->insert($postdata);
                if ($insert_id) {
                    $message_html = '';
                    $message_html.='<div id="message_wrapper" style="font-family:Arial, Helvetica, sans-serif; border: 3px solid #5133AB; border-left:0px; border-right: 0px; font-size:13px;">';
                    $message_html.='<div id="message_header" style="display:none;background-color:#5133AB; padding: 10px;"></div>';
                    $message_html.='<div id="message_body" style="padding: 10px;">';
                    $message_html.='<h4>Hi '. ucwords(strtolower($this->input->post('user_firstname'))).' '.ucwords(strtolower($this->input->post('user_lasttname'))) .' ,</h4>';
                    $message_html.='<p>Welcome to sm CI app. Your have been successfully registered. Please click on the below link to activate your account. Once your account is activated you will be able to login.</p>';
                    $message_html.='<p>'.anchor(base_url($this->router->class.'/activate_account/'.$insert_id.'/'.$activation_token),NULL).'</p>';
                    $message_html.='<p>Here is your details -</p>';
                    $message_html.='<p>Portal URL : '.anchor(base_url()).' <br> Username/Email : '.strtolower($this->input->post('user_email')).'<br> Password : '.$this->input->post('user_password').'</p>';
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
                    $this->app_lib->set_flash_message('Your account has been created successfully. You will receive account activation link in your registered email. Please activate your account to login.', 'alert-success');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = "Sign Up";
        $this->data['maincontent'] = $this->load->view($this->router->class.'/registration', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function validate_registration_form_data() {
        $this->form_validation->set_rules('user_firstname', 'first name', 'required|alpha|min_length[3]|max_length[25]');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required|alpha_numeric_spaces|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('user_gender', 'gender selection', 'required');
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email|callback_valid_email_domain|callback_is_email_registered');        
        $this->form_validation->set_rules('user_password', 'password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('user_phone1', 'mobile number', 'required|trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_password_confirm', 'confirm password', 'required|matches[user_password]');
        $this->form_validation->set_rules('user_dob', 'date of birth', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function create_account() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        $this->app_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        if ($this->input->post('form_action') == 'create_account') {
            if ($this->validate_create_account_form_data() == TRUE) {
                $activation_token = md5(time('Y-m-d h:i:s'));
				$customer_id = $this->user_model->get_new_customer_id();
				$password = $this->generate_password();
                $postdata = array(
                    'user_firstname' => ucwords(strtolower($this->input->post('user_firstname'))),
                    'user_lastname' => ucwords(strtolower($this->input->post('user_lastname'))),
                    'user_gender' => $this->input->post('user_gender'),
                    'user_email' => strtolower($this->input->post('user_email')),
                    'user_email_secondary' => strtolower($this->input->post('user_email_secondary')),
                    'user_dob' => $this->app_lib->convert_to_mysql($this->input->post('user_dob')),
                    'user_role' => $this->input->post('user_role'),
                    'user_department' => $this->input->post('user_department'),
                    'user_designation' => $this->input->post('user_designation'),
                    'user_phone1' => $this->input->post('user_phone1'),
                    'user_phone2' => $this->input->post('user_phone2'),
                    'user_password' => md5($password),
                    'user_activation_key' => $activation_token,
                    'user_registration_ip' => $_SERVER['REMOTE_ADDR'],
                    'user_status' => 'Y',
                    'user_emp_id' => $customer_id
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
                    $this->app_lib->set_flash_message('User account has been created successfully. <br>System generated User ID <span class="font-weight-bold h5">'.$customer_id.'</span>. <br>Account activation link will be sent to the registered email address.', 'alert-success');
                    redirect(current_url());
                }
            }
        }
        $this->app_lib->init_template_elements('admin');
		$this->data['page_title'] = "Add New User";
        $this->data['maincontent'] = $this->load->view($this->router->class.'/create_account', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function validate_create_account_form_data() {
        $this->form_validation->set_rules('user_firstname', 'first name', 'required|alpha|min_length[3]|max_length[25]');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required|alpha_numeric_spaces|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('user_gender', 'gender selection', 'required');
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email|callback_valid_email_domain|callback_is_email_registered');
        $this->form_validation->set_rules('user_email_secondary', 'personal email', 'valid_email|differs[user_email]');
        $this->form_validation->set_rules('user_phone1', 'mobile (personal)', 'required|trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_phone2', 'mobile (work)', 'trim|min_length[10]|max_length[10]|numeric|differs[user_phone1]');
        $this->form_validation->set_rules('user_dob', 'date of birth', 'required');
        $this->form_validation->set_rules('user_role', 'access group', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	function valid_email_domain($str){
		/*if($str){
			if(stristr($str,'@domain.com') !== FALSE){
				return TRUE;
			}else{
				$this->form_validation->set_message('valid_email_domain', 'Please provide an acceptable email address.');
				return FALSE;
			}
		}*/
        return TRUE;
	}
	
    function is_email_registered($user_email, $action_type = NULL) {
        //echo $user_email;die();
        $result = $this->user_model->check_is_email_registered($user_email);
        if ($result) {
            $this->form_validation->set_message('is_email_registered', $user_email . ' is already registered !');
            if ($action_type == 'forgot_password') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }

    function activate_account() {
		$user_id = $this->uri->segment(3);
		$activation_key = $this->uri->segment(4);
        $res = $this->user_model->check_user_activation_key($user_id, $activation_key);
        if ($res) {
            $postdata = array('user_status' => 'Y');
            $where = array('id' => $user_id, 'user_activation_key' => $activation_key);
            $act_res = $this->user_model->update($postdata, $where);
            if ($act_res) {
                $this->app_lib->set_flash_message('Your account has been activated successfully', 'alert-success');
                redirect($this->router->directory.$this->router->class.'/login');
            } else {
                $this->app_lib->set_flash_message('Sorry ! Unable to activate your account', 'alert-danger');
                redirect($this->router->directory.$this->router->class.'/login');
            }
        } else {
            $this->app_lib->set_flash_message('No activation token match found for you', 'alert-danger');
            redirect($this->router->directory.$this->router->class.'/login');
        }
    }

    function forgot_password() {
        if ($this->input->post('form_action') == 'forgot_password') {
            if ($this->validate_forgot_password_form() == TRUE) {
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

                    $this->app_lib->set_flash_message('Password reset link will be sent to ' . $email, 'alert-success');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = 'Forgot Password?';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/forgot_password', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function validate_forgot_password_form() {		
        $this->form_validation->set_rules('user_email', 'email address', 'required|valid_email|callback_is_email_valid');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function reset_password() {
        $this->data['password_reset_key'] = $this->uri->segment(3);
        if (!isset($this->data['password_reset_key'])) {
            $this->app_lib->set_flash_message('The password reset token not found.', 'alert-danger');
        }
        if ($this->input->post('form_action') == 'reset_password') {
            if ($this->validate_reset_password_form() == TRUE) {
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

                        $this->app_lib->set_flash_message('Password changed successfully.', 'alert-success');
                        redirect(current_url());
                    }
                } else {
                    $this->app_lib->set_flash_message('Invalid email or password reset link.', 'alert-danger');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_title'] = 'Create New Password';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/reset_password', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function validate_reset_password_form() {
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_new_password', 'new password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_user_new_password', 'confirm password', 'required|matches[user_new_password]');

        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
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
            if ($result == TRUE) {
                return TRUE;
            }else{
				$this->form_validation->set_message('is_email_valid', $user_email . ' is not a registered email address.');
				return FALSE;
			}            
        }
    }

    function change_password() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        if ($this->input->post('form_action') == 'change_password') {
            if ($this->validate_changepassword_form() == TRUE) {
                $postdata = array('user_password' => md5($this->input->post('user_new_password')));
                $where = array('id' => $this->sess_user_id);
                $this->user_model->update($postdata, $where);
                $this->app_lib->set_flash_message('Password changed successfully.', 'alert-success');
                redirect(current_url());
            }
        }
        if($this->data['is_admin'] === TRUE){
            $this->app_lib->init_template_elements('admin');
            $this->data['page_title'] = 'Please sign in';
            $this->data['maincontent'] = $this->load->view($this->router->class.'/admin_change_password', $this->data, TRUE);
            $this->load->view('_layouts/layout_admin_default', $this->data);
        }else{
            $this->data['page_title'] = 'Change Password';
            $this->data['maincontent'] = $this->load->view($this->router->class.'/change_password', $this->data, TRUE);
            $this->load->view('_layouts/layout_default', $this->data);
        }
		
    }

    function validate_changepassword_form() {
        $this->form_validation->set_rules('user_current_password', 'current password', 'required|callback_check_current_password');        
        $this->form_validation->set_rules('user_new_password', 'new password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_user_new_password', 'confirm password', 'required|matches[user_new_password]');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_current_password($password) {
        if($password){
            $result = $this->user_model->check_user_password_valid(md5($password), $this->sess_user_id);
            if ($result == FALSE) {
                $this->form_validation->set_message('check_current_password', 'The {field} field is invalid');
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }

    function logout() {
        if (isset($this->session->userdata['sess_user'])) {
            $this->session->unset_userdata('sess_user');
            $this->session->unset_userdata('sess_post_login_redirect_url');
            $this->app_lib->set_flash_message('You have been logged out successfully.','alert-success');
            redirect($this->router->directory.$this->router->class.'/login');
        } else {
            if($this->data['is_admin'] === TRUE){
                redirect($this->router->directory.'admin/login');
            }else{
                redirect($this->router->directory.'home');
            }
        }
    }

    function manage() {
        $this->app_lib->init_template_elements('admin');
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        //Has logged in user permission to access this page or method?        
        $this->app_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        //Download Data 
        if($this->input->post('form_action') == 'download'){
            $this->download_to_excel();
        }
		$this->breadcrumbs->push('View', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		$this->data['page_title'] = 'Manage Users';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/manage', $this->data, TRUE);
        $this->load->view('_layouts/layout_admin_default', $this->data);
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
			$row[] = isset($result['user_status']) ? $this->data['status_flag'][$result['user_status']]['text'] : '';
            
			//add html for action
            $action_html = '';
            
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit_user_profile/' . $result['id']), '<i class="fa fa-fw fa-pencil" aria-hidden="TRUE"></i>', array(
                'class' => 'btn btn-sm btn-outline-secondary',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit Profile',
                'title' => 'Edit Profile'
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/profile/' . $result['id']), '<i class="fa fa-fw fa-info-circle" aria-hidden="TRUE"></i>', array(
                'class' => 'btn btn-sm btn-outline-info',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'View Profile',
                'title' => 'View Profile'
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

    function home() {
        $this->profile();
    }

    function profile() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
		
        //View Page Config
        $is_self_account = TRUE;
        if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) != $this->sess_user_id)){
            $is_self_account = FALSE;
        }
        $this->data['is_self_account'] = $is_self_account;
        $this->data['page_title'] = "My Profile";
        $this->breadcrumbs->push('Profile','/');
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		$user_id =  $this->sess_user_id;		
        $rows = $this->user_model->get_rows($user_id);		
		$res_pic = $this->user_model->get_user_profile_pic($user_id);
		$this->data['profile_pic'] = $res_pic[0]['user_profile_pic'];
        $this->data['row'] = $rows['data_rows'];
		$this->data['address'] = $this->user_model->get_user_address(NULL,$user_id,NULL);
        $this->data['education'] = $this->user_model->get_user_education(NULL, $user_id);
		$this->data['page_title'] = 'My Profile';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/profile', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
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
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function people() {        
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }               
		$this->breadcrumbs->push('People', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        
        
        $search_keywords = NULL;
        if($this->input->get_post('form_action') == 'search'){
            $search_keywords = $this->input->get_post('user_search_keywords');
        }
        //die($search_keywords);

		// Display using CI Pagination: Total filtered rows - check without limit query. Refer to model method definition		
		$result_array = $this->user_model->get_users(NULL, NULL, NULL, $search_keywords);
		$total_num_rows = $result_array['num_rows'];
		
		//pagination config
		$additional_segment = $this->router->class.'/'.$this->router->method;
		$per_page = 12;
		$config['uri_segment'] = 4;
		$config['num_links'] = 1;
		$config['use_page_numbers'] = TRUE;
		//$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4)-1) : 0;
		$offset = ($page*$per_page);
		$this->data['pagination_link'] = $this->app_lib->render_pagination($total_num_rows, $per_page, $additional_segment);
        //end of pagination config
        
        // Data Rows - Refer to model method definition
        $result_array = $this->user_model->get_users(NULL, $per_page, $offset, $search_keywords);
        $this->data['data_rows'] = $result_array['data_rows'];
		
		$this->data['page_title'] = 'People';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/people', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function add_address() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        $this->data['arr_states'] = $this->user_model->get_state_dropdown();		
        if ($this->input->post('form_action') == 'insert_address') {
            if ($this->validate_user_address_form_data('add') == TRUE) {
                $postdata = array(
					'user_id' => $this->sess_user_id,
                    'address_type' => $this->input->post('address_type'),
                    //'name' => $this->input->post('name'),
                    'phone1' => $this->input->post('phone1'),                    
                    'zip' => $this->input->post('zip'),                    
                    'locality' => $this->input->post('locality'),                    
                    'address' => $this->input->post('address'),                    
                    'city' => $this->input->post('city'),                    
                    'state' => $this->input->post('state'),                    
                    //'country' => $this->input->post('country'),                    
                    'landmark' => $this->input->post('landmark'),                    
                    'phone2' => $this->input->post('phone2'),                    
                );                
                $res = $this->user_model->insert($postdata,'user_addresses');
                if ($res) {
                    $this->app_lib->set_flash_message('Address has been added successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
		$this->data['page_title'] = 'Add Address';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add_address', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function edit_address() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
		$address_id = $this->uri->segment(3);        
        $this->data['address'] = $this->user_model->get_user_address($address_id, $this->sess_user_id,NULL);
        $this->data['arr_states'] = $this->user_model->get_state_dropdown();
        //print_r($this->data['address']);die();
        if ($this->input->post('form_action') == 'update_address') {
            if ($this->validate_user_address_form_data('edit') == TRUE) {
                $postdata = array(
					//'user_id' => $this->sess_user_id,
                    //'address_type' => $this->input->post('address_type'),
                    //'name' => $this->input->post('name'),
                    'phone1' => $this->input->post('phone1'),                    
                    'zip' => $this->input->post('zip'),                    
                    'locality' => $this->input->post('locality'),                    
                    'address' => $this->input->post('address'),                    
                    'city' => $this->input->post('city'),                    
                    'state' => $this->input->post('state'),                    
                    //'country' => $this->input->post('country'),                    
                    'landmark' => $this->input->post('landmark'),                    
                    'phone2' => $this->input->post('phone2'),                   
                );
                $where = array('id'=>$address_id, 'user_id' => $this->sess_user_id);
                $res = $this->user_model->update($postdata, $where,'user_addresses');
                if ($res) {
                    $this->app_lib->set_flash_message('Address has been updated successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
		
		$this->data['page_title'] = 'Edit Address';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit_address', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function delete_address() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
		$address_id = $this->uri->segment(3);
        $this->data['address'] = $this->user_model->get_user_address($address_id, $this->sess_user_id,NULL);
		$where = array('id'=>$address_id, 'user_id' => $this->sess_user_id);
		$res = $this->user_model->delete($where,'user_addresses');
		if ($res) {
			$this->app_lib->set_flash_message('Address has been deleted successfully.', 'alert-success');
			redirect($this->router->directory.$this->router->class.'/profile');
		}else{
			$this->app_lib->set_flash_message('We\'re unable to process your request.', 'alert-danger');
			redirect($this->router->directory.$this->router->class.'/profile');
		}
    }
	
	function get_address_types($char_address_type){
		if(isset($char_address_type)){
			return $this->data['address_type'][$char_address_type];
		}else{
			return '';
		}		
    }
    
    function validate_user_address_form_data($mode) {
        if($mode == 'add'){
            $this->form_validation->set_rules('address_type', 'address type selection', 'required|callback_check_is_address_added');            
        }
        if($mode=="edit"){
            //$this->form_validation->set_rules('address_type', 'address type selection', 'required');        		            
        }    

        //$this->form_validation->set_rules('name', ' ', 'required|min_length[3]|alpha_numeric_spaces');        
        $this->form_validation->set_rules('phone1', ' ', 'trim|min_length[10]|max_length[15]|numeric');        
        $this->form_validation->set_rules('zip', ' ', 'required|min_length[6]|max_length[6]|numeric');        
        $this->form_validation->set_rules('locality', ' ', 'required|min_length[3]');        
        $this->form_validation->set_rules('address', ' ', 'required|max_length[120]');               
        $this->form_validation->set_rules('city', ' ', 'required|max_length[20]');        
        $this->form_validation->set_rules('state', ' ', 'required|max_length[30]');        
        //$this->form_validation->set_rules('country', ' ', 'required');        
        $this->form_validation->set_rules('landmark', ' ', 'max_length[100]');        
        //$this->form_validation->set_rules('phone2', ' ', 'min_length[10]|max_length[10]|numeric');    

        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check_is_address_added($str) {      
        //die($str);  
        $result = $this->user_model->check_address_type_exists($this->sess_user_id, $str);
        if ($result) {
            $this->form_validation->set_message('check_is_address_added', 'This address type is already added by you. Please choose another.');
            return FALSE;
        }
        return TRUE;
    }

	function add_education() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
        $this->data['arr_academic_qualification'] = $this->user_model->get_qualification_dropdown();
        $this->data['arr_academic_degree'] = $this->user_model->get_degree_dropdown();
		$this->data['arr_academic_inst'] = $this->user_model->get_institute_dropdown();
		$this->data['arr_academic_specialization'] = $this->user_model->get_specialization_dropdown();
        if ($this->input->post('form_action') == 'add') {
            if ($this->validate_user_education_form_data('add') == TRUE) {
                $postdata = array(
					'user_id' => $this->sess_user_id,
                    'academic_qualification' => $this->input->post('academic_qualification'),
                    'academic_degree' => $this->input->post('academic_degree'),
                    'academic_specialization' => $this->input->post('academic_specialization'),
                    'academic_institute' => $this->input->post('academic_institute'), 
                    'academic_from_year' => $this->input->post('academic_from_year'),
                    'academic_to_year' => $this->input->post('academic_to_year'),
                    'academic_marks_percentage' => $this->input->post('academic_marks_percentage')                    
                );                
                $res = $this->user_model->insert($postdata,'user_academics');
                if ($res) {
                    $this->app_lib->set_flash_message('Education has been added successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
		$this->data['page_title'] = "Add Educational Qualification";
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add_education', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function edit_education() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
		$education_id = $this->uri->segment(3);
		$this->data['arr_academic_qualification'] = $this->user_model->get_qualification_dropdown();
		$this->data['arr_academic_inst'] = $this->user_model->get_institute_dropdown();
        $this->data['arr_academic_specialization'] = $this->user_model->get_specialization_dropdown();
        $this->data['arr_academic_degree'] = $this->user_model->get_degree_dropdown();
        $this->data['education'] = $this->user_model->get_user_education($education_id, $this->sess_user_id);

        if ($this->input->post('form_action') == 'edit') {
            if ($this->validate_user_education_form_data('edit') == TRUE) {
                $postdata = array(                    
                    'academic_qualification' => $this->input->post('academic_qualification'),
                    'academic_degree' => $this->input->post('academic_degree'),
                    'academic_specialization' => $this->input->post('academic_specialization'),
                    'academic_institute' => $this->input->post('academic_institute'), 
                    'academic_from_year' => $this->input->post('academic_from_year'),
                    'academic_to_year' => $this->input->post('academic_to_year'),
                    'academic_marks_percentage' => $this->input->post('academic_marks_percentage')                    
                );
                $where = array('id'=>$education_id, 'user_id' => $this->sess_user_id);
                $res = $this->user_model->update($postdata, $where,'user_academics');
                if ($res) {
                    $this->app_lib->set_flash_message('Education has been updated successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
		$this->data['page_title'] = "Edit Educational Qualification";
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit_education', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function delete_education() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
		$id = $this->uri->segment(3);
		$where = array('id'=>$id, 'user_id' => $this->sess_user_id);
		$res = $this->user_model->delete($where,'user_academics');
		if ($res) {
			$this->app_lib->set_flash_message('Education details has been deleted successfully.', 'alert-success');
			redirect($this->router->directory.$this->router->class.'/profile');
		}else{
			$this->app_lib->set_flash_message('We\'re unable to process your request.', 'alert-danger');
			redirect($this->router->directory.$this->router->class.'/profile');
		}
    }
	
	function validate_user_education_form_data($mode) {	
        $max_year = (date('Y')+4);	
        $this->form_validation->set_rules('academic_qualification', 'qualification', 'required'); 
        $this->form_validation->set_rules('academic_degree', 'degree', 'required|greater_than_equal_to[0]',array('greater_than_equal_to' => 'The %s field is required.')); 
		$this->form_validation->set_rules('academic_from_year', 'from year', 'required|min_length[4]|max_length[4]|numeric|greater_than_equal_to[1970]|less_than_equal_to['.date('Y').']');        
        $this->form_validation->set_rules('academic_to_year', 'to year', 'required|min_length[4]|max_length[4]|numeric|greater_than_equal_to[1970]|less_than_equal_to['.$max_year.']'); 
        $this->form_validation->set_rules('academic_institute', 'academic institute', 'required|greater_than_equal_to[0]',array('greater_than_equal_to' => 'The %s field is required.'));
        $this->form_validation->set_rules('academic_specialization', 'specialization', 'required|greater_than_equal_to[0]',array('greater_than_equal_to' => 'The %s field is required.'));
        $this->form_validation->set_rules('academic_marks_percentage', 'marks in percentage', 'required|less_than_equal_to[100]|decimal');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	function edit_profile() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        
        $rows = $this->user_model->get_rows($this->sess_user_id);
        $this->data['row'] = $rows['data_rows'];

        if ($this->input->post('form_action') == 'update_profile') {
            if ($this->validate_edit_profile_form() == TRUE) {
                $postdata = array(
                    //'user_firstname' => $this->input->post('user_firstname'),
                    //'user_lastname' => $this->input->post('user_lastname'),
                    'user_bio' => $this->input->post('user_bio'),
                    //'user_gender' => $this->input->post('user_gender'),
                    'user_phone1' => $this->input->post('user_phone1'),
                    'user_phone2' => $this->input->post('user_phone2'),                  
                    'user_email_secondary' => $this->input->post('user_email_secondary'),                  
                    'user_blood_group' => $this->input->post('user_blood_group'),                  
                );
                $where = array('id' => $this->sess_user_id);
                $res = $this->user_model->update($postdata, $where);
                if ($res) {
                    $this->app_lib->set_flash_message('Basic information has been updated successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
	
		$this->data['page_title'] = 'Edit My Profile';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit_profile', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function profile_pic() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.$this->router->class.'/login');
        }
        
		$this->data['user_id'] = $this->sess_user_id;
		
		$res_pic = $this->user_model->get_user_profile_pic($this->sess_user_id);
		$this->data['profile_pic'] = $res_pic[0]['user_profile_pic'];
		
        if ($this->input->post('form_action') == 'file_upload') {
            $this->upload_file();
        }
	
		$this->data['page_title'] = 'Profile Photo';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/profile_pic', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function validate_uplaod_form_data() {
        //$this->form_validation->set_rules('userfile', 'file selection field', 'required');        
        //$this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        //if ($this->form_validation->run() == TRUE) {
            return TRUE;
        //} else {
            //return FALSE;
        //}
    }
	
	function upload_file() {
        if ($this->validate_uplaod_form_data() == TRUE) {
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
            $upload_result = $this->app_lib->upload_file('userfile', $upload_param);
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
                        $this->app_lib->unlink_file(array(FCPATH . $file_path));
                    }
                    // Now update table
                    //$update_upload = $this->user_model->update($postdata, array('id' => $uploads[0]['id']), 'uploads');
                    $update_upload = $this->user_model->update($postdata, $where_array);
                    $this->app_lib->set_flash_message('Profile photo uploaded successfully.', 'alert-success');
                    redirect(current_url());
                } else {
                    //$upload_insert_id = $this->user_model->insert($postdata, 'uploads');
                    $update_upload = $this->user_model->update($postdata, $where_array);
                    $this->app_lib->set_flash_message('Profile photo uploaded successfully.', 'alert-success');
                    redirect(current_url());
                }
            } else if (sizeof($upload_result['upload_error']) > 0) {
                $error_message = $upload_result['upload_error'];
                $this->app_lib->set_flash_message($error_message, 'alert-danger');
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
				$this->app_lib->unlink_file(array(FCPATH . $file_path));
				//$res = $this->user_model->delete(array('id'=>$uploaded_file_id),'uploads');
				$postdata = array(                    
                    'user_profile_pic' => NULL
                );
				$where_array = array('id'=>$this->sess_user_id);
				$res = $this->user_model->update($postdata, $where_array);
				if($res){
					$this->app_lib->set_flash_message('Profile photo has been deleted successfully.', 'alert-success');
					redirect($this->router->directory.$this->router->class.'/profile_pic');
				}else{
					$this->app_lib->set_flash_message('Error occured while processing your request.', 'alert-danger');
					redirect($this->router->directory.$this->router->class.'/profile_pic');
				}
			}
		//}
	}	
	
	function allocate_projects() {
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.$this->router->class.'/login');
        }
		$this->load->model('project_model');
		$this->data['arr_projects'] = $this->project_model->get_project_dropdown();
        if ($this->input->post('form_action') == 'add') {
            if ($this->validate_user_project_assign_form_data('add') == TRUE) {
                $postdata = array(					
                    'academic_qualification' => $this->input->post('academic_qualification')                 
                );                
                $res = $this->user_model->insert($postdata,'user_projects');
                if ($res) {
                    $this->app_lib->set_flash_message('Project has been added successfully.', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/profile');
                }
            }
        }
		$this->data['page_title'] = "Allocate Projects";
        $this->data['maincontent'] = $this->load->view($this->router->class.'/allocate_projects', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }
	
	function validate_user_project_assign_form_data(){
		$this->form_validation->set_rules('project_id', 'project', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
	}	
    
    function add_user_input_specialization(){
        $message = array('is_valid'=>FALSE, 'insert_id'=>'','msg'=>'');
        if(($this->input->post('action')=='add')){
            if ($this->validate_add_user_input_specialization_data() == TRUE) {
                $postdata = array(					
                    'specialization_name' => ucwords(strtolower($this->input->post('specialization_name')))
                );                
                $insert_id = $this->user_model->insert($postdata,'academic_specialization');
                if ($insert_id) {
                    $message = array('is_valid'=>TRUE, 'insert_id'=>$insert_id,'msg'=>'<div class="alert alert-success">Specialization has been added succesfully.</div>'); 
                }
            }else{
                $message = array('is_valid'=>FALSE, 'insert_id'=>'','msg'=>validation_errors()); 
            }
        }
        echo json_encode($message); die();
    }

    function validate_add_user_input_specialization_data(){        
		$this->form_validation->set_rules('specialization_name', 'specialization name', 'required|alpha_numeric_spaces|min_length[5]|max_length[100]|is_unique[academic_specialization.specialization_name]',
        array(                
                'is_unique'     => 'This %s already exists.'
        ));
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_user_input_degree(){
        $message = array('is_valid'=>FALSE, 'insert_id'=>'','msg'=>'');
        if(($this->input->post('action')=='add')){
            if ($this->validate_add_user_input_degree_data() == TRUE) {
                $postdata = array(					
                    'degree_name' => ucwords(strtolower($this->input->post('degree_name')))
                );                
                $insert_id = $this->user_model->insert($postdata,'academic_degree');
                if ($insert_id) {
                    $message = array('is_valid'=>TRUE, 'insert_id'=>$insert_id,'msg'=>'<div class="alert alert-success">degree has been added succesfully.</div>'); 
                }
            }else{
                $message = array('is_valid'=>FALSE, 'insert_id'=>'','msg'=>validation_errors()); 
            }
        }
        echo json_encode($message); die();
    }

    function validate_add_user_input_degree_data(){        
		$this->form_validation->set_rules('degree_name', 'degree name', 'required|alpha_numeric_spaces|min_length[5]|max_length[50]|is_unique[academic_degree.degree_name]',
        array(                
                'is_unique'     => 'This %s already exists.'
        ));
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_user_input_institute(){
        $message = array('is_valid'=>FALSE, 'insert_id'=>'','msg'=>'');
        if(($this->input->post('action')=='add')){
            if ($this->validate_add_user_input_institute_data() == TRUE) {
                $postdata = array(					
                    'institute_name' => ucwords(strtolower($this->input->post('institute_name')))
                );                
                $insert_id = $this->user_model->insert($postdata,'academic_institute');
                if ($insert_id) {
                    $message = array('is_valid'=>TRUE, 'insert_id'=>$insert_id,'msg'=>'<div class="alert alert-success">institute has been added succesfully.</div>'); 
                }
            }else{
                $message = array('is_valid'=>FALSE, 'insert_id'=>'','msg'=>validation_errors()); 
            }
        }
        echo json_encode($message); die();
    }

    function validate_add_user_input_institute_data(){
		$this->form_validation->set_rules('institute_name', 'institute name', 'required|alpha_numeric_spaces|min_length[5]|max_length[200]|is_unique[academic_institute.institute_name]',
        array(                
                'is_unique'     => 'This %s already exists.'
        ));
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
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
                'bold' => TRUE
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