<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
	<div class="col-md-9">
		<?php
		// Show server side flash messages
		if (isset($alert_message)) {
			$html_alert_ui = '';                
			$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
			echo $html_alert_ui;
		}
		?>
		<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' =>'form')); ?>
		<?php echo form_hidden('form_action', 'insert'); ?>

		
		<div class="form-row">
			<div class="form-group col-md-4">
				<label for="content_type" class="">Content Type <span class="required">*</span></label>
				<?php echo form_dropdown('content_type', $arr_content_type, set_value('content_type'), array('class' => 'form-control',));?>
				<?php echo form_error('content_type'); ?>
			</div>
			<div class="form-group col-md-8">
				<label for="content_title" class="">Content Title <span class="required">*</span></label>
				<?php echo form_input(array('name' => 'content_title', 'value' => set_value('content_title'), 'id' => 'content_title', 'class' => 'form-control', 'placeholder' => ''));?>
				<?php echo form_error('content_title'); ?>
			</div>		
		</div>
		
		<div class="form-group">
			<label for="content_text" class="">Content (HTML) <span class="required">*</span></label>
			<?php echo form_textarea(array('name' => 'content_text','value' => set_value('content_text'),'class' => 'form-control textarea','id' => 'content_text','rows' => '2','cols' => '50','placeholder' => '')); ?>
			<?php echo form_error('content_text'); ?>
		</div>
		<?php /*?>
		<div class="form-row">
			<div class="form-group col-md-4 d-none">									
				<label for="content_display_from_date" class="">Display from Date</label>
				<?php echo form_input(array('name' => 'content_display_from_date','value' => set_value('content_display_from_date'),'id' => 'content_display_from_date','class' => 'form-control cms-datepicker', 'placeholder' => 'dd-mm-yyyy','readonly'=>true));?>
				<?php echo form_error('content_display_from_date'); ?>
			</div>
		
			<div class="form-group col-md-4 d-none">									
				<label for="content_display_to_date" class="">Display to Date</label>
				<?php echo form_input(array('name' => 'content_display_to_date','value' => set_value('content_display_to_date'),'class' => 'form-control cms-datepicker','id' => 'content_display_to_date','placeholder' => 'dd-mm-yyyy','readonly'=>true));?>
				<?php echo form_error('content_display_to_date'); ?>
			</div>	
			
		</div>
		<?php */ ?>
		<?php /* ?>
		<div class="form-row d-none">			
			<div class="form-group col-md-4">									
				<label for="content_meta_keywords" class="">Meta Keywords</label>
				<?php echo form_input(array('name' => 'content_meta_keywords','value' => set_value('content_meta_keywords'),'id' => 'content_meta_keywords','class' => 'form-control', 'placeholder' => '')); ?>
				<?php echo form_error('content_meta_keywords'); ?>
			</div>
		
			<div class="form-group col-md-4">									
				<label for="content_meta_description" class="">Meta Description</label>
				<?php echo form_input(array('name' => 'content_meta_description','value' => set_value('content_meta_description'),'id' => 'content_meta_description','class' => 'form-control', 'placeholder' => ''));?>
				<?php echo form_error('content_meta_description'); ?>
			</div>
		
			<div class="form-group col-md-4">									
				<label for="content_meta_author" class="">Meta Author</label>
				<?php echo form_input(array('name' => 'content_meta_author','value' => set_value('content_meta_author'),'class' => 'form-control','id' => 'content_meta_author','placeholder' => ''));?>
				<?php echo form_error('content_meta_author'); ?>
			</div>			
		</div>
		<?php */ ?>

		<div class="form-row">
			<div class="form-group col-md-12">									
				<label for="content_status" class="">Display Status <span class="required">*</span></label>
				<?php //echo form_dropdown('content_status', array('Y'=>'Yes','N'=>'No'), set_value('content_status'), array('class' => 'form-control')); ?>
				<?php //echo form_error('content_status'); ?>

				<!-- <div class=""> -->
					<div class="custom-control custom-radio custom-control-inline">
						<?php
							$radio_is_checked = $this->input->post('content_status') == 'Y';
							echo form_radio(array('name' => 'content_status','value' => 'Y','id' => 'Y','checked' => $radio_is_checked,'class' => 'custom-control-input'), set_radio('content_status', 'Y'));
						?>
						<label class="custom-control-label" for="Y">Publish</span></label>
					</div>
					
					<div class="custom-control custom-radio custom-control-inline">
						<?php
							$radio_is_checked = $this->input->post('content_status') == 'N';
							echo form_radio(array('name' => 'content_status', 'value' => 'N', 'id' => 'N', 'checked' => $radio_is_checked, 'class' => 'custom-control-input'), set_radio('content_status', 'N'));
						?>
						<label class="custom-control-label" for="N">Unpublish</span></label>
					</div>								
				<!-- </div> -->
				<small id="emailHelp" class="form-text text-muted">If you unpublish this, it will not displayed for public user(employees)</small>
				<?php echo form_error('content_status'); ?>
			</div>		
		</div>


		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Submit','class' => 'btn btn-primary'));?>
		<a href="<?php echo base_url($this->router->directory.$this->router->class);?>" class="ml-2 btn btn-secondary">Cancel</a>
		<?php echo form_close(); ?>
	</div>
</div>