<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms extends CI_Controller {

    var $data;
    var $id;
    var $sess_user_id;

    function __construct() {
        parent::__construct();

        //Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }

        //Has logged in user permission to access this page or method?        
        $is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access'
        ));

        // Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements();

        //add required js files for this controller
        $app_js_src = array(
            'assets/dist/js/'.$this->router->class.'.js', //create js file name same as controller name
        );
        $this->data['app_js'] = $this->common_lib->add_javascript($app_js_src);

        
        $this->load->model('cms_model');
		$this->load->model('upload_model');
        $this->id = $this->uri->segment(3);

        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
        $this->data['page_heading'] = $this->router->class.' : '.$this->router->method;
        
		$this->data['arr_content_type'] = $this->cms_model->get_pagecontent_type();
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Home', '/');
		$this->breadcrumbs->push('CMS', '/cms');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
		//Pagination
		 $this->load->library('pagination');
		
    }

    function index() {
        // Check user permission by permission name mapped to db
        // $is_authorized = $this->common_lib->is_auth('cms-list-view');
		
		// Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');
			
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->data['page_heading'] = 'Website CMS - Contents';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }
	
	function index_ci_pagination() {
        // Check user permission by permission name mapped to db
        // $is_authorized = $this->common_lib->is_auth('cms-list-view');
			
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');

        // Display using CI Pagination: Total filtered rows - check without limit query. Refer to model method definition		
		$result_array = $this->cms_model->get_rows(NULL, NULL, NULL, FALSE, FALSE);
		$total_num_rows = $result_array['num_rows'];
		
		//Pagination config starts here		
        $per_page = 3;
        $config['uri_segment'] = 4; //which segment of your URI contains the page number
        $config['num_links'] = 2;
        $page = ($this->uri->segment($config['uri_segment'])) ? ($this->uri->segment($config['uri_segment'])-1) : 0;
        $offset = ($page*$per_page);
        $this->data['pagination_link'] = $this->common_lib->render_pagination($total_num_rows, $per_page);
        //Pagination config ends here
        

        // Data Rows - Refer to model method definition
        $result_array = $this->cms_model->get_rows(NULL, $per_page, $offset, FALSE, TRUE);
        $this->data['data_rows'] = $result_array['data_rows'];
		
		$this->data['page_heading'] = 'Website Contents (CI Pagination Version)';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index_ci_pagination', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function render_datatable() {
        //Total rows - Refer to model method definition
        $result_array = $this->cms_model->get_rows();
        $total_rows = $result_array['num_rows'];

        // Total filtered rows - check without limit query. Refer to model method definition
        $result_array = $this->cms_model->get_rows(NULL, NULL, NULL, TRUE, FALSE);
        $total_filtered = $result_array['num_rows'];

        // Data Rows - Refer to model method definition
        $result_array = $this->cms_model->get_rows(NULL, NULL, NULL, TRUE);
        $data_rows = $result_array['data_rows'];
        $data = array();
        $no = $_REQUEST['start'];
        foreach ($data_rows as $result) {
            $no++;
            $row = array();
            $row[] = $result['pagecontent_type'];
            $row[] = $result['pagecontent_title'];
            $row[] = $this->common_lib->display_date($result['pagecontent_created_on'], true);
            $row[] = (strtolower($result['pagecontent_status']) == 'y') ? 'Published' : 'Unpublished';            
            //add html for action
            $action_html = '';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit/' .$result['id']), '<i class="fa fa-edit" aria-hidden="true"></i> Edit', array(
                'class' => 'btn btn-sm btn-outline-secondary mr-1',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit',
                'title' => 'Edit',
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/delete/' . $result['id']), '<i class="fa fa-trash" aria-hidden="true"></i> Delete', array(
                'class' => 'btn btn-sm btn-outline-danger btn-delete ml-1',
				'data-confirmation'=>true,
				'data-confirmation-message'=>'Are you sure, you want to delete this?',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Delete',
                'title' => 'Delete',
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

    function add() {
        //Check user permission by permission name mapped to db
        //$is_authorized = $this->common_lib->is_auth('cms-add');
        //$this->data['page_heading'] = "Add Page Content";
		$this->breadcrumbs->push('Add','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'insert') {
            if ($this->validate_form_data('add') == true) {

                $postdata = array(
                    'pagecontent_type' => $this->input->post('pagecontent_type'),
                    'pagecontent_title' => $this->input->post('pagecontent_title'),
                    'pagecontent_text' => $this->input->post('pagecontent_text'),
                    'pagecontent_meta_keywords' => $this->input->post('pagecontent_meta_keywords'),
                    'pagecontent_meta_description' => $this->input->post('pagecontent_meta_description'),
                    'pagecontent_display_start_date' => $this->common_lib->convert_to_mysql($this->input->post('pagecontent_display_start_date')),
                    'pagecontent_display_end_date' => $this->common_lib->convert_to_mysql($this->input->post('pagecontent_display_end_date')),
                    'pagecontent_meta_author' => $this->input->post('pagecontent_meta_author'),
                    'pagecontent_user_id' => $this->sess_user_id,
					'pagecontent_status' => $this->input->post('pagecontent_status')
                );
                $insert_id = $this->cms_model->insert($postdata);
                if ($insert_id) {
                    $this->session->set_flashdata('flash_message', 'Data Added Successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/add');
                }
            }
        }
		$this->data['page_heading'] = 'Add Contents';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function edit() {
        //Check user permission by permission name mapped to db
        //$is_authorized = $this->common_lib->is_auth('cms-edit');
		//$this->data['page_heading'] = "Edit Page Content";
		$this->breadcrumbs->push('Edit','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'update') {
            if ($this->validate_form_data('edit') == true) {
                $postdata = array(
                    'pagecontent_type' => $this->input->post('pagecontent_type'),
                    'pagecontent_title' => $this->input->post('pagecontent_title'),
                    'pagecontent_text' => $this->input->post('pagecontent_text'),
                    'pagecontent_meta_keywords' => $this->input->post('pagecontent_meta_keywords'),
                    'pagecontent_meta_description' => $this->input->post('pagecontent_meta_description'),
                    'pagecontent_meta_author' => $this->input->post('pagecontent_meta_author'),
                    'pagecontent_status' => $this->input->post('pagecontent_status'),
					'pagecontent_display_start_date' => $this->common_lib->convert_to_mysql($this->input->post('pagecontent_display_start_date')),
                    'pagecontent_display_end_date' => $this->common_lib->convert_to_mysql($this->input->post('pagecontent_display_end_date')),
                    'pagecontent_archived' => $this->input->post('pagecontent_archived'),
					'pagecontent_user_id' => $this->sess_user_id
                );
                $where_array = array('id' => $this->input->post('id'));
                $res = $this->cms_model->update($postdata, $where_array);
                if ($res) {
                    $this->session->set_flashdata('flash_message', 'Data Updated Successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                }
            }
        }
        $result_array = $this->cms_model->get_rows($this->id);
        $this->data['rows'] = $result_array['data_rows'];
		$this->data['page_heading'] = 'Edit Contents';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }

    function delete() {
        //Check user permission by permission name mapped to db
        //$is_authorized = $this->common_lib->is_auth('cms-delete');

        $where_array = array('id' => $this->id);
        $res = $this->cms_model->delete($where_array);
        if ($res) {
            $this->session->set_flashdata('flash_message', 'Data Deleted Successfully.');
            $this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect($this->router->directory.$this->router->class);
        }
    }

    function validate_form_data($action = NULL) {
        $this->form_validation->set_rules('pagecontent_type', 'page content type', 'required');
        $this->form_validation->set_rules('pagecontent_title', 'page content title', 'required');
        $this->form_validation->set_rules('pagecontent_text', 'page content text', 'required');
        $this->form_validation->set_rules('pagecontent_status', 'display status', 'required');

        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
	
	
	#### Banner Slider Section ###
	function render_banner_datatable() {		
        //Total rows - Refer to model method definition
		$cond = array(
			'upload_related_to' => 'slider',
			'upload_related_to_id' => NULL,
			'upload_file_type_name' => NULL
		);
        $result_array = $this->upload_model->get_uploads(NULL, NULL, NULL, FALSE, FALSE, $cond);
        $total_rows = $result_array['num_rows'];

        // Total filtered rows - check without limit query. Refer to model method definition
        $result_array = $this->upload_model->get_uploads(NULL, NULL, NULL, TRUE, FALSE, $cond);
        $total_filtered = $result_array['num_rows'];

        // Data Rows - Refer to model method definition
        $result_array = $this->upload_model->get_uploads(NULL, NULL, NULL, TRUE, TRUE, $cond);
        $data_rows = $result_array['data_rows'];
        $data = array();
        $no = $_REQUEST['start'];
        foreach ($data_rows as $result) {
            $no++;
            $row = array();
			
			$img_src = "";
			$default_path = "assets/src/img/no-image.png";
			if(isset($result['upload_file_name'])){					
				$banner_img = "assets/uploads/banner_img/".$result['upload_file_name'];					
				if (file_exists(FCPATH . $banner_img)) {
					$img_src = $banner_img;
				}else{
					$img_src = $default_path;
				}
			}else{
				$img_src = $default_path;
			}
            //$row[] = $result['upload_file_name'].'<div>'.$result['upload_mime_type'].'</div>';
            $row[] = '<img class="img banner-img-xs" src="'.base_url($img_src).'"><div>'.$result['upload_mime_type'].'</div>';
            //$row[] = $result['upload_mime_type'];
            //$row[] = $this->common_lib->display_date($result['upload_datetime'], true);
            $row[] = (strtolower($result['upload_status']) == 'y') ? 'Published' : 'Unpublished';            
            //add html for action
            $action_html = '';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit_banner/' .$result['id']), '<i class="fa fa-edit" aria-hidden="true"></i> Edit', array(
                'class' => 'btn btn-sm btn-outline-secondary mr-1',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit',
                'title' => 'Edit',
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/delete_banner/' . $result['id'].'/'.$result['upload_file_name']), '<i class="fa fa-trash" aria-hidden="true"></i> Delete', array(
                'class' => 'btn btn-sm btn-outline-danger btn-delete ml-1',
				'data-confirmation'=>true,
				'data-confirmation-message'=>'Are you sure, you want to delete this?',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Delete',
                'title' => 'Delete',
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

	function manage_banner() {
        // Check user permission by permission name mapped to db
        // $is_authorized = $this->common_lib->is_auth('cms-list-view');
		
		// Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');
			
		$this->breadcrumbs->push('View','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->data['page_heading'] = 'Carousel';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/manage_banner', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }
	
	function add_banner() {
		$this->breadcrumbs->push('Add','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'insert') {
			$this->upload_file();            
        }
		$this->data['page_heading'] = 'Create a Carousel Slider';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add_banner', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }
	
	function edit_banner() {
		$this->breadcrumbs->push('Edit','/');				
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if ($this->input->post('form_action') == 'update') {
			$this->upload_file();            
        }
		$result_array = $this->upload_model->get_uploads($this->id, NULL, NULL, FALSE, FALSE, NULL);
        $this->data['rows'] = $result_array['data_rows'];		
		$this->data['page_heading'] = 'Edit Carousel Slider';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit_banner', $this->data, true);
        $this->load->view('_layouts/layout_admin_default', $this->data);
    }
	
	function delete_banner(){
		$uploaded_file_id = $this->uri->segment(3);
		$uploaded_file_name = $this->uri->segment(4);
		//if($uploaded_file_name){
			//Unlink previously uploaded file                    
			$file_path = 'assets/uploads/banner_img/'.$uploaded_file_name;
			//if (file_exists(FCPATH . $file_path)) {
				$this->common_lib->unlink_file(array(FCPATH . $file_path));
				$res = $this->upload_model->delete(array('id'=>$uploaded_file_id),'uploads');
				if($res){
					$this->session->set_flashdata('flash_message', 'Banner has been deleted successfully.');
					$this->session->set_flashdata('flash_message_css', 'alert-success');
					redirect($this->router->directory.$this->router->class.'/manage_banner');
				}else{
					$this->session->set_flashdata('flash_message', 'Error occured while processing your request.');
					$this->session->set_flashdata('flash_message_css', 'alert-danger');
					redirect($this->router->directory.$this->router->class.'/manage_banner');
				}
			//}
		//}
	}
	
	function validate_banner_form_data($action = NULL) {        
        $this->form_validation->set_rules('upload_status', 'upload status', 'required');
		if(($this->input->post('form_action') == 'insert') && (empty($_FILES['userfile']['name']))){
			$this->form_validation->set_rules('userfile', 'file', 'required');
		}
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
	
	function upload_file() {
        if ($this->validate_banner_form_data() == true) {
            $upload_related_to = 'slider'; // related to user, product, album, contents etc
            $upload_related_to_id = $this->id; // related to id user id, product id, album id etc
            $upload_file_type_name = $this->input->post('upload_file_type_name'); // file type name            
			$upload_text_1 = $this->input->post('upload_text_1');
            $upload_text_2 = $this->input->post('upload_text_2');
            $upload_text_3 = $this->input->post('upload_text_3');
            $upload_status = $this->input->post('upload_status');
			$upload_id = $this->input->post('id');

            //Create directory for object specific
            $upload_path = 'assets/uploads/banner_img';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }
            $allowed_ext = 'png|jpg|jpeg|doc|docx|pdf';
            if ($upload_file_type_name == 'slider_img') {
                $allowed_ext = 'png|jpg|jpeg';
            }
            $upload_param = array(
                'upload_path' => $upload_path, // original upload folder
                'allowed_types' => $allowed_ext, // allowed file types,
                'max_size' => '2048', // max 2MB size,
                'file_new_name' => $upload_related_to_id . '_' . $upload_file_type_name . '_' . time(),
            );
			
			// If user chhose file to upload
			if(!empty($_FILES['userfile']['name'])){
				$upload_result = $this->common_lib->upload_file('userfile', $upload_param);
				if (isset($upload_result['file_name']) && empty($upload_result['upload_error'])) {
					$uploaded_file_name = $upload_result['file_name'];
					$postdata = array(
						'upload_related_to' => $upload_related_to,
						'upload_related_to_id' => $upload_related_to_id,
						'upload_file_type_name' => $upload_file_type_name,
						'upload_file_name' => $uploaded_file_name,
						'upload_mime_type' => $upload_result['file_type'],									
						'upload_by_user_id' => $this->sess_user_id,
						'upload_is_featured'=>'N',
						'upload_is_verified' => 'N',
						'upload_status'=>$upload_status,
						'upload_datetime' => date('Y-m-d H:i:s'),
						'upload_text_1' => $upload_text_1,
						'upload_text_2' => $upload_text_2,
						'upload_text_3' => $upload_text_3,					
					);

					
					if($this->input->post('form_action') == 'update'){
						$upload_data = $this->upload_model->get_uploads($upload_id, NULL, NULL, FALSE, FALSE, NULL);
						$uploads = $upload_data['data_rows'];
					}else{
						// Allow mutiple file upload for a file type.
						$multiple_allowed_upload_file_type = array('slider_img');
						if (!in_array($upload_file_type_name, $multiple_allowed_upload_file_type)) {
							$cond = array(
								'upload_related_to' => $upload_related_to,
								'upload_related_to_id' => $upload_related_to_id,
								'upload_file_type_name' => $upload_file_type_name
							);
							$upload_data = $this->upload_model->get_uploads(NULL, NULL, NULL, FALSE, FALSE, $cond);
							$uploads = $upload_data['data_rows'];
						}
					}
					//print_r($uploads); die();
					
					if (isset($uploads[0]) && ($uploads[0]['id'] != '')) {
						//Unlink previously uploaded file                    
						$file_path = $upload_param['upload_path'] . '/' . $uploads[0]['upload_file_name'];
						if (file_exists(FCPATH . $file_path)) {
							$this->common_lib->unlink_file(array(FCPATH . $file_path));
						}
						// Now update table
						$update_upload = $this->cms_model->update($postdata, array('id' => $uploads[0]['id']), 'uploads');
						$this->session->set_flashdata('flash_message', 'File uploaded successfully.');
						$this->session->set_flashdata('flash_message_css', 'alert-success');
						redirect(current_url());
					} else {
						$upload_insert_id = $this->cms_model->insert($postdata, 'uploads');
						$this->session->set_flashdata('flash_message', 'File uploaded successfully.');
						$this->session->set_flashdata('flash_message_css', 'alert-success');
						redirect(current_url());
					}
				} else if (sizeof($upload_result['upload_error']) > 0) {
					$error_message = $upload_result['upload_error'];
					$this->session->set_flashdata('flash_message', $error_message);
					$this->session->set_flashdata('flash_message_css', 'alert-danger');
					redirect(current_url());
				}
			}
			//if user only update text etc not changing images
			else{
				$postdata = array(
						'upload_status'=>$upload_status,						
						'upload_text_1' => $upload_text_1,
						'upload_text_2' => $upload_text_2,
						'upload_text_3' => $upload_text_3,					
					);
				$id = $this->input->post('id');
				$update_upload = $this->cms_model->update($postdata, array('id' => $id), 'uploads');
				$this->session->set_flashdata('flash_message', 'Data updated successfully.');
				$this->session->set_flashdata('flash_message_css', 'alert-success');
				redirect(current_url());
			}
			
            
        }
    }

}

?>
