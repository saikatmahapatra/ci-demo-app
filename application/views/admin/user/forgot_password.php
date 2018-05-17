<div class="row justify-content-center">
	<div class="col-12 col-sm-8 col-md-4">				
		<div class="card">
			<div class="card-header bg-default">
				<h4><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h4>
			</div>
			<div class="card-body">
				<?php
				// Show server side messages
				if (isset($alert_message)) {
				$html_alert_ui = '';
				$html_alert_ui.='<div class="alert-container">';
				$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable">';
				$html_alert_ui.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$html_alert_ui.=$alert_message;				
				$html_alert_ui.='</div>';
				$html_alert_ui.='</div>';
				echo $html_alert_ui;
				}
				?>
				<div class="text-center mt-4 mb-5">
					<h4>Forgot your password?</h4>
					<p>Enter your email address and we will send you instructions on how to reset your password.</p>
				</div>
				
				<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'ci-form')) ?>
				<?php echo form_hidden('form_action', 'forgot_password'); ?>
				
					<div class="form-group">
					<label for="user_email">Registered Email address</label>
						<?php echo form_input(array('name' => 'user_email','value' => set_value('user_email'),'id' => 'name','class'=> 'form-control','placeholder' => 'Email','maxlength' => '100','autofocus' => true,));?>
						<?php echo form_error('user_email'); ?>
					</div>
					<?php echo form_submit(array('name' => 'submit','value' => 'Submit','class' => 'btn btn-primary btn-block',));?>				
					
					
					<?php form_close(); ?>
					<div class="text-center">
						<a class="d-block small" href="<?php echo site_url('admin/user/login');?>">Back to login</a>
					</div>
				</div>
			</div>
		</div>
	</div>