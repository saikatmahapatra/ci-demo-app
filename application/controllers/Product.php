<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends CI_Controller {

    var $data;
    var $id;

    function __construct() {
        parent::__construct();

        //Check if any user logged in else redirect to login
        /*$is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }*/

        //Has logged in user permission to access this page or method?        
        /*$is_authorized = $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access'
        ));*/

        // Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements();

        //add required js files for this controller        
        $app_js_src = array(
            'assets/dist/js/'.$this->router->class.'.js', //create js file name same as controller name
        );
        $this->data['app_js'] = $this->common_lib->add_javascript($app_js_src);

        
        $this->load->model('category_model');
        $this->load->model('product_model');
        $this->load->model('upload_model');
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
        $this->data['category_dropdown'] = $this->category_model->get_category_dropdown();

        $this->id = $this->uri->segment(3);
        $this->data['page_heading'] = $this->router->class.' : '.$this->router->method;
		
		// Status flag indicator for showing in table grid etc
		$this->data['status_flag'] = array(
            'Y'=>array('text'=>'Active', 'css'=>'text-success', 'icon'=>'<i class="fa fa-circle text-success" aria-hidden="true"></i>'),
            'N'=>array('text'=>'Inactive', 'css'=>'text-warning', 'icon'=>'<i class="fa fa-circle text-warning" aria-hidden="true"></i>'),
            'A'=>array('text'=>'Archived', 'css'=>'text-danger', 'icon'=>'<i class="fa fa-circle text-danger" aria-hidden="true"></i>')
        );
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Dashboard', '/admin');
		$this->breadcrumbs->push('Product', '/admin/product');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
    }

    function index() {
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

		$this->breadcrumbs->push('View', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->data['page_heading'] = 'Products';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/index', $this->data, true);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function render_datatable() {
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

        //Total rows - Refer to model method definition
        $result_array = $this->product_model->get_rows();
        $total_rows = $result_array['num_rows'];

        // Total filtered rows - check without limit query. Refer to model method definition
        $result_array = $this->product_model->get_rows(NULL, NULL, NULL, TRUE, FALSE);
        $total_filtered = $result_array['num_rows'];

        // Data Rows - Refer to model method definition
        $result_array = $this->product_model->get_rows(NULL, NULL, NULL, TRUE);
        $data_rows = $result_array['data_rows'];
        $data = array();
        $no = $_REQUEST['start'];
        foreach ($data_rows as $result) {
            $no++;
            $row = array();
            /*$html_product_details = '';
            $html_product_details.='<div>' . $result['product_name'] . ' (SKU: ' . $result['product_sku'] . ')' . '</div>';
            $html_product_details.=isset($result['product_color']) ? '<div>Color: ' . $result['product_color'] . '</div>' : '<div>Color: </div>';
            $html_product_details.=isset($result['product_size']) ? '<div>Size: ' . $result['product_size'] . '</div>' : '<div>Size: </div>';
            $html_product_details.=isset($result['product_height']) ? '<div>Height: ' . $result['product_height'] . '</div>' : '<div>Height: </div>';
            $html_product_details.=isset($result['product_length']) ? '<div>Length: ' . $result['product_length'] . '</div>' : '<div>Length: </div>';
            $html_product_details.=isset($result['product_weight']) ? '<div>Weight: ' . $result['product_weight'] . '</div>' : '<div>Weight: </div>';

            $row[] = $html_product_details;*/
            $row[] = $result['product_sku'];
            $row[] = $result['product_name'];

            /*$html_price_details = '';
            $html_price_details.=isset($result['product_mrp']) ? '<div>MRP: ' . $result['product_mrp'] . '</div>' : '<div>MRP: </div>';
            $html_price_details.=isset($result['product_price']) ? '<div>Price: ' . $result['product_price'] . '</div>' : '<div>Price: </div>';
            $row[] = $html_price_details;*/
			$row[] = isset($result['category_name']) ? $result['category_name'] :'';
			$row[] = isset($result['product_mrp']) ? $result['product_mrp'] :'';
			$row[] = isset($result['product_price']) ? $result['product_price'] :'';
            //$row[] = word_limiter($result['product_description'], 6);
            $row[] = isset($result['product_status']) ? $this->data['status_flag'][$result['product_status']]['icon'] : '';
            //add html for action
            $action_html = '';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/edit/' .$result['id']), '<i class="fa fa-edit" aria-hidden="true"></i> Edit', array(
                'class' => 'btn btn-sm btn-outline-secondary mr-1',
                'data-toggle' => 'tooltip',
                'data-original-title' => 'Edit',
                'title' => 'Edit',
            ));
            $action_html.='&nbsp;';
            $action_html.= anchor(base_url($this->router->directory.$this->router->class.'/delete/' .$result['id']), '<i class="fa fa-trash" aria-hidden="true"></i> Delete', array(
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

    function validate_form_data($action = NULL) {
        //$this->form_validation->set_rules('category_name', 'category name', 'required|is_unique[categories.category_name]');
        $this->form_validation->set_rules('category_id', 'category', 'required');
        $this->form_validation->set_rules('product_name', 'product name', 'required');
        $this->form_validation->set_rules('product_price', 'price', 'required');
        $this->form_validation->set_rules('product_mrp', 'MRP', 'required');
        $this->form_validation->set_rules('product_description', 'description', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function add() {
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

        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->breadcrumbs->push('Add', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
        if ($this->input->post('form_action') == 'insert') {
            if ($this->validate_form_data('add') == true) {
                $postdata = array(
                    'product_sku' => 'E' . time(),
                    'product_name' => $this->input->post('product_name'),
                    'category_id' => $this->input->post('category_id'),
                    'product_price' => $this->input->post('product_price'),
                    'product_mrp' => $this->input->post('product_mrp'),
                    'product_description' => $this->input->post('product_description'),
                    'product_size' => $this->input->post('product_size'),
                    'product_color' => $this->input->post('product_color'),
                    'product_weight' => $this->input->post('product_weight'),
                    'product_height' => $this->input->post('product_height'),
                    'product_width' => $this->input->post('product_width'),
                    'product_length' => $this->input->post('product_length'),
                );
                $insert_id = $this->product_model->insert($postdata);
                if ($insert_id) {
                    $this->session->set_flashdata('flash_message', 'Data added successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'/add');
                }
            }
        }
		$this->data['page_heading'] = 'Add Product';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/add', $this->data, true);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function edit() {
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

        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
		
		$this->breadcrumbs->push('Edit', '/');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
		
		
        if ($this->input->post('form_action') == 'update') {
            if ($this->validate_form_data('edit') == true) {
                $postdata = array(
                    'product_name' => $this->input->post('product_name'),
                    'category_id' => $this->input->post('category_id'),
                    'product_price' => $this->input->post('product_price'),
                    'product_mrp' => $this->input->post('product_mrp'),
                    'product_description' => $this->input->post('product_description'),
                    'product_size' => $this->input->post('product_size'),
                    'product_color' => $this->input->post('product_color'),
                    'product_weight' => $this->input->post('product_weight'),
                    'product_height' => $this->input->post('product_height'),
                    'product_width' => $this->input->post('product_width'),
                    'product_length' => $this->input->post('product_length'),
                    'product_status' => $this->input->post('product_status'),
                );
                $where_array = array('id' => $this->input->post('id'));
                $res = $this->product_model->update($postdata, $where_array);

                if ($res) {
                    $this->session->set_flashdata('flash_message', 'Data updated successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect($this->router->directory.$this->router->class.'');
                }
            }
        }
        $result_array = $this->product_model->get_rows($this->id);
        $this->data['rows'] = $result_array['data_rows'];

        //Uploads        
        $upload_related_to = 'product';
        $this->data['upload_related_to'] = $upload_related_to;
        $cond = array(
			'upload_related_to' => $upload_related_to,
			'upload_related_to_id' => $this->id,
			'upload_file_type_name' => NULL
		);
        $upload_data = $this->upload_model->get_uploads(NULL, NULL, NULL, FALSE, FALSE, $cond);		 
		$this->data['all_uploads'] = $upload_data['data_rows'];
        $this->data['arr_upload_file_type_name'] = $this->get_upload_file_type_names();
        if ($this->input->post('form_action') == 'file_upload') {
            $this->upload_file();
        }
		$this->data['page_heading'] = 'Edit Product';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/edit', $this->data, true);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    function delete() {
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

        $where_array = array('id' => $this->id);
        $res = $this->product_model->delete($where_array);
        if ($res) {            
            $upload_related_to = 'product';
            $this->delete_uploads($upload_related_to,$this->id);
            $this->session->set_flashdata('flash_message', 'Data deleted successfully.');
            $this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect($this->router->directory.$this->router->class.'');
        }
    }

    function get_upload_file_type_names() {
        $upload_file_type_name = array(
            "" => "Select",
            "aadhar_card" => "Aadhar Card",
            "bgc_doc" => "BGC Verification Supporting Doc",
            "driving_license" => "Driving License",
            "id_card" => "ID Card",
            "medical_report" => "Medical Verification Supporting Report",
            "product_image" => "Product Image",            
            "voter_card" => "Voter Card",
        );
        return $upload_file_type_name;
    }

    function upload_file() {
        if ($this->validate_uplaod_form_data() == true) {
            $upload_related_to = 'product'; // related to user, product, album, contents etc
            $upload_related_to_id = $this->id; // related to id user id, product id, album id etc
            $upload_file_type_name = $this->input->post('upload_file_type_name'); // file type name

            //Create directory for object specific
            $upload_path = 'assets/uploads/' . $upload_related_to . '/' . $upload_related_to_id;
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }
            $allowed_ext = 'png|jpg|jpeg|doc|docx|pdf';
            if ($upload_file_type_name == 'product_image') {
                $allowed_ext = 'png|jpg|jpeg';
            }
            $upload_param = array(
                'upload_path' => $upload_path, // original upload folder
                'allowed_types' => $allowed_ext, // allowed file types,
                'max_size' => '2048', // max 2MB size,
                'file_new_name' => $upload_related_to_id . '_' . $upload_file_type_name . '_' . time(),
            );
            $upload_result = $this->common_lib->upload_file('userfile', $upload_param);
            if (isset($upload_result['file_name']) && empty($upload_result['upload_error'])) {
                $uploaded_file_name = $upload_result['file_name'];
                $postdata = array(
                    'upload_related_to' => $upload_related_to,
                    'upload_related_to_id' => $upload_related_to_id,
                    'upload_file_type_name' => $upload_file_type_name,
                    'upload_file_name' => $uploaded_file_name,
                    'upload_mime_type' => $upload_result['file_type'],
                    'upload_by_user_id' => $this->sess_user_id
                );

                // Allow mutiple file upload for a file type.
                $multiple_allowed_upload_file_type = array('product_image');
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
                    $update_upload = $this->product_model->update($postdata, array('id' => $uploads[0]['id']), 'uploads');
                    $this->session->set_flashdata('flash_message', 'File uploaded successfully.');
                    $this->session->set_flashdata('flash_message_css', 'alert-success');
                    redirect(current_url());
                } else {
                    $upload_insert_id = $this->product_model->insert($postdata, 'uploads');
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
    }

    function validate_uplaod_form_data() {
        $this->form_validation->set_rules('upload_file_type_name', 'type selection', 'required');
        if (empty($_FILES['userfile']['name'])){
			$this->form_validation->set_rules('userfile', ' ', 'required');
		}
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function delete_file() {
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

        $id = $this->input->get_post('id');
        $file_path = $this->input->get_post('file_path');
        if ($id) {
            $where_array = array('id' => $id);
            $res = $this->user_model->delete($where_array, 'uploads');
            if ($res) {
                $this->common_lib->unlink_file(array(FCPATH . $file_path));
            }
            echo json_encode("success");
        } else {
            echo json_encode("error");
        }
    }
    
    function delete_uploads($upload_related_to, $upload_related_to_id) {
        $where_array = array('upload_related_to' => $upload_related_to, 'upload_related_to_id' => $upload_related_to_id);
        $res = $this->product_model->delete($where_array, 'uploads');
        if ($res) {
            $upload_path = 'assets/uploads/'.$upload_related_to.'/' . $upload_related_to_id;
            $this->common_lib->recursive_remove_directory(FCPATH . $upload_path);
        }
    }

    //Public
    function shop_online(){
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $result = $this->product_model->get_rows();
        $this->data['total_products'] = $result['num_rows'];
        $this->data['products'] = $result['data_rows'];
		$this->data['page_heading'] = 'Shop Online';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/shop_online', $this->data, true);
        $this->load->view('_layouts/layout_default', $this->data);
    }

    //Public
    function details(){
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        $result = $this->product_model->get_rows();
        $this->data['total_products'] = $result['num_rows'];
        $this->data['products'] = $result['data_rows'];
		$this->data['page_heading'] = 'Shop Online';
        $this->data['maincontent'] = $this->load->view($this->router->class.'/shop_online', $this->data, true);
        $this->load->view('_layouts/layout_default', $this->data);
    }
}

?>
