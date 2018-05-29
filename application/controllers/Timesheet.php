<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timesheet extends CI_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        //Loggedin user details
        $this->sess_user_id = $this->common_lib->get_sess_user('id');        
        
        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements();
        
        //add required js files for this controller
        $app_js_src = array('assets/dist/js/timesheet.js');         
        $this->data['app_js'] = $this->common_lib->add_javascript($app_js_src);
		        
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
		
		//Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
            redirect($this->router->directory.'user/login');
        }

        //Has logged in user permission to access this page or method?        
        $this->common_lib->check_user_role_permission(array(
            'default-super-admin-access',
            'default-admin-access',
            'default-user-access'			
        ));
		
		//View Page Config
		$this->data['view_dir'] = 'site/'; // inner view and layout directory name inside application/view
		$this->data['page_heading'] = $this->router->class.' : '.$this->router->method;
        
    }
	
	function index() {
		$prefs = array (
               'start_day'    => 'monday',
               'month_type'   => 'short',
               'day_type'     => 'short',
			   'show_next_prev'=>TRUE,
			   'template'	  =>  '
			   {table_open}<table class="ci-calendar table-sm" border="0" cellpadding="" cellspacing="">{/table_open}

				{heading_row_start}<tr>{/heading_row_start}

				{heading_previous_cell}<th class="prevcell"><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
				{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
				{heading_next_cell}<th class="nextcell"><a href="{next_url}" >&gt;&gt;</a></th>{/heading_next_cell}

				{heading_row_end}</tr>{/heading_row_end}

				{week_row_start}<tr class="wk_nm">{/week_row_start}
				{week_day_cell}<td>{week_day}</td>{/week_day_cell}
				{week_row_end}</tr>{/week_row_end}

				{cal_row_start}<tr>{/cal_row_start}
				{cal_cell_start}<td class="day">{/cal_cell_start}

				{cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
				{cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

				{cal_cell_no_content}{day}{/cal_cell_no_content}
				{cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

				{cal_cell_blank}&nbsp;{/cal_cell_blank}

				{cal_cell_end}</td>{/cal_cell_end}
				{cal_row_end}</tr>{/cal_row_end}

				{table_close}</table>{/table_close}
			   '
             );
		$this->load->library('calendar',$prefs);
		
		$year = $this->uri->segment(2) ? $this->uri->segment(2) : date('Y');
		$month = $this->uri->segment(3) ? $this->uri->segment(3) : date('m');
		$day = date('d');
		
		$this->data['entry_for'] = date('Y/m/d');
		
		
		
		$data = array();
		$this->data['cal'] = $this->calendar->generate($year,$month,$data);
		$this->data['page_heading'] = 'Timesheet Entry';
        $this->data['maincontent'] = $this->load->view($this->data['view_dir'].'timesheet/index', $this->data, true);
        $this->load->view($this->data['view_dir'].'_layouts/layout_default', $this->data);
    }

}

?>
