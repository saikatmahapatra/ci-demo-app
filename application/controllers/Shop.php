<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shop extends CI_Controller {

    var $data;
    var $id;
    var $sess_user_id;

    function __construct() {
        parent::__construct();
        
        //Check if any user logged in else redirect to login
        /*$is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }*/

        // Get logged  in user id
        $this->sess_user_id = $this->common_lib->get_sess_user('id');

        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements('site');

        // Load required js files for this controller
        $javascript_files = array(
            $this->router->class
        );
        $this->data['app_js'] = $this->common_lib->add_javascript($javascript_files);
        
        
        
        $this->id = $this->uri->segment(3);

        $this->load->library('cart');        
        $this->load->model('user_model');
        $this->load->model('product_model');
        $this->load->model('order_model');
        $this->cart->product_name_rules = '[:print:]'; // allow any characters in product name while add to cart  
		$this->data['payment_method'] = array('cod'=>'Cash On Delivery','debit_card' => 'Debit Card', 'credit_card' => 'Credit Card', 'net_banking' => 'Net Banking');
		
        $this->data['page_title'] = $this->router->class.' : '.$this->router->method;
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		// add breadcrumbs. push() - Append crumb to stack
		$this->breadcrumbs->push('Dashboard', '/admin');
		$this->breadcrumbs->push('Orders', '/admin/order');		
		$this->data['breadcrumbs'] = $this->breadcrumbs->show();
    }

    function index(){
        $this->store();
    }

    function store(){
        
        
        $result = $this->product_model->get_rows();
        $this->data['total_products'] = $result['num_rows'];
        $this->data['products'] = $result['data_rows'];
		$this->data['page_title'] = 'Shop Online';
        $this->data['maincontent'] = $this->load->view('site/'.$this->router->class.'/store', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function details(){
        
        
        $result = $this->product_model->get_rows();
        $this->data['total_products'] = $result['num_rows'];
        $this->data['products'] = $result['data_rows'];
		$this->data['page_title'] = 'Item Details';
        $this->data['maincontent'] = $this->load->view('site/'.$this->router->class.'/store', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }
    
    function get_cart_data(){
		$result = array();
		$cartrows = array();
		$result['cartrows'] = '';
		$result['cart_total'] = $this->cart->total(); // Returns:	Total amount;
		$result['total_items'] = $this->cart->total_items(); //Returns:	Total amount of items in the cart;
		
        foreach ($this->cart->contents() as $item) {
            $product_options = $this->cart->product_options($item['rowid']);
            $cartrows[] = array(
                'rowid' => $item['rowid'],
                'id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'name' => $item['name'],
                'line_total' => $item['subtotal'],
                'category_name' => $product_options['category_name']
            );
        }
		$result['cartrows'] = $cartrows;
        return $result;
	}

    function my_cart() {
        //Check if any user logged in else redirect to login
        /*$is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }*/

        
        
        
        $cart_data = $this->get_cart_data();		
		$this->data['cartrows'] = $cart_data['cartrows'];
        $this->data['cart_total'] = $cart_data['cart_total']; // Returns:	Total amount
        $this->data['total_items'] = $cart_data['total_items']; //Returns:	Total amount of items in the cart
		
		//Update quantity
		if($this->input->post('form_action')=='update_cart'){
			$this->update_cart();
		}
		
		$this->data['page_title'] = 'My Cart';
        $this->data['maincontent'] = $this->load->view('site/'.$this->router->class.'/my_cart', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }

    function find_item($product_id) { //return rowid against a product id, if already added to the cart
        foreach ($this->cart->contents() as $item) {
            if ($item['id'] == $product_id) {
                return array('rowid' => $item['rowid'], 'qty' => $item['qty']);
            }
        }
        return false;
    }

    function add_to_cart() {
        $product_id = $this->input->get_post('product_id') ? $this->input->get_post('product_id') : $this->common_lib->decode($this->uri->segment(3));
        $result = $this->find_item($product_id);
        $qty = ($this->input->get_post('quantity') > 0) ? $this->input->get_post('quantity') : '1';

        if ($result == false) {
            $result_array = $this->product_model->get_rows($product_id);
            $row = $result_array['data_rows'];
            //$product_img = $this->product_model->get_product_images($product_id);
            //$img = $product_img[0]->product_image_file ? $product_img[0]->product_image_file : '';
            $data = array(
                'id' => $row[0]['id'],
                'qty' => $qty,
                'price' => $row[0]['product_price'],
                'name' => $row[0]['product_name'],
                'options' => array(
                    'category_name' => isset($row[0]['category_name']) ? $row[0]['category_name'] : 'none',
                    'product_image' => 'prod.png'
                )
            );
			//print_r($data); die();
            $result = $this->cart->insert($data);
        } else {
            $data = array(
                'rowid' => $result['rowid'],
                'qty' => $result['qty'] + $qty
            );

            $result = $this->cart->update($data);
        }
        if ($result) {
            if ($this->input->get_post('via_ajax')) {
                echo 'add_success'; // For Ajax response text
                die();
            }
            $this->common_lib->set_flash_message('Item has been added to your cart.');
            $this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect($this->router->directory.$this->router->class.'/my_cart');
        }
    }

    function update_cart() {
        for ($i = 1; $i <= $this->cart->total_items(); $i++) {
            $data = array(
                'rowid' => $this->input->post('rowid_' . $i),
                'qty' => $this->input->post('quantity_' . $i)
            );

            $result = $this->cart->update($data);
        }
        $this->common_lib->set_flash_message('You cart has been updated successfully.');
        $this->session->set_flashdata('flash_message_css', 'alert-success');
        redirect($this->router->directory.$this->router->class.'/my_cart');
    }

    function remove_cart($rowid = NULL) {
        $rowid = $this->uri->segment(3);
        $data = array(
            'rowid' => $rowid,
            'qty' => 0
        );
        $result = $this->cart->update($data);
        $this->common_lib->set_flash_message('Item has been removed from your cart');
        $this->session->set_flashdata('flash_message_css', 'alert-success');
        redirect($this->router->directory.$this->router->class.'/my_cart');
    }

    function remove_all() {
        $result = $this->cart->destroy();
        $this->common_lib->set_flash_message('Item has been removed from your cart');
        $this->session->set_flashdata('flash_message_css', 'alert-success');
        redirect($this->router->directory.$this->router->class.'/my_cart');
    }
	
	function init_payment(){
        //Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }
        
        if($this->cart->total_items() <= 0){
            $this->common_lib->set_flash_message('You don\'t have any items in your cart. Please add atleast one to proceed.');
            $this->session->set_flashdata('flash_message_css', 'alert-danger');
        }

		
        
        
		$cart_data = $this->get_cart_data();
		$this->data['cartrows'] = $cart_data['cartrows'];
        $this->data['cart_total'] = $cart_data['cart_total']; // Returns:	Total amount
        $this->data['total_items'] = $cart_data['total_items']; //Returns:	Total amount of items in the cart
        
        //Shipping address
        $this->data['shipping_address'] = $this->get_user_shipping_address(NULL, $this->sess_user_id, 'S'); //Returns:	Total amount of items in the cart
		
		//Place Order
		if ($this->input->post('form_action') == 'place_order') {
            if ($this->validate_order_payment_form_data() == true) {
				$this->place_order();
			}
        }

		$this->data['page_title'] = 'Checkout';		
		$this->data['maincontent'] = $this->load->view('site/'.$this->router->class.'/init_payment', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
	}

    function get_user_shipping_address($address_id = NULL, $user_id, $address_type_char){
        $result = array();
        $shipping_address = $this->user_model->get_user_address($address_id, $user_id, $address_type_char); //S -Shipping
        if(isset($shipping_address)){
            $result = $shipping_address;
        }
        return $result;
    }

    function place_order() {  
        //Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }

		$cart_data = $this->get_cart_data();
		$cartrows = $cart_data['cartrows'];		
        $cart_total = $cart_data['cart_total']; // Returns:	Total amount
        $total_items = $cart_data['total_items']; //Returns:	Total number of added items of a cart
		$shipping_address_id = $this->input->post('shipping_address');
		$shipping_address = $this->get_user_shipping_address($shipping_address_id, $this->sess_user_id, 'S');
        $order_number = $this->generate_order_number();
		$postdata = array(
			'order_user_id' => $this->sess_user_id,
			'order_no' => $order_number,
			'order_tax_amt' => $this->input->post('order_tax_amount'),
			'order_coupon_code' => $this->input->post('order_coupon_code'),
			'order_discount_amt' => $this->input->post('order_discount_amount'),
			'order_total_amt' => $cart_total,
			'order_payment_method' => $this->input->post('payment_method'),
			'order_payment_trans_id' => time(),
			'order_shipping_name' => $shipping_address[0]['name'],
			'order_shipping_phone1' => $shipping_address[0]['phone1'],
			'order_shipping_locality' => $shipping_address[0]['locality'],
			'order_shipping_zip' => $shipping_address[0]['zip'],
			'order_shipping_address' => $shipping_address[0]['address'],
			'order_shipping_city' => $shipping_address[0]['city'],
			'order_shipping_state' => $shipping_address[0]['state'],
			'order_shipping_landmark' => $shipping_address[0]['landmark'],
			'order_shipping_phone2' => $shipping_address[0]['phone2'],
		);
		//echo '<pre>';
		//print_r($postdata);
		//die();
		$insert_id_order_id = $this->order_model->insert($postdata);
		
		if ($insert_id_order_id) {
			$order_details_post_data = array();
			foreach($cartrows as $key => $cart_row){				
				$order_details_post_data[$key] = array(
					'order_id' => $insert_id_order_id,
					'product_id' => $cart_row['id'],
					//'order_detail_unit_price' => $cart_row['price'],
					'order_detail_price' => $cart_row['price'],
					'order_detail_quantity' => $cart_row['qty'],
					//'order_detail_discount_coupon' => $cart_row['coupon'],
					//'order_detail_discount_amt' => $cart_row['coupon'],
					//'order_detail_delivery_amt' => $cart_row['delivery'],
					'order_detail_total_amt' => $cart_row['line_total'] // this col aded for future implementation on calculation on gst, discount, delivey charges
				);

				
			}
			//echo '<pre>';
			//print_r($order_details_post_data); die();
			$this->order_model->insert_batch($order_details_post_data, 'order_details');
			
			$this->common_lib->set_flash_message('Thank you! We have received your order. Your Order Number '.$order_number.' Payment Done (Test)');
			$this->session->set_flashdata('flash_message_css', 'alert-success');
            redirect($this->router->directory.$this->router->class.'/transaction_response/'.$order_number);
		}
    }

    
    function generate_order_number() {
        $order_no = date('mdY') . time();
        return $order_no;
    }

    function validate_order_payment_form_data() {
        $this->form_validation->set_rules('shipping_address', 'Shipping address selection', 'required');        
        $this->form_validation->set_rules('payment_method', 'payment method selection', 'required');        
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    function transaction_response() {
        //Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }

		$this->cart->destroy(); // remove cart items
		
        
        //Update table with payment response
		//$this->data = array();
		$this->data['order_no'] = $this->uri->segment(4);
		$this->data['page_title'] = 'Your Online Transaction Summary';
		$this->data['maincontent'] = $this->load->view('site/'.$this->router->class.'/transaction_response', $this->data, true);
        $this->load->view('site/_layouts/layout_default', $this->data);
    }


}

?>
