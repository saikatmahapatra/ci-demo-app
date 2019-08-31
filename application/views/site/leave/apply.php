<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
	<div class="col-md-12">
	<?php echo isset($alert_message) ? $alert_message : ''; ?>
	</div>	
</div>

<div class="row">
	<div class="col-md-12">
		<div class="alert <?php //echo ($system_msg_error_counter>0) ? 'alert-warning' : 'alert-info'; ?>">
			<ul class="">
			<?php
				foreach($system_msg as $key=>$val){
					?>
					<li class="<?php echo $val['css'];?>"><?php echo $val['txt'];?></li>
					<?php
				}
			?>
			</ul>
		</div>
		
		
		<h6>Leave Balance</h6>
		<div class="row mb-3">
			<div class="col-md-3">Casual Leave (CL) : <?php echo isset($leave_balance[0]['cl']) ? $leave_balance[0]['cl'] : '0.0'; ?></div>
			<div class="col-md-3">Privileged Leave (PL) : <?php echo isset($leave_balance[0]['pl']) ? $leave_balance[0]['pl'] : '0.0'; ?></div>
			<div class="col-md-3 d-none">Optional Leave (OL) : <?php echo isset($leave_balance[0]['ol']) ? $leave_balance[0]['ol'] : '0.0'; ?></div>			
		</div>

		<?php echo form_open(current_url(), array( 'method' => 'post','class'=>'ci-form','name' => '','id' => 'ci-form-leave',)); ?>
		<?php echo form_hidden('form_action', 'add'); ?>
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="leave_type" class="required">Leave Type</label>
				<?php
				echo form_dropdown('leave_type', $leave_type_arr, set_value('leave_type'), array(
					'class' => 'form-control',
				));
				?> 
				<?php echo form_error('leave_type'); ?>
			</div>
								
			<div class="form-group col-md-2">
				<label for="leave_from_date" class="required">From Date</label>
				<?php echo form_input(array('name' => 'leave_from_date','value' => set_value('leave_from_date'),'id' => 'leave_from_date','class' => 'form-control', 'placeholder'=>'dd-mm-yyyy', 'readonly'=>'readonly')); ?>
				<?php echo form_error('leave_from_date'); ?>
			</div>
				
			<div class="form-group col-md-2">
				<label for="leave_to_date" class="required">To Date</label>		
				<?php echo form_input(array('name' => 'leave_to_date','value' => set_value('leave_to_date'),'id' => 'leave_to_date','class' => 'form-control', 'placeholder'=>'dd-mm-yyyy', 'readonly'=>'readonly')); ?>
				<?php echo form_error('leave_to_date'); ?>
			</div>
		
			<div class="form-group col-md-5">
				<label for="leave_reason" class="required">Reason</label>
				<?php
				echo form_input(array(
					'name' => 'leave_reason',
					'value' => set_value('leave_reason'),
					'id' => 'leave_reason',
					'class' => 'form-control',
					'placeholder'=>'Briefly describe leave reason',
					'maxlength' => '100'
				));
				?>
				<?php echo form_error('leave_reason'); ?>				
			</div>
		</div>
		
		<button type="submit" <?php echo ($system_msg_error_counter >0 ) ? 'disabled="disabled"' : '';  ?> class="btn btn-primary">Submit</button>
		<?php echo form_close(); ?>
	</div>
</div>