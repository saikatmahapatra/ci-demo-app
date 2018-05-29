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
	</div>
	<div class="col-md-8 d-none">
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
			
		<div class="form-row">
			<div class="form-group col-md-5">
			  <label for="project" class="">Projects <span class="required">*</span></label>
				<?php
				$project_arr = array(
					''=>'Select',
					'1'=>'P001 - CRS - Fusion ACQ',
					'2'=>'P002 - CRS - Fusion SVC',						
				);
				echo form_dropdown('project', $project_arr, set_value('project'), array(
					'class' => 'form-control',
				));
				?> 
				<?php echo form_error('project'); ?>
			</div>
			
			<div class="form-group col-md-5">
			  <label for="task_type" class="">Activity <span class="required">*</span></label>
				<?php
				$task_type_arr = array(
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
				echo form_dropdown('task_type_arr', $task_type_arr, set_value('task_type_arr'), array(
					'class' => 'form-control',
				));
				?> 
				<?php echo form_error('task_type_arr'); ?>
			</div>
			
			<div class="form-group col-md-2">
				<label for="hours" class="">Hours <span class="required">*</span></label>		
				<?php
				echo form_input(array(
					'name' => 'hours',
					'value' => set_value('hours'),
					'id' => 'hours',
					'class' => 'form-control',
					'placeholder' => 'Hours'
				));
				?>
				<?php echo form_error('hours'); ?>
			</div>
		</div>		  
		  
		<div class="form-group">
		<label for="description" class="">Description <span class="required">*</span></label>
		<?php
		echo form_textarea(array(
			'name' => 'description',
			'value' => set_value('description'),
			'id' => 'description',
			'class' => 'form-control',
			'rows' => '1',
			'cols' => '4',
			'placeholder' => 'Description',
			'minlength' => '10',
			'maxlength' => '200'
		));
		?>
		<?php echo form_error('description'); ?>
		</div>
		  
		  <?php echo form_submit(array('name' => 'submit', 'value' => 'Submit', 'id' => 'btn_submit', 'class' => 'btn btn-primary')); ?> 
		<?php echo form_close(); ?>
	</div>
</div>