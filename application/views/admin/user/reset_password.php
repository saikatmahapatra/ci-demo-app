<div class="card card-login mx-auto mt-3">
  <div class="card-header">Reset Password</div>
  <div class="card-body">
	<div class="text-center">
		<img class="logo-2x pb-3" src="<?php echo base_url('assets/src/img/logo.png');?>">
		<!-- <h6><?php echo $this->config->item('app_company_product');?></h6> -->
		<h1 class="page-heading"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
	</div>
	<?php
		// Show server side flash messages
		if (isset($alert_message)) {
			$html_alert_ui = '';
			$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
			echo $html_alert_ui;
		}
	?>


	<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'')) ?>
	<?php echo form_hidden('form_action', 'reset_password'); ?>
	<?php echo form_hidden('password_reset_key', $password_reset_key); ?>
	
		<div class="form-label-group">
			<?php
			echo form_input(array(
				'name' => 'user_email',
				'value' => set_value('user_email'),
				'id' => 'user_email',
				'class' => 'form-control',
				'placeholder' => 'Registered Email',
				'maxlength' => '255',
				'autofocus' => '',
			));
			?>
			<label for="user_email" class="">Registered Email</label>
			<?php echo form_error('user_email'); ?>
		</div>


		<div class="form-label-group">
			<?php
			echo form_password(array(
				'name' => 'user_new_password',
				'value' => set_value('user_new_password'),
				'id' => 'user_new_password',
				'placeholder' => 'New Password',
				'class' => 'form-control',
				'maxlength' => '16',
			));
			?>
			<label for="user_new_password" class="">New Password</label>
			<?php echo form_error('user_new_password'); ?>
		</div>

		<div class="form-label-group">
			<?php
			echo form_password(array(
				'name' => 'confirm_user_new_password',
				'value' => set_value('confirm_user_new_password'),
				'id' => 'confirm_user_new_password',
				'placeholder' => 'Confirm New Password',
				'class' => 'form-control',
				'maxlength' => '16',
			));
			?>
			<label for="confirm_user_new_password" class="">Confirm New Password</label>
			<?php echo form_error('confirm_user_new_password'); ?>
		</div>
		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => '<i class="fa fa-fw fa-check-circle"></i> Submit','class' => 'btn btn-lg btn-primary btn-block'));?>	
		<?php form_close(); ?>
			
		<div class="mt-3">
			<a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/login');?>">Back to login</a>
			<br>
			<a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/forgot_password');?>" class="">Resend password reset link</a>
		</div>
  </div><!--/.card-body-->
</div><!--/.card-->