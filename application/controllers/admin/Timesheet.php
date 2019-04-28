<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timesheet extends CI_Controller {

    var $data;
	var $id;
    var $sess_user_id;

    function __construct() {
        parent::__construct();
        //Loggedin user details
        $this->sess_user_id = $this->common_lib->get_sess_user('id');        
        
        //Render header, footer, navbar, sidebar etc common elements of templates
        $this->common_lib->init_template_elements('admin');
        
        // Load required js files for this controller
        $javascript_files = array(
            $this->router->class
        );
        $this->data['app_js'] = $this->common_lib->add_javascript($javascript_files);
		        
        $this->data['alert_message'] = NULL;
        $this->data['alert_message_css'] = NULL;
		
		//Check if any user logged in else redirect to login
        $is_logged_in = $this->common_lib->is_logged_in();
        if ($is_logged_in == FALSE) {
			$this->session->set_userdata('sess_post_login_redirect_url', current_url());
            redirect($this->router->directory.'user/login');
        }

        //Has logged in user permission to access this page or method?        
        $this->common_lib->is_auth(array(
            'default-super-admin-access',
            'default-admin-access'
        ));
		
		$this->load->model('timesheet_model');
		$this->id = $this->uri->segment(3);
		
		// Status flag indicator for showing in table grid etc
		$this->data['status_flag'] = array(
            'Y'=>array('text'=>'Active', 'css'=>'text-success', 'icon'=>'<i class="fa fa-circle text-success" aria-hidden="true"></i>'),
            'N'=>array('text'=>'Inactive', 'css'=>'text-warning', 'icon'=>'<i class="fa fa-circle text-warning" aria-hidden="true"></i>'),
            'A'=>array('text'=>'Archived', 'css'=>'text-danger', 'icon'=>'<i class="fa fa-circle text-danger" aria-hidden="true"></i>')
        );
		
		//Dropdown
		$this->data['project_arr'] = $this->timesheet_model->get_project_dropdown();
		$this->data['task_task_activity_type_array'] = $this->timesheet_model->get_activity_dropdown();
		$this->data['timesheet_hours'] = $this->timesheet_model->get_timesheet_hours_dropdown();
		
		//View Page Config
		$this->data['view_dir'] = 'site/'; // inner view and layout directory name inside application/view
		$this->data['page_heading'] = $this->router->class.' : '.$this->router->method;
        
    }

    function timesheet_stats(){		
		$year = $this->input->get_post('year') ? $this->input->get_post('year') : date('Y');
        $month = $this->input->get_post('month') ? $this->input->get_post('month') : date('m');
        $user_id =  $this->sess_user_id;		
		$response = array(
            'status' => 'init',
            'message' => '',
            'message_css' => '',
            'data' => array(),
        );		
		if($this->input->post('via')=='ajax'){			
			$result_array = $this->timesheet_model->get_timesheet_stats($year, $month, $user_id);			
			if($result_array['num_rows']>0){
				$response = array(
					'status' => 'ok',
					'message' => 'Records fetched',
					'message_css' => 'alert alert-success',
					'data' => $result_array,
				);
			}else{
				$response = array(
					'status' => 'ok',
					'message' => 'No records found',
					'message_css' => 'alert alert-danger',
					'data' => $result_array,
				);
			}
			echo json_encode($response);die();
		}else{
			die("404: Not Found");
		}
    }
    
    function report() {
        $this->data['project_arr'] = $this->timesheet_model->get_project_dropdown();		
        $this->data['user_arr'] = $this->timesheet_model->get_user_dropdown();		
				
        $this->data['alert_message'] = $this->session->flashdata('flash_message');
        $this->data['alert_message_css'] = $this->session->flashdata('flash_message_css');
        if($this->input->get_post('form_action') == 'search'){
            //print_r($_REQUEST); die();
            // Display using CI Pagination: Total filtered rows - check without limit query. Refer to model method definition		
            $filter_by_condition = array(
                'q_emp' => $this->input->get_post('q_emp'),
                'q_project' => $this->input->get_post('q_project'),
                'from_date' => $this->input->get_post('from_date'),
                'to_date' => $this->input->get_post('to_date')
            );
            if ($this->validate_search_form_data($filter_by_condition) == true) {
                $result_array = $this->timesheet_model->get_report_data(NULL, NULL, NULL, $filter_by_condition);
                $total_num_rows = $result_array['num_rows'];
                
                //Pagination config starts here		
                $per_page = 30;
                $config['uri_segment'] = 4; //which segment of your URI contains the page number
                $config['num_links'] = 2;
                $page = ($this->uri->segment($config['uri_segment'])) ? ($this->uri->segment($config['uri_segment'])-1) : 0;
                $offset = ($page*$per_page);
                $this->data['pagination_link'] = $this->common_lib->render_pagination($total_num_rows, $per_page);
                //Pagination config ends here
                

                // Data Rows - Refer to model method definition
                $result_array = $this->timesheet_model->get_report_data(NULL, $per_page, $offset, $filter_by_condition);
                $this->data['data_rows'] = $result_array['data_rows'];

                if($this->input->get_post('form_action_primary') == 'download'){
                    $this->download_to_excel();
                }
            }
        }

		$this->data['page_heading'] = 'Search Timesheet Report';
        $this->data['maincontent'] = $this->load->view('admin/'.$this->router->class.'/report', $this->data, true);
        $this->load->view('admin/_layouts/layout_default', $this->data);
    }

    
    function validate_search_form_data($data) {        
        $this->form_validation->set_data($data);
        //$this->form_validation->set_rules('q_emp', 'employee', 'required');
        $this->form_validation->set_rules('from_date', ' ', 'required');
        $this->form_validation->set_rules('to_date', ' ', 'required');
        $this->form_validation->set_error_delimiters('<div class="validation-error">', '</div>');
        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }

    
    function validate_days_diff(){
        $from_date = strtotime($this->common_lib->convert_to_mysql($this->input->post('from_date'))); // or your date as well
        $to_date = strtotime($this->common_lib->convert_to_mysql($this->input->post('to_date')));
        $datediff = ($to_date - $from_date);
        $no_day = round($datediff / (60 * 60 * 24));
        if($no_day >= 0 ){
            return true;
        }else{
            $this->form_validation->set_message('validate_days_diff', 'Invalid date range.');
            return false;
        }
    }


    function download_to_excel(){    
        $filter_by_condition = array(
            'q_emp' => $this->input->get_post('q_emp'),
            'q_project' => $this->input->get_post('q_project'),
            'from_date' => $this->input->get_post('from_date'),
            'to_date' => $this->input->get_post('to_date')
        );
        $result_array = $this->timesheet_model->get_report_data(NULL, NULL, NULL, $filter_by_condition);
        $data_rows = $result_array['data_rows'];

        $excel_heading = array(
            'A' => 'Sr No.',
            'B' => 'Date',
            'C' => 'Employee',
            'D' => 'Project',
            'E' => 'Activity',
            'F' => 'Hours',
            'G' => 'Description',
            'H' => 'Created On'
        );
        $this->data['xls_col'] = $excel_heading;
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        //name the worksheet
        $sheet->setTitle('TimeSheet');
        // echo '<pre>';
        // print_r($data_rows);
        // die();
        // read data to active sheet
        //$sheet->fromArray($data_rows);
        
        // Static Fields
        //$sheet->setCellValue('A1', 'Active Account');
        //$sheet->getStyle('A1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'a8ef81'))));
        
        //$sheet->setCellValue('A2', 'Inactive Account');
        //$sheet->getStyle('A2')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'f9eb7f'))));
        //End Static Fields

        $range = range('A', 'Z');
        $heading_row = 1;
        $index = 0;
        foreach ($excel_heading as $column => $heading_display) {
            $sheet->setCellValue($range[$index] . $heading_row, $heading_display);
            $index++;
        }


        $excel_row = 2;
        $serial_no = 1;
        foreach ($data_rows as $index => $row) {
            $sheet->setCellValue('A' . $excel_row, $serial_no);
            $sheet->setCellValue('B' . $excel_row, $this->common_lib->display_date($row['timesheet_date']));
            $sheet->setCellValue('C' . $excel_row, $row['user_firstname'].' '.$row['user_lastname']);
            $sheet->setCellValue('D' . $excel_row, $row['project_number'].'-'.$row['project_name']);
            $sheet->setCellValue('E' . $excel_row, $row['task_activity_name']);
            $sheet->setCellValue('F' . $excel_row, $row['timesheet_hours']);
            $sheet->setCellValue('G' . $excel_row, $row['timesheet_description']);            
            $sheet->setCellValue('H' . $excel_row, $row['timesheet_created_on']);            
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
        $sheet->getStyle('A1:H1')->applyFromArray($style_header);
        $sheet->getStyle('A1:H1')->getFont()->setSize(9);
        $sheet->getDefaultStyle()->getFont()->setSize(10);
        $sheet->getDefaultColumnDimension()->setWidth('17');

        $filename = 'Timesheet_' . date('dmY') . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        //echo "ok"; die();
    }

}

?>
