<?php $row = $row[0]; ?>
<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>


<div class="row">        
    <div class="col-md-6">
        <?php echo isset($alert_message) ? $alert_message : ''; ?>
        <?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form',
            'name' => 'profile',
            'id' => 'profile',));
        ?>
        <?php echo form_hidden('form_action', 'update_profile'); ?>
        <?php echo form_hidden('user_email', $row['user_email']); ?>
			
			<?php /* ?>
            <div class="form-row">                
                    <div class="form-group col-md-6">                                
						<label for="user_firstname" class="required">First Name</label>
                        <?php
                        echo form_input(array(
                            'name' => 'user_firstname',
                            'value' => (isset($_POST['user_firstname']) ? set_value('user_firstname') : $row['user_firstname'])
                            'id' => 'user_firstname',
                            'class' => 'form-control',
                            'maxlength' => '30',
                        ));
                        ?>
                        <?php echo form_error('user_firstname'); ?>
                    </div>
                
                    <div class="form-group col-md-6">  
						<label for="user_lastname" class="required">Last Name</label>
                        <?php
                        echo form_input(array(
                            'name' => 'user_lastname',
                            'value' => (isset($_POST['user_lastname']) ? set_value('user_lastname') : $row['user_lastname']),
                            'id' => 'user_lastname',
                            'class' => 'form-control',
                            'maxlength' => '50',
                        ));
                        ?>
                        <?php echo form_error('user_lastname'); ?>
                    </div>                
            </div><!--/.row-->
			<?php */ ?>
			<div class="form-group">
				<label for="user_bio" class="">About me</label>
				<?php
				echo form_input(array(
					'name' => 'user_bio',
					'value' => (isset($_POST['user_bio']) ? set_value('user_bio') : $row['user_bio']),
					'id' => 'user_bio',
					'class' => 'form-control',						
					'maxlength' => '100',
				));
				?>
				<?php echo form_error('user_bio'); ?>
			</div>
                
			<?php /*?>	
            <div class="form-control">
                    <label class="required">Gender</label>
                    <div class="radio">  
                        <label class="label-normal">
                            <?php
                            $radio_is_checked = (isset($row['user_gender']) ? $row['user_gender'] : $this->input->post('user_gender')) === 'M';
                            echo form_radio(array(
                                'name' => 'user_gender',
                                'value' => 'M',
                                'id' => 'm',
                                'checked' => $radio_is_checked,
                                'class' => '',
								), set_radio('user_gender', 'M')
                            );
                            ?>
                            <span>Male</span>
                        </label>                    
                        <label class="label-normal">
                            <?php
                            $radio_is_checked = (isset($row['user_gender']) ? $row['user_gender'] : $this->input->post('user_gender')) === 'F';
                            echo form_radio(array(
                                'name' => 'user_gender',
                                'value' => 'F',
                                'id' => 'f',
                                'checked' => $radio_is_checked,
                                'class' => ''
								), set_radio('user_gender', 'F')
                            );
                            ?>
                            <span>Female</span>
                        </label>                    
                        <?php echo form_error('user_gender'); ?>
                    </div>
                </div>
				<?php */ ?>
			<div class="form-group">
				<label for="user_email_secondary" class="required">Email (Personal)</label>
				<?php
				echo form_input(array(
					'name' => 'user_email_secondary',
					'value' => (isset($_POST['user_email_secondary']) ? set_value('user_email_secondary') : $row['user_email_secondary']),
					'id' => 'user_email_secondary',
					'class' => 'form-control'
				));
				?>
				<?php echo form_error('user_email_secondary'); ?>
			</div>
			
            <div class="form-row">               
				<div class="form-group col-md-6">
					<label for="user_phone1" class="required">10-digit Mobile Number</label>
					<?php
					echo form_input(array(
						'name' => 'user_phone1',
						'value' => (isset($_POST['user_phone1']) ? set_value('user_phone1') : $row['user_phone1']),
						'id' => 'user_phone1',
						'class' => 'form-control',
						'maxlength' => '10',
						'minlength' => '10',
					));
					?>
					<?php echo form_error('user_phone1'); ?>
				</div>
				<div class="form-group col-md-6">
					<label for="user_phone2" class="">10-digit Mobile Number (Work)</label>
					<?php
					echo form_input(array(
						'name' => 'user_phone2',
						'value' => (isset($_POST['user_phone2']) ? set_value('user_phone2') : $row['user_phone2']),
						'id' => 'user_phone2',
						'class' => 'form-control',
						'maxlength' => '10',
						'minlength' => '10',
					));
					?>
					<?php echo form_error('user_phone2'); ?>
				</div>                
            </div><!--/.form-row-->
                    
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="user_blood_group" class="">Blood Group</label>
                    <?php
                    echo form_dropdown('user_blood_group', $blood_group, isset($_POST['user_blood_group']) ? set_value('user_blood_group') : $row['user_blood_group'], array(
                        'class' => 'form-control',
                    ));
                    ?> 
                    <?php echo form_error('user_blood_group'); ?>
                </div>
            </div><!--/.form-row-->

			<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Submit','class' => 'btn btn-primary'));?>
			<a href="<?php echo base_url($this->router->directory.$this->router->class.'/profile');?>" class="btn btn-light">Cancel</a>
        <?php echo form_close(); ?>
    </div><!--/.col-md-6-->
</div>