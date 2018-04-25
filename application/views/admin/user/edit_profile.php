<?php $user = $row[0]; ?>
<div class="row">
    <div class="col-md-4">
        <h3 class="dark-grey">Profile</h3>
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
        <?php
        echo form_open(current_url(), array(
            'method' => 'post', 'class'=>'ci-form',
            'name' => 'profile',
            'id' => 'profile',
        ));
        ?>
        <?php echo form_hidden('form_action', 'update_profile'); ?>
        <div class="form-group">                    
			<label for="user_firstname" class="">First Name <span class="star">*</span></label>
            <?php
            echo form_input(array(
                'name' => 'user_firstname',
                'value' => isset($user->user_firstname) ? $user->user_firstname : '',
                'id' => 'user_firstname',
                'class' => 'form-control',
                'maxlength' => 20,
            ));
            ?>
            <?php echo form_error('user_firstname'); ?>
        </div>

        <div class="form-group">                    
			<label for="" class="">Last Name<span class="star">*</span></label>
            <?php
            echo form_input(array(
                'name' => 'user_lastname',
                'value' => isset($user->user_lastname) ? $user->user_lastname : '',
                'id' => 'user_lastname',
                'class' => 'form-control',
                'maxlength' => 20,
            ));
            ?>
            <?php echo form_error('user_lastname'); ?>
        </div>

        <div class="form-group"> 
			<label for="" class="">Mobile Number</label>
            <?php
            echo form_input(array(
                'name' => 'user_phone',
                'value' => isset($user->user_phone1) ? $user->user_phone1 : '',
                'id' => 'user_phone',
                'class' => 'form-control',
                'maxlength' => 10,
            ));
            ?>
            <?php echo form_error('user_phone'); ?>
        </div>

        <div class="form-group">                    
			<label for="" class="">Postal Zip</label>
            <?php
            echo form_input(array(
                'name' => 'user_zipcode',
                'value' => isset($user->user_zipcode) ? $user->user_zipcode : '',
                'id' => 'user_zipcode',
                'class' => 'form-control',
                'maxlength' => 7,
            ));
            ?>
            <?php echo form_error('user_zipcode'); ?>
        </div>

        <div class="form-group">            
			<label for="user_gender" class="">Gender</label>
            <?php
            $radio_is_checked = $user->user_gender === 'M';
            echo form_radio(array(
                'name' => 'user_gender',
                'value' => 'M',
                'id' => 'm',
                'checked' => $radio_is_checked,
                'class' => ''
                    ), set_radio('user_gender', 'M')
            );
            ?>
			<label for="m" class="">Male</label>

            <?php
            $radio_is_checked = $user->user_gender === 'F';
            echo form_radio(array(
                'name' => 'user_gender',
                'value' => 'F',
                'id' => 'f',
                'checked' => $radio_is_checked,
                'class' => ''
                    ), set_radio('user_gender', 'F')
            );
            ?>
			<label for="f" class="">Female</label>
            <?php echo form_error('user_gender'); ?>
        </div>

        <div class="form-group">        
            <?php
            echo form_submit(array(
                'name' => 'submit',
                'value' => 'Update',
                'class' => 'btn btn-primary btn-block',
            ));
            ?>
        </div>
        <?php echo form_close(); ?>
    </div>    
</div>