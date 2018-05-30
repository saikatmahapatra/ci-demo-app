<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        
    </div>
</div><!--/.heading-container-->

<div class="row">


    <div class="col-md-4">		
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
	
	
	
	<div class="col-md-8">		
		<?php
		// Show server side flash messages
		if (isset($alert_message)) {
			$html_alert_ui = '';                
			$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
			echo $html_alert_ui;
		}
		?>
		<?php echo form_open(current_url(), array( 'method' => 'post','class'=>'ci-form','name' => '','id' => 'ci-form-timesheet',)); ?>
		<?php echo form_hidden('form_action', 'add'); ?>		  
		<?php echo form_hidden('selected_date',set_value('selected_date')); ?>		  
			
		<div class="form-row">
			<div class="form-group col-md-5">
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
			
			<div class="form-group col-md-5">
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
			
			<div class="form-group col-md-2">
				<label for="timesheet_hours" class="">Hours <span class="required">*</span></label>		
				<?php
				echo form_input(array(
					'name' => 'timesheet_hours',
					'value' => set_value('timesheet_hours'),
					'id' => 'timesheet_hours',
					'class' => 'form-control',
					'placeholder' => 'Hours'
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
			'rows' => '1',
			'cols' => '4',
			'placeholder' => 'Description',
			'maxlength' => '200'
		));
		?>
		<?php echo form_error('timesheet_description'); ?>
		</div>
		  
		  <?php echo form_submit(array('name' => 'submit', 'value' => 'Submit', 'id' => 'btn_submit', 'class' => 'btn btn-primary')); ?> 
		<?php echo form_close(); ?>
	</div>
</div>