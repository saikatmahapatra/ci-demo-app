<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
	<div class="col-md-4">
		<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'')) ?>
		<?php echo form_hidden('form_action', 'login'); ?>
			<?php echo isset($alert_message) ? $alert_message : ''; ?>
		
			<div class="form-group">
				<label for="user_email" class="required">Email/Username</label>
				<?php echo form_input(array('name' => 'user_email', 'value' => set_value('user_email'),'id' => 'name','class' => 'form-control','placeholder' => '','maxlength' => '100','autofocus' => true,));?>
				<?php echo form_error('user_email'); ?>
			</div>
			<div class="form-group">
				<label for="user_password" class="required">Password</label>
				<?php echo form_password(array('name' => 'user_password','value' => set_value('user_password'),'id' =>'user_password','placeholder' => '','class' => 'form-control','maxlength' => '16'));?>
				<?php echo form_error('user_password'); ?>
			</div>
			<!--<div class="form-group">
				<input id="remember" name="remember" type="checkbox" value="1">
				<label class="form-check-label" for="remember">Remember Password</label>
			</div>	-->
			<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Sign In','class' => 'btn btn-primary'));?>
			
			<?php form_close(); ?>

			<div class="mt-3">
				<a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/forgot_password');?>">Forgot Password?</a>
				<br>
				<!-- <a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/registration');?>">Create your account</a> -->
			</div>
				
		</div>
	</div>