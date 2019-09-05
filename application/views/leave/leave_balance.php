<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row my-2">
	<div class="col-lg-12">
	<?php echo isset($alert_message) ? $alert_message : ''; ?>
	</div>	
</div>


<div class="row my-3">
	<div class="col-lg-12">
		<?php echo form_open(current_url(), array( 'method' => 'post','class'=>'ci-form','name' => '','id' => 'ci-form-leavebalance',)); ?>
			<?php echo form_hidden('form_action', 'leave_balance_update'); ?>
			<?php echo form_hidden('id', ''); ?>
			<div class="form-row">
				<div class="form-group col-lg-3">
					<label for="user_id" class="required">Employee</label>
					<?php
					echo form_dropdown('user_id', $user_dropdwon, set_value('user_id'), array(
						'class' => 'form-control',
						'id' => 'user_dropdown'
					));
					?> 
					<?php echo form_error('user_id'); ?>
				</div>
									
				<div class="form-group col-lg-2">
					<label for="cl" class="required">Casual Leave</label>
					<?php echo form_input(array('name' => 'cl','value' => set_value('cl'),'id' => 'cl','class' => 'form-control', 'placeholder'=>'', 'maxlength'=>'6')); ?>
					<?php echo form_error('cl'); ?>
				</div>
					
				<div class="form-group col-lg-2">
					<label for="pl" class="required">Priviledge Leave</label>		
					<?php echo form_input(array('name' => 'pl','value' => set_value('pl'),'id' => 'pl','class' => 'form-control', 'placeholder'=>'', 'maxlength'=>'6')); ?>
					<?php echo form_error('pl'); ?>
				</div>
			
				<div class="form-group col-lg-2">
					<label for="ol" class="required">Optional Leave</label>
					<?php echo form_input(array('name' => 'ol','value' => set_value('ol'),'id' => 'ol', 'class' => 'form-control','placeholder'=>'','maxlength' => '6'));
					?>
					<?php echo form_error('ol'); ?>				
				</div>

				<div class="col-lg-2 mt-4">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
			
			<?php echo form_close(); ?>
	</div>
</div>