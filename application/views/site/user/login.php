<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Login</h1>               
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
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
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-md-4">
        <?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form', 'name' => 'form', 'id' => 'form')); ?> 
        <?php echo form_hidden('form_action', 'login'); ?>
        <div class="form-group">
            <label for="user_email" class="">Email <span class="star">*</span></label>
            <?php
            echo form_input(array(
                'name' => 'user_email',
                'value' => set_value('user_email'),
                'id' => 'user_email',
                'class' => 'form-control',
                'maxlength' => '255',
            ));
            ?> 
            <?php echo form_error('user_email'); ?>
        </div>
        <div class="form-group">            
			<label for="user_password" class="">Password <span class="star">*</span></label>
            <?php
            echo form_password(array(
                'name' => 'user_password',
                'value' => set_value('user_password'),
                'id' => 'user_password',
                'class' => 'form-control',
                'maxlength' => '16',
            ));
            ?> 
            <?php echo form_error('user_password'); ?>
        </div>            
        <!--<div class="checkbox">
            <label>
                <input type="checkbox" name="remember" id="remember"> Remember login
            </label>
            <p class="help-block">(if this is a private computer)</p>
        </div>-->
        <div class="form-group">
            <?php
            echo form_submit(array(
                'name' => 'submit',
                'value' => 'Log In',
                'class' => 'btn btn-primary',
            ));
            ?>            

        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<a href="<?php echo site_url('user/forgot_password');?>" class="">Forgot your password?</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<a href="<?php echo site_url('user/create_account');?>" class="">Create an account</a>
    </div>
</div>