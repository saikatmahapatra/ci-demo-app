<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
	<div class="col-lg-4">
			<p>Enter your registered email address to get reset password link.</p>			
			<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'')) ?>
			<?php echo isset($alert_message) ? $alert_message : ''; ?>
			<?php echo form_hidden('form_action', 'forgot_password'); ?>
			
				<div class="form-group">
				<label for="user_email" class="required">Registered Email</label>
					<?php echo form_input(array('name' => 'user_email','value' => set_value('user_email'),'id' => 'name','class'=> 'form-control','placeholder' => '','maxlength' => '100','autofocus' => true,));?>
					<?php echo form_error('user_email'); ?>
				</div>
				<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Submit','class' => 'btn btn-primary'));?>
				
				<?php form_close(); ?>
				<div class="mt-3">
					<a class="d-block" href="<?php echo base_url($this->router->directory.$this->router->class.'/login');?>">Back to login</a>
				</div>
				
		</div>
	</div>