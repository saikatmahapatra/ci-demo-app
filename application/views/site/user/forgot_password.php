<div class="row heading-container">
    <div class="col-12">
        <h1 class="page-heading"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->
<div class="row">
	<div class="col-md-4">
			<p>Enter your registered email address to get reset password link.</p>			
			<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'')) ?>
			<?php
				// Show server side flash messages
				if (isset($alert_message)) {
					$html_alert_ui = '';                
					$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
					echo $html_alert_ui;
				}
			?>
			<?php echo form_hidden('form_action', 'forgot_password'); ?>
			
				<div class="form-group">
				<label for="user_email">Registered Email <span class="required">*</span></label>
					<?php echo form_input(array('name' => 'user_email','value' => set_value('user_email'),'id' => 'name','class'=> 'form-control','placeholder' => '','maxlength' => '100','autofocus' => true,));?>
					<?php echo form_error('user_email'); ?>
				</div>
				<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => '<i class="fa fa-fw fa-check-circle"></i> Submit','class' => 'btn btn-primary'));?>
				
				<?php form_close(); ?>
				<div class="mt-3">
					<a class="d-block" href="<?php echo base_url($this->router->directory.$this->router->class.'/login');?>">Back to login</a>
				</div>
				
		</div>
	</div>