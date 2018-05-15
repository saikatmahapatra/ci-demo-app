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
				<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'admin_login ci-from')) ?>
				<?php echo form_hidden('form_action', 'login'); ?>
				
					<div class="form-group">
						<label for="user_email">Email Address or Username</label>
						<?php echo form_input(array('name' => 'user_email', 'value' => set_value('user_email'),'id' => 'name','class' => 'form-control','placeholder' => 'Enter email or username','maxlength' => '100','autofocus' => true,));?>
						<?php echo form_error('user_email'); ?>
					</div>
					<div class="form-group">
						<label for="user_password">Password</label>
						<?php echo form_password(array('name' => 'user_password','value' => set_value('user_password'),'id' =>'user_password','placeholder' => 'Enter password','class' => 'form-control','maxlength' => '16'));?>
						<?php echo form_error('user_password'); ?>
					</div>
					<div class="form-group">
						<input id="remember" name="remember" type="checkbox" value="1">
						<label class="form-check-label" for="remember">Remember Password</label>
					</div>												
						<?php echo form_submit(array('name' => 'submit','value' => 'Login','class' => 'btn btn-primary btn-lg btn-block'));?>														
					
					<?php form_close(); ?>

					<div class="text-center">
						<a class="d-block small" href="<?php echo site_url('admin/user/forgot_password');?>">Forgot Password?</a>
					</div>
				</div>
			</div>
		</div>
	</div>