<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Document extends CI_Controller {

    var $data;
    var $id;

    function __construct() {
        parent::__construct();

        //Check if any user logged in else redirect to login
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            if($this->data['is_admin'] === TRUE){
                redirect($this->router->directory.'admin/login');
            }else{
                redirect($this->router->directory.'user/login');
            }
        }

        // Get logged  in user id
        $this->sess_user_id = $this->app_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->app_lib->init_template_elements('site');

        // Load required js files for this controller
        $javascript_files = array(
            $this->router->class
        );
        $this->data['app_js'] = $this->app_lib->add_javascript($javascript_files);

        $this->load->model('upload_model');
        $this->data['id'] = $this->uri->segment(3) ? $this->uri->segment(3) : $this->sess_user_id;
        $this->data['page_title'] = $this->router->class.' : '.$this->router->method;
        $this->data['arr_upload_file_type_name'] = array(
            "" => "Select",
            "12_certificate" => "12th Certificate (Single)",
            "work_exp_letter" => "Work Experience Letter (Multiple)",
        );
        ksort($this->data['arr_upload_file_type_name']);
    }

    function index() {
        //Uploads
        $upload_related_to = 'user';
        $this->data['upload_related_to'] = $upload_related_to;
		
		$cond = array(
			'upload_related_to' => $upload_related_to,
			'upload_related_to_id' => $this->data['id'],
			'upload_file_type_name' => NULL
		);
        $upload_data = $this->upload_model->get_uploads(NULL, NULL, NULL, FALSE, FALSE, $cond);		 
		$this->data['all_uploads'] = $upload_data['data_rows'];
        if ($this->input->post('form_action') == 'file_upload') {
            $this->upload_file();
        }
		$this->data['page_title'] = 'My Documents';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index', $this->data, TRUE);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function upload_file() {
        if ($this->validate_uplaod_form_data() == TRUE) {
            $upload_related_to = 'user'; // related to user, product, album, contents etc
            $upload_related_to_id = $this->sess_user_id; // related to id user id, product id, album id etc
            $upload_file_type_name = $this->input->post('upload_file_type_name'); // file type name			
            
            //Create directory for object specific
            $upload_path = 'assets/uploads/' . $upload_related_to . '/docs/' . $upload_related_to_id;
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }
            $allowed_ext = 'png|jpg|jpeg|doc|docx|pdf';
            $upload_param = array(
                'upload_path' => $upload_path, // original upload folder
                'allowed_types' => $allowed_ext, // allowed file types,
                'max_size' => '2048', // max 2MB size,
                'file_new_name' => $upload_related_to_id . '_' . $upload_file_type_name . '_' . time(),
            );
            $upload_result = $this->app_lib->upload_file('userfile', $upload_param);
            if (isset($upload_result['file_name']) && empty($upload_result['upload_error'])) {
                $uploaded_file_name = $upload_result['file_name'];
                $postdata = array(
                    'upload_related_to' => $upload_related_to,
                    'upload_related_to_id' => $upload_related_to_id,
                    'upload_file_type_name' => $upload_file_type_name,
                    'upload_file_name' => $uploaded_file_name,
                    'upload_mime_type' => $upload_result['file_type'],
                    'upload_by_user_id' => $this->sess_user_id,
                    'upload_datetime' => date('Y-m-d H:i:s')
                );

                // Allow mutiple file upload for a file type.
                $multiple_allowed_upload_file_type = array('work_exp_letter');

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
                    $update_upload = $this->upload_model->update($postdata, array('id' => $uploads[0]['id']), 'uploads');
                    $this->app_lib->set_flash_message('File has been uploaded successfully.', 'alert-success');
                    redirect(current_url());
                } else {
                    $upload_insert_id = $this->upload_model->insert($postdata, 'uploads');
                    $this->app_lib->set_flash_message('File has been uploaded successfully.', 'alert-success');
                    redirect(current_url());
                }
            } else if (sizeof($upload_result['upload_error']) > 0) {
                $error_message = $upload_result['upload_error'];
                $this->app_lib->set_flash_message($error_message, 'alert-danger');
                redirect(current_url());
            }
        }
    }

    function validate_uplaod_form_data() {
        $this->form_validation->set_rules('upload_file_type_name', ' ', 'required');
        if (empty($_FILES['userfile']['name'])){
			$this->form_validation->set_rules('userfile', ' ', 'required');
		}
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delete_file() {
        //Check if any user logged in else redirect to login
        $is_logged_in = $this->app_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            if($this->data['is_admin'] === TRUE){
                redirect($this->router->directory.'admin/login');
            }else{
                redirect($this->router->directory.'user/login');
            }
        }

        $id = $this->input->get_post('id');
        $file_path = $this->input->get_post('file_path');
        if ($id) {
            $where_array = array('id' => $id);
            $res = $this->user_model->delete($where_array, 'uploads');
            if ($res) {
                $this->app_lib->unlink_file(array(FCPATH . $file_path));
            }
            echo json_encode("success");
        } else {
            echo json_encode("error");
        }
    }
    
    function delete_uploads($upload_related_to, $upload_related_to_id) {
        $where_array = array('upload_related_to' => $upload_related_to, 'upload_related_to_id' => $upload_related_to_id);
        $res = $this->upload_model->delete($where_array, 'uploads');
        if ($res) {
            $upload_path = 'assets/uploads/'.$upload_related_to.'/' . $upload_related_to_id;
            $this->app_lib->recursive_remove_directory(FCPATH . $upload_path);
        }
    }
}

?>
