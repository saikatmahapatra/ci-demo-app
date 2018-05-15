<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

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
        
        $this->load->model('user_model');
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;

        /* DOB */
        $this->data['day_arr'] = $this->calander_days();
        $this->data['month_arr'] = $this->calander_months();
        $this->data['year_arr'] = $this->calander_years();
		
		/*Address Type*/
		$this->data['address_type'] = array('S'=>'Shipping','B'=>'Billing','W'=>'Work','H'=>'Home','C'=>'Preseent','P'=>'Permanent');
		
		//View Page Config
		$this->data['page_heading'] = $this->router->class.' : '.$this->router->method;
    }

    function index() {
        $this->login();
    }

    function login() {
        //echo $this->session->userdata('sess_post_login_redirect_uri');
		$this->data['page_heading'] = "Please login to continue";
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
                        $post_login_redirect_uri = $this->session->userdata('sess_post_login_redirect_uri');
                        if(isset($post_login_redirect_uri) && $post_login_redirect_uri!=''){
                            $this->session->unset_userdata('sess_post_login_redirect_uri');
                            redirect($post_login_redirect_uri);
                        }else{
                            redirect('user/profile');
                        }
                        
                    }
                }
            }
        }

        $this->data['maincontent'] = $this->load->view('site/user/login', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function validate_login_form_data() {
        $this->form_validation->set_rules('user_email', ' ', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_password', ' ', 'required|min_length[5]|max_length[15]');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function create_account() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'create_account') {
            if ($this->validate_register_form_data() == true) {
                $activation_token = md5(time('Y-m-d h:i:s'));
                $dob = $this->input->post('dob_year') . '-' . $this->input->post('dob_month') . '-' . $this->input->post('dob_day');
                $postdata = array(
                    'user_firstname' => $this->input->post('user_firstname'),
                    //'user_midname' => $this->input->post('user_midname'),
                    'user_lastname' => $this->input->post('user_lastname'),
                    'user_gender' => $this->input->post('user_gender'),
                    'user_email' => $this->input->post('user_email'),
                    'user_dob' => $dob,
                    'user_role' => '2', // as per role table 2 (user)
                    'user_mobile_phone1' => $this->input->post('user_mobile_phone1'),
                    'user_password' => md5($this->input->post('user_password')),
                    'user_registration_date' => date('Y-m-d H:i:s'),
                    'user_activation_key' => $activation_token,
                    'user_registration_ip' => $_SERVER['REMOTE_ADDR'],
                        //'user_account_active' => 'Y'
                );
                $insert_id = $this->user_model->insert($postdata);
                if ($insert_id) {
                    $html = '<div style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">';
                    $html.='<p>Hi ' . ucwords(strtolower($this->input->post('user_firstname'))) . ',</p>';
                    $html.='<p> Thank you for registering. Your account has been created succesfully.<br/>';
                    #$html.='<br/> activate your account to login.<br><br>';
                    #$html.='Activatation Link : <a href="'.base_url('users/activate_account/'.strtolower(base64_encode($insert_id)).'/'.$activation_token).'" target="_blank">'.base_url('users/activate_account/'.strtolower(base64_encode($insert_id)).'/'.$activation_token).'</a></p><br>';
                    $html.='<p>Please note the access of the website : <br/><br/> Login URL: <a href="' . base_url() . '" target="_blank">' . base_url() . '</a><br>';
                    $html.='Email: ' . $this->input->post('user_email') . '<br/>';
                    $html.='Password: ' . $this->input->post('user_password') . '</p>';
                    $html.='<br>';
                    $html.='</div>';
                    //echo $html;
                    //die();
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->to($this->input->post('user_email'));
                    $this->email->from($this->config->item('app_admin_email'), $this->config->item('app_admin_email_name'));
                    $this->email->subject($this->config->item('app_email_subject_prefix') . 'Your Account Details');
                    $this->email->message($html);
                    $this->email->send();
                    //echo $this->email->print_debugger();
                    $this->session->set_flashdata('flash_message', '<i class="icon fa fa-check" aria-hidden="true"></i> Registration Successful.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_heading'] = "Create your account";
        $this->data['maincontent'] = $this->load->view('site/user/create_account', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function validate_register_form_data() {
        $this->form_validation->set_rules('user_firstname', 'first name', 'required');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required');
        $this->form_validation->set_rules('user_gender', 'gender selection', 'required');
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email|callback_is_email_registered');
        $this->form_validation->set_rules('user_password', 'password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('user_mobile_phone1', 'mobile number', 'required|trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_password_confirm', 'confirm password', 'required|matches[user_password]');
        $this->form_validation->set_rules('dob_day', 'birth day selection', 'required');
        $this->form_validation->set_rules('dob_month', 'birth month selection', 'required');
        $this->form_validation->set_rules('dob_year', 'birth year selection', 'required');
        $this->form_validation->set_rules('terms', 'terms & conditions acceptance', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
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

    function activate_account() {
        $res = $this->user_model->check_user_activation_key($user_id, $activation_key);
        if ($res) {
            $postdata = array('user_account_active' => 'Y');
            $where = array('id' => $user_id, 'user_activation_key' => $activation_key);
            $act_res = $this->user_model->update($postdata, $where);
            if ($act_res) {
                $this->session->set_flashdata('flash_message', 'Your account has been activated successfully');
                redirect('users/login');
            } else {
                $this->session->set_flashdata('flash_message', 'Sorry ! Unable to activate your account');
                redirect('users/login');
            }
        } else {
            $this->session->set_flashdata('flash_message', 'No activation token match found for you');
            redirect('users/login');
        }
    }

    function forgot_password() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');

        if ($this->input->post('form_action') == 'reset_password') {
            if ($this->validate_forgot_password_form() == true) {
                $email = $this->input->post('user_email');
                $password_reset_key = $this->generate_password();

                $postdata = array('user_reset_password_key' => md5($password_reset_key));
                $where = array('user_email' => $email);

                $result = $this->user_model->update($postdata, $where);
                if ($result) {
                    $to_email = $email;
                    $html = '<div style="margin:10px 0;padding:0;width:580px;float:left;border:1px solid #ccc;padding:9px">';
                    $html.='<div style="font:14px Arial,Helvetica,sans-serif;margin-bottom:5px;color:#222222;padding:10px 0 10px 0">Dear User,</div>';
                    $html.='<div style="font:14px Arial,Helvetica,sans-serif;margin-bottom:5px;color:#222222;padding:0px 0 10px 0">';
                    $html.='<p>Please click on the password reset link to create a new login password.</p>';
                    $html.='<p><strong>Password Reset Link:</strong><br />';
                    $html.= anchor(site_url('user/reset_password/' . md5($password_reset_key)), NULL);
                    $html.='</p>';
                    $html.='</div>';
                    $html.='</div>';

                    // load email lib and email results
                    die($html);
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->to($email);
                    $this->email->from($this->config->item('app_admin_email'), $this->config->item('app_admin_email_name'));
                    $this->email->subject($this->config->item('app_email_subject_prefix') . ' Password Reset Link');
                    $this->email->message($html);
                    $this->email->send();
                    //echo $this->email->print_debugger();

                    $this->session->set_flashdata('flash_message', '<i class="icon fa fa-check" aria-hidden="true"></i> Password reset link has been sent to ' . $email);
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                } else {
                    $this->session->set_flashdata('flash_message', '<i class="icon fa fa-warning" aria-hidden="true"></i> Unable to send reset password link');
                    $this->session->set_flashdata('flash_message_css', 'alert-danger');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_heading'] = "Forgot login password?";
        $this->data['maincontent'] = $this->load->view('site/user/forgot_password', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function validate_forgot_password_form() {
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email|callback_is_email_valid');
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
        $this->data['password_reset_key'] = $this->uri->segment('3');

        if (!isset($this->data['password_reset_key'])) {
            $this->data['alert_message'] = '<strong>Error! </strong> No password reset link found.';
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

                        $this->session->set_flashdata('flash_message', '<strong>Great! </strong> New password saved successfully.');
                        $this->session->set_flashdata('flash_message_css', 'alert-success');
                        redirect(current_url());
                    }
                } else {
                    $this->session->set_flashdata('flash_message', '<strong>Sorry! </strong> Invalid email or password reset link.');
                    $this->session->set_flashdata('flash_message_css', 'alert-danger');
                    redirect(current_url());
                }
            }
        }
		$this->data['page_heading'] = "Create new password";
        $this->data['maincontent'] = $this->load->view('site/user/reset_password', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
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
            if ($result == false) {
                $this->form_validation->set_message('is_email_valid', $user_email . ' is not a registered email address.');
                return false;
            }
            
        }else{
            return true;
        }
    }

    function change_password() {
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('user/login');
        }
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'change_password') {
            if ($this->validate_changepassword_form() == true) {
                $postdata = array('user_password' => md5($this->input->post('user_new_password')));
                $where = array('id' => $this->sess_user_id);
                $this->user_model->update($postdata, $where);
                $this->session->set_flashdata('flash_message', '<strong>Success! </strong>Password Changed Successfully');
                $this->session->set_flashdata('flash_message_css', 'alert-success');
                redirect(current_url());
            }
        }
		$this->data['page_heading'] = "Change Password";
        $this->data['maincontent'] = $this->load->view('site/user/change_password', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
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
            $this->session->unset_userdata('post_login_redirect_uri');
            $this->session->set_flashdata('flash_message', 'You have been logged out successfully.');
            $this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect('user/login');
        } else {
            redirect('user/login');
        }
    }

    function profile() {
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('user/login');
        }
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $rows = $this->user_model->get_rows($this->sess_user_id);		
        $this->data['row'] = $rows['data_rows'];
		$this->data['address'] = $this->user_model->get_user_address(NULL,$this->sess_user_id,NULL);
		$this->data['page_heading'] = "Profile";
        $this->data['maincontent'] = $this->load->view('site/user/profile', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function edit_profile() {
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('user/login');
        }
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $rows = $this->user_model->get_rows($this->sess_user_id);
        $this->data['row'] = $rows['data_rows'];

        if ($this->input->post('form_action') == 'update_profile') {
            if ($this->validate_edit_profile_form() == true) {
                $postdata = array(
                    'user_firstname' => $this->input->post('user_firstname'),
                    'user_lastname' => $this->input->post('user_lastname'),
                    'user_intro' => $this->input->post('user_intro'),
                    'user_gender' => $this->input->post('user_gender'),                   
                    //'user_dob' => $dob,
                    'user_mobile_phone1' => $this->input->post('user_mobile_phone1'),
                    'user_mobile_phone2' => $this->input->post('user_mobile_phone2')                    
                );
                $where = array('id' => $this->sess_user_id);
                $res = $this->user_model->update($postdata, $where);
                if ($res) {
                    $this->session->set_flashdata('flash_message', 'Your basic info has been updated successfully');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect('user/profile');
                }
            }
        }
		$this->data['page_heading'] = "Edit Profile";
        $this->data['maincontent'] = $this->load->view('site/user/edit_profile', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }
	
	
	function validate_user_address_form_data($mode) {
		if($mode == 'add'){
			$this->form_validation->set_rules('address_type', 'address type selection', 'required');        
		}
        $this->form_validation->set_rules('name', ' ', 'required');        
        $this->form_validation->set_rules('phone1', ' ', 'required|trim|min_length[10]|max_length[10]|numeric');        
        $this->form_validation->set_rules('zip', ' ', 'required');        
        $this->form_validation->set_rules('locality', ' ', 'required');        
        $this->form_validation->set_rules('address', ' ', 'required|max_length[200]');               
        $this->form_validation->set_rules('city', ' ', 'required');        
        $this->form_validation->set_rules('state', ' ', 'required');        
        //$this->form_validation->set_rules('country', ' ', 'required');        
        $this->form_validation->set_rules('landmark', ' ', 'max_length[100]');        
        $this->form_validation->set_rules('phone2', ' ', 'min_length[10]|max_length[10]|numeric');        
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
	
	function add_address() {
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('user/login');
        }
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');		
        if ($this->input->post('form_action') == 'insert_address') {
            if ($this->validate_user_address_form_data('add') == true) {
                $postdata = array(
					'user_id' => $this->sess_user_id,
                    'address_type' => $this->input->post('address_type'),
                    'name' => $this->input->post('name'),
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
                    $this->session->set_flashdata('flash_message', 'Your address has been added successfully');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect('user/profile');
                }
            }
        }
		$this->data['page_heading'] = "Add Address";
        $this->data['maincontent'] = $this->load->view('site/user/add_address', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }
	
	function edit_address() {
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('user/login');
        }
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		$address_id = $this->uri->segment(3);        
        $this->data['address'] = $this->user_model->get_user_address($address_id, $this->sess_user_id,NULL);

        if ($this->input->post('form_action') == 'update_address') {
            if ($this->validate_user_address_form_data('edit') == true) {
                $postdata = array(
					//'user_id' => $this->sess_user_id,
                    //'address_type' => $this->input->post('address_type'),
                    'name' => $this->input->post('name'),
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
                    $this->session->set_flashdata('flash_message', 'Address has been updated successfully');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect('user/profile');
                }
            }
        }
		$this->data['page_heading'] = "Edit Address";
        $this->data['maincontent'] = $this->load->view('site/user/edit_address', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }
	
	function delete_address() {
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('user/login');
        }
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		$address_id = $this->uri->segment(3);        
        $this->data['address'] = $this->user_model->get_user_address($address_id, $this->sess_user_id,NULL);
		$where = array('id'=>$address_id, 'user_id' => $this->sess_user_id);
		$res = $this->user_model->delete($where,'user_addresses');
		if ($res) {
			$this->session->set_flashdata('flash_message', 'Your address has been deleted successfully.');
			$this->session->set_flashdata('flash_message_css', 'alert-success');
			redirect('user/profile');
		}else{
			$this->session->set_flashdata('flash_message', 'We\'re unable to process your request.');
			$this->session->set_flashdata('flash_message_css', 'alert-danger');
			redirect('user/profile');
		}
    }
	
	function get_address_types($char_address_type){
		if(isset($char_address_type)){
			return $this->data['address_type'][$char_address_type];
		}else{
			return '';
		}		
	}

    function validate_edit_profile_form() {
        $this->form_validation->set_rules('user_firstname', 'first name', 'required');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required');
        $this->form_validation->set_rules('user_gender', 'gender selection', 'required');        
        $this->form_validation->set_rules('user_mobile_phone1', 'primary mobile', 'required|trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_mobile_phone2', 'secondary mobile', 'trim|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('user_intro', 'short introduction', 'max_length[100]');
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

    function valid_url_format($str) {
        $pattern_1 = "/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
        $pattern_2 = "/^(www)((\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
        if ((!preg_match($pattern_1, $str)) || (!preg_match($pattern_2, $str))) {
            $this->form_validation->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }
        return TRUE;
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
        $current_year = date('Y');
        $start_year = ($current_year - 105);
        for ($i = $current_year; $i > $start_year; $i--) {
            $result[$i] = $i;
        }
        return $result;
    }

}

?>
