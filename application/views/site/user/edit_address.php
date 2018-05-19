<?php $row = $address[0]; ?>
<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        
    </div>
</div><!--/.heading-container-->


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
    <div class="col-md-8">        
        <?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form','name' => 'edit_address', 'id' => 'edit_address'));?>
        <?php echo form_hidden('form_action', 'update_address'); ?>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="name" class="">Name</label>
					<?php echo form_input(array(
					'name' => 'name',
					'value' =>isset($row['name']) ? $row['name'] : set_value('name'),
					'id' => 'address',
					'placeholder'=>'Name',
					'class' => 'form-control',
					'maxlength' => '100',
					));
					?>
					<?php echo form_error('name'); ?>
				</div>
			
				<div class="form-group col-md-6">        
					<label for="phone1" class="">Phone Number</label>
					<?php echo form_input(array(
					'name' => 'phone1',
					'value' =>isset($row['phone1']) ? $row['phone1'] : set_value('phone1'),
					'id' => 'phone1',
					'placeholder'=>'Phone Number',
					'class' => 'form-control',
					'maxlength' => '10',
					));
					?>
					<?php echo form_error('phone1'); ?>
				</div>					
            </div>
			
			<div class="form-row">								
				<div class="form-group col-md-6">        
					<label for="zip" class="">Pincode</label>
					<?php
					echo form_input(array(
						'name' => 'zip',
						'value' => isset($row['zip']) ? $row['zip'] : set_value('zip'),
						'id' => 'zip',
						'placeholder' => 'Pincode',
						'class' => 'form-control',
						'maxlength' => '10',
					));
					?>
					<?php echo form_error('zip'); ?>
				</div>				
				<div class="form-group col-md-6">        
					<label for="locality" class="">Locality</label>
					<?php
					echo form_input(array(
						'name' => 'locality',
						'value' => isset($row['locality']) ? $row['locality'] : set_value('locality'),
						'id' => 'locality',
						'placeholder'=>'Locality',
						'class' => 'form-control',
					));
					?>
					<?php echo form_error('locality'); ?>
				</div>
			</div>
			
			<div class="form-group">        
				<label for="address" class="">Address (Apt. Area & Street)</label>
				<?php echo form_input(array(
				'name' => 'address',
				'value' =>isset($row['address']) ? $row['address'] : set_value('address'),
				'id' => 'address',
				'class' => 'form-control',
				'maxlength' => '100',
				));
				?>
				<?php echo form_error('address'); ?>
			</div>
			
		
			<div class="form-row">				
				<div class="form-group col-md-6">        
					<label for="city" class="">City/District/Town</label>
					<?php echo form_input(array(
					'name' => 'city',
					'value' =>isset($row['city']) ? $row['city'] : set_value('city'),
					'id' => 'city',
					'class' => 'form-control',
					'maxlength' => '30',
					'placeholder'=>'City/District/Town',
					));
					?>
					<?php echo form_error('city'); ?>
				</div>				
				<div class="form-group col-md-6">        
					<label for="state" class="">State</label>
					<?php 
					echo form_input(array(
					'name' => 'state',
					'value' => isset($row['state']) ? $row['state'] : set_value('state'),
					'id' => 'state',
					'placeholder'=>'State',
					'class' => 'form-control',
					'maxlength' => '30',
					));
					?>
					<?php echo form_error('state'); ?>
				</div>				
			</div>	
			
				
			<div class="form-row">				
				<div class="form-group col-md-6">        
					<label for="landmark" class="">Landmark (Optional)</label>
					<?php 
					echo form_input(array(
					'name' => 'landmark',
					'value' => isset($row['landmark']) ? $row['landmark'] :set_value('landmark'),
					'id' => 'landmark',
					'class' => 'form-control',
					'maxlength' => '100',
					'placeholder'=>'bus stop, railway, metro station',
					));
					?>
					<?php echo form_error('landmark'); ?>
				</div>				
				<div class="form-group col-md-6">        
					<label for="phone2" class="">Alternate Phone (Optional)</label>
					<?php echo form_input(array(
					'name' => 'phone2',
					'value' => isset($row['phone2']) ? $row['phone2'] :set_value('phone2'),
					'id' => 'phone2',
					'class' => 'form-control',
					'placeholder'=>'Alternate Phone',
					'maxlength' => '10',
					));
					?>
					<?php echo form_error('phone2'); ?>
				</div>				
			</div>
			
			<a href="<?php echo base_url($this->router->directory.'user/profile');?>" class="btn btn-secondary">Back</a>
			<?php echo form_submit(array('name' => 'submit','value' => 'Save','class' => 'btn btn-primary'));?>
        <?php echo form_close(); ?>
    </div>  
</div>