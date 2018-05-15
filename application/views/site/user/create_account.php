<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        
    </div>
</div><!--/.heading-container-->

<div class="row">
    <div class="col-md-8">
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
        <?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form','name' => 'form','id' => 'form',));?>
        <?php echo form_hidden('form_action', 'create_account'); ?>        
        <div class="form-row">
			<div class="form-group col-md-6">                            
				<label for="user_firstname" class="">First Name <span class="required">*</span></label>
				<?php
				echo form_input(array(
					'name' => 'user_firstname',
					'value' => set_value('user_firstname'),
					'id' => 'user_firstname',
					'class' => 'form-control',
					'maxlength' => '30',
				));
				?>
				<?php echo form_error('user_firstname'); ?>
			</div>
            
			<div class="form-group col-md-6">                            
				<label for="user_lastname" class="">Last Name <span class="required">*</span></label>
				<?php
				echo form_input(array(
					'name' => 'user_lastname',
					'value' => set_value('user_lastname'),
					'id' => 'user_lastname',
					'class' => 'form-control',
					'maxlength' => '50',
				));
				?>
				<?php echo form_error('user_lastname'); ?>
			</div>
            </div>
       
            <div class="form-row">
                <div class="form-group col-md-6">
					<label for="user_email" class="">Email Address <span class="required">*</span></label>
                    <?php
                    echo form_input(array(
                        'name' => 'user_email',
                        'value' => set_value('user_email'),
                        'id' => 'user_email',
                        'class' => 'form-control',
                        'maxlength' => '254',
                    ));
                    ?> 
                    <?php echo form_error('user_email'); ?>
                </div>
                <div class="form-group col-md-6">                           
					<label for="user_mobile_phone1" class="">Mobile Number <span class="required">*</span></label>
                    <?php
                    echo form_input(array(
                        'name' => 'user_mobile_phone1',
                        'value' => set_value('user_mobile_phone1'),
                        'id' => 'user_mobile_phone1',
                        'maxlength' => '10',
                        'class' => 'form-control',
                    ));
                    ?>
                    <?php echo form_error('user_mobile_phone1'); ?>
                </div>
            </div>
			
            <div class="form-row">
                <div class="form-group col-md-6">                   
					<label for="user_password" class="">Password <span class="required">*</span></label>
                    <?php
                    echo form_password(array(
                        'name' => 'user_password',
                        'value' => set_value('user_password'),
                        'id' => 'user_password',
                        'class' => 'form-control',
                        'maxlength' => '15',
                    ));
                    ?> 
                    <?php echo form_error('user_password'); ?>
                </div>
				<div class="form-group col-md-6">
					<label for="user_password_confirm" class="">Confirm Password <span class="required">*</span></label>
                    <?php
                    echo form_password(array(
                        'name' => 'user_password_confirm',
                        'value' => set_value('user_password_confirm'),
                        'id' => 'user_password_confirm',
                        'class' => 'form-control',
                        'maxlength' => '15',
                    ));
                    ?> 
                    <?php echo form_error('user_password_confirm'); ?>
                </div>
            </div>

       
            <div class="form-row">
                <div class="form-group col-md-6">                            
					<label for="user_dob" class="">Date of Birth <span class="required">*</span></label>
                    <div class="">
                        <?php echo form_dropdown('dob_day', $day_arr, set_value('dob_day'), array('class' => 'form-control dob-inline',));?>
                        <?php echo form_dropdown('dob_month', $month_arr, set_value('dob_month'), array('class' => 'form-control dob-inline',));?>
                        <?php echo form_dropdown('dob_year', $year_arr, set_value('dob_year'), array('class' => 'form-control dob-inline'));?>
                    </div>
                    <?php echo form_error('dob_day'); ?>
                    <?php echo form_error('dob_month'); ?>
                    <?php echo form_error('dob_year'); ?>
                </div>
            
            <div class="form-group col-md-6">
			<label for="gender">Gender <span class="required">*</span></label>
			<div class="form-radio">
				<?php
				$radio_is_checked = $this->input->post('user_gender') === 'M';
				echo form_radio(array(
					'name' => 'user_gender',
					'value' => 'M',
					'id' => 'm',
					'checked' => $radio_is_checked,
					'class' => '',
						), set_radio('user_gender', 'M')
				);
				?>
				<label class="form-radio-label" for="m">Male</span></label>
				
				<?php
				$radio_is_checked = $this->input->post('user_gender') === 'F';
				echo form_radio(array(
					'name' => 'user_gender',
					'value' => 'F',
					'id' => 'f',
					'checked' => $radio_is_checked,
					'class' => ''
						), set_radio('user_gender', 'F')
				);
				?>
				<label class="form-radio-label" for="f">Female</span></label>
				
				<?php
				$radio_is_checked = $this->input->post('user_gender') === 'T';
				echo form_radio(array(
					'name' => 'user_gender',
					'value' => 'T',
					'id' => 't',
					'checked' => $radio_is_checked,
					'class' => ''
						), set_radio('user_gender', 'T')
				);
				?>
				<label class="form-radio-label" for="t">Trans-gender</span></label>
			</div>
			<?php echo form_error('user_gender'); ?>
		  </div>
        </div>

        <div class="row">
            <div class="col-md-12">               
				<label for="terms" class="">Uses Agreement Terms & Conditions <span class="required">*</span></label>
                <div class="checkbox"> 
                    <label class="label-normal">
                        <?php
                        $cb_is_checked = $this->input->post('terms') === 'accept';
                        echo form_checkbox('terms', 'accept', $cb_is_checked, array(
                            'id' => 'trems',
                            'class' => ''
                        ));
                        ?>
                        <span>By clicking Create an account, you agree to our Terms and that you have read our Data Policy, including our Cookie Use</span>
                        <span class="required">*</span>
                    </label>
                    <?php echo form_error('terms'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php
                    echo form_submit(array(
                        'name' => 'register',
                        'value' => 'Create an account',
                        'class' => 'btn btn-primary',
                    ));
                    ?>         
                </div>	
            </div>
        </div>
		<a href="<?php echo site_url('user/login');?>" class="">Already have an account? Login</a>
        <?php echo form_close(); ?>
    </div>
</div>