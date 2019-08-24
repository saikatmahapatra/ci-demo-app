<?php
$row = $rows[0];
?>
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
		<?php echo form_hidden('form_action', 'update'); ?>
		<?php echo form_hidden('id', $row['id']); ?>
		
		<div class="form-row">		
			<div class="form-group col-md-12">
				
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-group col-md-4">
				<label for="content_type" class="required">Content Type</label>
				<?php echo form_dropdown('content_type', $arr_content_type, (isset($_POST['content_type']) ? set_value('content_type') : $row['content_type']), array('class' => 'form-control',));?>
				<?php echo form_error('content_type'); ?>
			</div>		
			<div class="form-group col-md-8">
				<label for="content_title" class="required">Content Title</label>
				<?php echo form_input(array('name' => 'content_title', 'value' => (isset($_POST['content_title']) ? set_value('content_title') : $row['content_title']), 'id' => 'content_title', 'class' => 'form-control', 'placeholder' => ''));?>
				<?php echo form_error('content_title'); ?>
			</div>
		</div>
		
		
		
		<div class="form-group">
			<label for="content_text" class="required">Description</label>
			<?php echo form_textarea(array('name' => 'content_text','value' => (isset($_POST['content_text']) ? set_value('content_text') : $row['content_text']),'class' => 'form-control textarea','id' => 'content_text','rows' => '2','cols' => '50','placeholder' => '')); ?>
			<?php echo form_error('content_text'); ?>
		</div>
		
		<?php /* ?>
		<div class="form-row">			
			<div class="form-group col-md-4 d-none">
				<label for="content_display_from_date" class="">Display from date</label>
				<?php echo form_input(array('name' => 'content_display_from_date','value' => (isset($_POST['content_display_from_date']) ? set_value('content_display_from_date') : $row['content_display_from_date']),'id' => 'content_display_from_date','class' => 'form-control cms-datepicker', 'placeholder' => 'dd-mm-yyyy','readonly'=>true));?>
				<?php echo form_error('content_display_from_date'); ?>
			</div>		
			<div class="form-group col-md-4 d-none">
				<label for="content_display_to_date" class="">Display to Date</label>
				<?php echo form_input(array('name' => 'content_display_to_date','value' => (isset($_POST['content_display_to_date']) ? set_value('content_display_to_date') : $row['content_display_to_date']),'class' => 'form-control cms-datepicker','id' => 'content_display_to_date','placeholder' => 'dd-mm-yyyy','readonly'=>true));?>
				<?php echo form_error('content_display_to_date'); ?>
			</div>			
		</div>
		<?php */ ?>
		
		<?php /* ?>
		<div class="form-row">		
			<div class="form-group col-md-4">
				<label for="content_meta_keywords" class="">Meta Keywords</label>
				<?php echo form_input(array('name' => 'content_meta_keywords','value' => (isset($_POST['content_meta_keywords']) ? set_value('content_meta_keywords') : $row['content_meta_keywords']),'id' => 'content_meta_keywords','class' => 'form-control', 'placeholder' => '')); ?>
				<?php echo form_error('content_meta_keywords'); ?>
			</div>
		
			<div class="form-group col-md-4">
				<label for="content_meta_description" class="">Meta Description</label>
				<?php echo form_input(array('name' => 'content_meta_description','value' => (isset($_POST['content_meta_description']) ? set_value('content_meta_description') : $row['content_meta_description']),'id' => 'content_meta_description','class' => 'form-control', 'placeholder' => ''));?>
				<?php echo form_error('content_meta_description'); ?>
			</div>
		
			<div class="form-group col-md-4">
				<label for="content_meta_author" class="">Meta Author</label>
				<?php echo form_input(array('name' => 'content_meta_author','value' => (isset($_POST['content_meta_author']) ? set_value('content_meta_author') : $row['content_meta_author']),'class' => 'form-control','id' => 'content_meta_author','placeholder' => ''));?>
				<?php echo form_error('content_meta_author'); ?>
			</div>		
		</div>
		<?php */ ?>
		<?php /* ?>
		<div class="form-row">		
			<div class="form-group col-md-3">									
				<label for="content_status" class="">Publish</label>
				<?php echo form_dropdown('content_status', array('Y'=>'Yes','N'=>'No'), (isset($_POST['content_status']) ? set_value('content_status') : $row['content_status']), array('class' => 'form-control')); ?>
				<?php echo form_error('content_status'); ?>
			</div>	
			
			<div class="form-group col-md-3">									
				<label for="content_archived" class="">Archived</label>
				<?php echo form_dropdown('content_archived', array('Y'=>'Yes','N'=>'No'), (isset($_POST['content_archived']) ? set_value('content_archived') : $row['content_archived']), array('class' => 'form-control'));?>
				<?php echo form_error('content_archived'); ?>
			</div>	
			
		</div>
		<?php */ ?>

		<div class="form-row">
			<div class="form-group col-md-12">									
				<label for="content_status" class="required">Status</label>
				<?php //echo form_dropdown('content_status', array('Y'=>'Yes','N'=>'No'), (isset($_POST['content_status']) ? set_value('content_status') : $row['content_status']), array('class' => 'form-control')); ?>
				  	<!--<div class="">-->
						<div class="custom-control custom-radio custom-control-inline">
							<?php
								$radio_is_checked = isset($_POST['content_status']) ? $_POST['content_status'] == 'Y' : ($row['content_status'] == 'Y');

								echo form_radio(array('name' => 'content_status','value' => 'Y','id' => 'Y','checked' => $radio_is_checked,'class' => 'custom-control-input'), set_radio('content_status', 'Y'));
							?>
							<label class="custom-control-label" for="Y">Publish</span></label>
						</div>
						
						<div class="custom-control custom-radio custom-control-inline">
							<?php
								$radio_is_checked = isset($_POST['content_status']) ? $_POST['content_status'] == 'N' : ($row['content_status'] == 'N');

								echo form_radio(array('name' => 'content_status', 'value' => 'N', 'id' => 'N', 'checked' => $radio_is_checked, 'class' => 'custom-control-input'), set_radio('content_status', 'N'));
							?>
							<label class="custom-control-label" for="N">Unpublish</span></label>
						</div>								
					<!--</div>-->
					<small id="emailHelp" class="form-text text-muted">If you unpublish this, it will not displayed for public user(employees)</small>
					<?php echo form_error('content_status'); ?>
			</div>
		</div>

		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Submit','class' => 'btn btn-primary'));?>
		
		<a href="<?php echo base_url($this->router->directory.$this->router->class);?>" class="btn btn-light">Cancel</a>                             
		<?php echo form_close(); ?>
	</div>
</div>