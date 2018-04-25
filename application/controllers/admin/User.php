<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        
        $this->load->model('user_model');
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
        
//        //Check if any user logged in else redirect to login
//        $is_logged_in = $this->common_lib->is_logged_in();
//        if ($is_logged_in == FALSE) {
//            redirect('admin/user/login');
//        }
//
//        //Has logged in user permission to access this page or method?        
//        $this->common_lib->check_user_role_permission(array(
//            'default-super-admin-access',
//            'default-admin-access'
//        ));

        // Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements('admin');

        //add required js files for this controller
        $app_js_src = array(
            'assets/dist/js/user.js',
        );
        $this->data['app_js'] = $this->common_lib->add_javascript($app_js_src);

        //View Page Config
        $this->data['page_heading'] = "Users";
        $this->data['datatable']['dt_id']= array('heading'=>'Data Table','cols'=>array());
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Dashboard', '/admin');
		$this->breadcrumbs->push('User', '/admin/user/manage');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
		/*Address Type*/
		$this->data['address_type'] = array('S'=>'Shipping','B'=>'Billing','W'=>'Work','H'=>'Home','C'=>'Preseent','P'=>'Permanent');
    }

    function index() {
        $this->login();
    }

    function manage() {        
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('admin/user/login');
        }
        //Has logged in user permission to access this page or method?        
        $this->common_lib->check_user_role_permission(array(
            'default-super-admin-access',
            'default-admin-access',
        ));        
		$this->breadcrumbs->push('View', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $this->data['maincontent'] = $this->load->view('admin/user/manage', $this->data, true);
        $this->load->view('admin/_layouts/layout_authenticated', $this->data);
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
            $row[] = $result['user_firstname'] . ' ' . $result['user_lastname'];
            $row[] = $result['user_email'];
            $row[] = $result['user_mobile_phone1'];
            $row[] = $result['role_name'];
            $row[] = ($result['user_account_active'] == 'Y') ? 'Active' : 'Inactive';
            //add html for action
            $action_html = '';


            $acc_status_text = ($result['user_account_active'] == 'Y') ? 'Deactivate' : 'Activate';
            $acc_status_class = ($result['user_account_active'] == 'Y') ? 'btn-outline-warning' : 'btn-info';
            $acc_status_set = ($result['user_account_active'] == 'Y') ? 'N' : 'Y';
            $action_html.= anchor(site_url('admin/user/profile/' . $result['id']), 'Profile', array(
                'class' => 'btn btn-sm btn-outline-dark',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Profile',
                'title' => 'Profile',
            ));
			$action_html.='&nbsp;';
			if($result['role_weight'] <= 90){
				$action_html.= anchor(site_url('admin/user/manage#'), $acc_status_text, array(
					'class' => 'change_account_status btn btn-xs ' . $acc_status_class,
					'data-toggle' => 'tooltip',
					'data-original-title' => $acc_status_text,
					'title' => $acc_status_text,
					'data-status' => $acc_status_set,
					'data-id' => $result['id'],
				));
			}
            /* $action_html.='&nbsp;';
              $action_html.= anchor(site_url('admin/user/delete/' . $result['id']), 'Delete', array(
              'class' => 'btn btn-sm btn-outline-danger btn-delete',
			  'data-confirmation'=>true,
			  'data-confirmation-message'=>'Are you sure, you want to delete this?',
              'data-toggle' => 'tooltip',
              'data-original-title' => 'Delete',
              'title' => 'Delete',
              )); */

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
            redirect('admin/home');
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
                        redirect('admin/home');
                    }
                }
            }
        }
        $this->data['maincontent'] = $this->load->view('admin/user/login', $this->data, true);
        $this->load->view('admin/_layouts/layout_unauthenticated', $this->data);
    }

    function home() {
        $this->profile();
    }

    function auth_error() {
        $this->data['maincontent'] = $this->load->view('admin/user/auth_error', $this->data, true);
        $this->load->view('admin/_layouts/layout_unauthenticated', $this->data);
    }

    function validate_login_form_data() {
        $this->form_validation->set_rules('user_email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_password', 'password', 'required');
        $this->form_validation->set_error_delimiters('<p class="validation-error">', '</p>');
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
                redirect('users/admin/login');
            } else {
                $this->session->set_flashdata('flash_message', 'Sorry ! Unable to activate your account');
                redirect('users/admin/login');
            }
        } else {
            $this->session->set_flashdata('flash_message', 'No activation token match found for you');
            redirect('users/admin/login');
        }
    }

    function forgot_password() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');

        if ($this->input->post('form_action') == 'forgot_password') {
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
                    $html.= anchor(site_url('admin/user/reset_password/' . md5($password_reset_key)), NULL);
                    $html.='</p>';
                    $html.='</div>';
                    $html.='</div>';

                    // load email lib and email results
                    //die($html);
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
        $this->data['maincontent'] = $this->load->view('admin/user/forgot_password', $this->data, true);
        $this->load->view('admin/_layouts/layout_unauthenticated', $this->data);
    }

    function validate_forgot_password_form() {		
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email|callback_is_email_valid');
        $this->form_validation->set_error_delimiters('<p class="validation-error">', '</p>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function reset_password() {
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $this->data['password_reset_key'] = $this->uri->segment('4');

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
        $this->data['maincontent'] = $this->load->view('admin/user/reset_password', $this->data, true);
        $this->load->view('admin/_layouts/layout_unauthenticated', $this->data);
    }

    function validate_reset_password_form() {
        $this->form_validation->set_rules('user_email', 'email address', 'trim|required|valid_email');
        $this->form_validation->set_rules('user_new_password', 'new password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_user_new_password', 'confirm password', 'required|matches[user_new_password]');

        $this->form_validation->set_error_delimiters('<p class="validation-error">', '</p>');
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
        $result = $this->user_model->check_is_email_registered($user_email);
        if ($result == false) {
            $this->form_validation->set_message('is_email_valid', $user_email . ' is not registered.');
            return false;
        }
        return true;
    }

    function change_password() {

        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('admin/user/login');
        }
        //Has logged in user permission to access this page or method?        
        $this->common_lib->check_user_role_permission(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        ########### Validate User Auth End #############

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
        $this->data['maincontent'] = $this->load->view('admin/user/change_password', $this->data, true);
        $this->load->view('admin/_layouts/layout_authenticated', $this->data);
    }

    function validate_changepassword_form() {
        $this->form_validation->set_rules('user_current_password', 'current password', 'required|callback_check_current_password');
        $this->form_validation->set_rules('user_new_password', 'new password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_user_new_password', 'confirm password', 'required|matches[user_new_password]');
        $this->form_validation->set_error_delimiters('<p class="validation-error">', '</p>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function check_current_password($password) {
        $result = $this->user_model->check_user_password_valid(md5($password), $this->sess_user_id);
        if ($result == false) {
            $this->form_validation->set_message('check_current_password', 'current password is invalid');
            return false;
        }
        return true;
    }

    function logout() {
        if (isset($this->session->userdata['sess_user'])) {
            $this->session->unset_userdata('sess_user');
            $this->session->set_flashdata('flash_message', '<i class="icon fa fa-check" aria-hidden="true"></i> You have been logged out successfully.');
            $this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect('admin/user/login');
        } else {
            redirect('admin/home');
        }
    }

    function profile() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('admin/user/login');
        }
        //Has logged in user permission to access this page or method?        
        $this->common_lib->check_user_role_permission(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        ########### Validate User Auth End #############
		
		//View Page Config
        $this->data['page_heading'] = "Profile";
        $this->breadcrumbs->push('Profile','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();

        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		$user_id = $this->uri->segment(4);
		//die($user_id);
		//$this->sess_user_id;
        $rows = $this->user_model->get_rows($user_id);
        $this->data['row'] = $rows['data_rows'];
		$this->data['address'] = $this->user_model->get_user_address(NULL,$user_id,NULL);		
        $this->data['maincontent'] = $this->load->view('admin/user/profile', $this->data, true);
        $this->load->view('admin/_layouts/layout_authenticated', $this->data);
    }

    function edit_profile() {
        ########### Validate User Auth #############
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect('admin/user/login');
        }
        //Has logged in user permission to access this page or method?        
        $this->common_lib->check_user_role_permission(array(
            'default-super-admin-access',
            'default-admin-access',
        ));
        ########### Validate User Auth End #############
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $this->data['row'] = $this->user_model->get_user_details($this->sess_user_id);

        if ($this->input->post('form_action') == 'update_profile') {
            if ($this->validate_edit_profile_form() == true) {
                $postdata = array(
                    'user_firstname' => $this->input->post('user_firstname'),
                    'user_lastname' => $this->input->post('user_lastname'),
                    'user_phone1' => $this->input->post('user_phone'),
                    'user_zipcode' => $this->input->post('user_zipcode'),
                    'user_gender' => $this->input->post('user_gender')
                );
                $where = array('id' => $this->sess_user_id);
                $res = $this->user_model->update($postdata, $where);
                if ($res) {
                    $this->session->set_flashdata('flash_message', '<strong>Success! </strong>Information updated successfully');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                }
            }
        }

        $this->data['maincontent'] = $this->load->view('admin/user/profile', $this->data, true);
        $this->load->view('admin/_layouts/layout_authenticated', $this->data);
    }

    function validate_edit_profile_form() {
        $this->form_validation->set_rules('user_firstname', 'first name', 'required');
        $this->form_validation->set_rules('user_lastname', 'last name', 'required');
        //$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_is_email_exists');
        $this->form_validation->set_error_delimiters('<p class="validation-error">', '</p>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function change_account_status() {
        $response = array(
            'status' => 'no',
            'alert_message' => 'No message',
            'alert_message_css' => 'alert alert-info',
            'data' => array(),
        );
        $is_active = $this->input->post('active');
        $postdata = array('user_account_active' => $is_active);
        $where = array('id' => $this->input->post('user_id'));
        $res = $this->user_model->update($postdata, $where);
        if ($res == true) {
            $response = array(
                'status' => 'success',
                'alert_message' => 'Account Status Changed',
                'alert_message_css' => 'alert alert-success',
                'data' => array(),
            );
        } else {
            $response = array(
                'status' => 'error',
                'alert_message' => 'Error Occured',
                'alert_message_css' => 'alert alert-danger',
                'data' => array(),
            );
        }
        echo json_encode($response);
    }

}

?>
