<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        
    </div>
</div><!--/.heading-container-->

<div class="row">


    <div class="col-md-3">		
		<?php echo $cal; ?>
		<?php echo form_error('selected_date'); ?>
		<div class="mt-3 small">
			<div class="d-inline-block"><span class="i-today pr-2 pl-2 m-1 text-white"></span>Today</div>
			<div class="d-inline-block"><span class="i-selected pr-2 pl-2 m-1"></span>Selected</div>
			<div class="d-inline-block"><span class="i-has-data pr-2 pl-2 m-1"></span>Task Filled</div>
			<div class="d-inline-block"><span class="i-leave pr-2 pl-2 m-1"></span>Leave</div>
			<div class="d-inline-block"><span class="i-holiday pr-2 pl-2 m-1"></span>Holiday</div>
		</div>
		<div class="mt-3">
			<div class="">Days worked: 10 days</div>
			<div class="">Hours worked: 80 hrs</div>
			<div class="">Average hours worked: 8 hrs/day</div>
		</div>
		
		<a class="text-centre" href="#"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download this month's timesheet</a>
	</div>
	
	
	
	<div class="col-md-9">		
		<nav>
			<div class="nav nav-tabs ci-nav-tab" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-add-tab" data-toggle="tab" href="#nav-add" role="tab" aria-controls="nav-add" aria-selected="true">Timesheet Entry</a>
				
				<a class="nav-item nav-link" id="nav-list-tab" data-toggle="tab" href="#nav-list" role="tab" aria-controls="nav-list" aria-selected="false">Tasks Filled</a>
			</div>
		</nav>
		
		<div class="tab-content" id="nav-tabContent">
		
			<div class="mt-3 tab-pane fade show active" id="nav-add" role="tabpanel" aria-labelledby="nav-add-tab">
			<?php
			// Show server side flash messages
			if (isset($alert_message)) {
				$html_alert_ui = '';                
				$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
				echo $html_alert_ui;
			}
			?>
			<?php echo form_open(current_url(), array( 'method' => 'post','class'=>'ci-form form-timesheet','name' => '','id' => 'ci-form-timesheet',)); ?>
			<?php echo form_hidden('form_action', 'add'); ?>		  
			<?php echo form_hidden('selected_date',set_value('selected_date')); ?>		  
				
			<div class="form-row">
				<div class="form-group col-md-4">
				  <label for="project_id" class="">Projects <span class="required">*</span></label>
					<?php
					$project_arr = array(
						''=>'Select',
						'1'=>'P001 - CRS - Fusion ACQ',
						'2'=>'P002 - CRS - Fusion SVC',						
					);
					echo form_dropdown('project_id', $project_arr, set_value('project_id'), array(
						'class' => 'form-control',
					));
					?> 
					<?php echo form_error('project_id'); ?>
				</div>
						
				<div class="form-group col-md-4">
				  <label for="activity_id" class="">Activity <span class="required">*</span></label>
					<?php
					$task_task_activity_type_array = array(
						''=>'Select',
						'1'=>'Coding & Development',
						'2'=>'Testing',						
						'3'=>'Planning',						
						'4'=>'Meeting',						
						'5'=>'Site Visit',						
						'6'=>'Co-oridination',						
						'7'=>'OOO-Out of Office',						
						'8'=>'KT',						
						'9'=>'Self Learning',						
						'10'=>'Traning',						
						'11'=>'Grooming',						
					);
					echo form_dropdown('activity_id', $task_task_activity_type_array, set_value('activity_id'), array(
						'class' => 'form-control',
					));
					?> 
					<?php echo form_error('activity_id'); ?>
				</div>
					
				<div class="form-group col-md-4">
					<label for="timesheet_hours" class="">Hours <span class="required">*</span></label>		
					<?php
					$timesheet_hours = array('' => 'Select',
											'0.5'=>'0.5 hrs',
											'1.0'=>'1.0 hrs',
											'1.5'=>'1.5 hrs',
											'2.0'=>'2.0 hrs',
											'2.5'=>'2.5 hrs',
											'3.0'=>'3.0 hrs',
											'3.5'=>'3.5 hrs',
											'4.0'=>'4.0 hrs',
											'4.5'=>'4.5 hrs',
											'5.0'=>'5.0 hrs',
											'5.5'=>'5.5 hrs',
											'6.0'=>'6.0 hrs',
											'6.5'=>'6.5 hrs',
											'7.0'=>'7.0 hrs',
											'7.5'=>'7.5 hrs',
											'8.0'=>'8.0 hrs',
											'8.5'=>'8.5 hrs',
											'9.0'=>'9.0 hrs',
											'9.5'=>'9.5 hrs');
					echo form_dropdown('timesheet_hours', $timesheet_hours, set_value('timesheet_hours'), array(
						'class' => 'form-control',
					));
					?>
					<?php echo form_error('timesheet_hours'); ?>
				</div>
			</div>		  
			 
			
			<div class="form-group">
			<label for="timesheet_description" class="">Description <span class="required">*</span></label>
			<?php
			echo form_textarea(array(
				'name' => 'timesheet_description',
				'value' => set_value('timesheet_description'),
				'id' => 'timesheet_description',
				'class' => 'form-control',
				'rows' => '2',
				'cols' => '4',
				'placeholder' => 'Write some short description...(200 characters)',
				'maxlength' => '200'
			));
			?>
			<?php echo form_error('timesheet_description'); ?>
			</div>
			
			  
			  <?php echo form_submit(array('name' => 'submit', 'value' => 'Submit', 'id' => 'btn_submit', 'class' => 'btn btn-primary')); ?> 
			<?php echo form_close(); ?>
			</div><!--/#nav-add-->
			
			<div class="mt-3 tab-pane fade" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
				
				<div class="table-responsive">
					<table id="timesheet-datatable" class="table table-sm w-100">
						<thead>
							<tr>
								<th scope="col">Date</th>
								<th scope="col">Project</th>
								<th scope="col">Activity</th>
								<th scope="col">Hours</th>
								<th scope="col">Status</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				
			</div><!--/#nav-list-->
		
		</div><!--/.tab-content #nav-tabContent-->
	</div>
</div>