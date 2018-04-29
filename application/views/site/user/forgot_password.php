<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Forgot your login password?</h1>               
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-12">
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
        <?php
        echo form_open(current_url(), array(
            'method' => 'post', 'class' => 'ci-form',
            'name' => '', 'id' => '',
        ));
        ?> 
        <?php echo form_hidden('form_action', 'reset_password'); ?>
        <div class="form-group">                    
			<label for="user_email" class="">Registered Email <span class="required">*</span></label>
            <?php
            echo form_input(array(
                'name' => 'user_email',
                'value' => set_value('user_email'),
                'id' => 'name',
                'maxlength' => '255',
                'class' => 'form-control',
                'autofocus' => '',
                'placeholder' => 'Email address',
                'data-hint' => 'Password reset link will be sent to this email id.'
            ));
            ?> 
            <?php echo form_error('user_email'); ?>
        </div>
        
        <div class=" form-group">
            <?php
            echo form_submit(array(
                'name' => 'submit',
                'value' => 'Submit',
                'class' => 'btn btn-primary',
            ));
            ?>
        </div>
        <?php echo form_close(); ?>
        <div class="text-left">
            <a href="<?php echo site_url('user/login');?>" class="d-block">Back to login</a>
        </div>
    </div>
</div>