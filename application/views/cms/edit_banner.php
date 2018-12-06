<?php
$row = $rows[0];
?>
<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<div class="row heading-container">
    <div class="col-12">
        <h1 class="h3 mb-3 font-weight-normal"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<div class="row">
	<div class="col-md-6">
		<?php
		// Show server side flash messages
		if (isset($alert_message)) {
			$html_alert_ui = '';                
			$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
			echo $html_alert_ui;
		}
		?>
		<?php echo form_open_multipart(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' =>'form')); ?>
		<?php echo form_hidden('form_action', 'update'); ?>
		<?php echo form_hidden('upload_file_type_name', 'slider_img'); ?>
		<?php echo form_hidden('id', $row['id']); ?>
		<?php echo form_hidden('upload_file_name', $row['upload_file_name']); ?>

		
		<div class="form-row">			
			<div class="form-group col-md-12">
				<?php
					$img_src = "";
					$default_path = "assets/src/img/no-image.png";
					if(isset($row['upload_file_name'])){					
						$banner_img = "assets/uploads/banner_img/".$row['upload_file_name'];					
						if (file_exists(FCPATH . $banner_img)) {
							$img_src = $banner_img;
						}else{
							$img_src = $default_path;
						}
					}else{
						$img_src = $default_path;
					}
				?>
				<img src="<?php echo base_url($img_src);?>" class="img banner-img-sm">
			</div>		
		</div>
		
		<div class="form-row">			
			<div class="form-group col-md-12">									
				<label for="userfile" class="">Image (Only 1200x300 dimensions are allowed)</label>
				<?php
					echo form_upload(array(
						'name' => 'userfile',
						'id' => 'userfile',
						'class' => 'form-control field-help',
						'aria-describedby'=>'uploaderHelpBlock',
						'data-help-text' => 'Only .jpg, .jpeg, .png are allowed',
						'data-help-text-class' => 'p-1 bg-light'
					));
					?>
				<?php echo form_error('userfile'); ?>
			</div>		
		</div>
		
		<div class="form-row">
			<div class="form-group col-md-12">									
				<label for="upload_text_1" class="">Text Line 1 (Optional)</label>
				<?php echo form_input(array('name' => 'upload_text_1', 'value' => (isset($_POST['upload_text_1']) ? set_value('upload_text_1') : $row['upload_text_1']), 'id' => 'upload_text_1', 'class' => 'form-control', 'placeholder' => ''));?>
				<?php echo form_error('upload_text_1'); ?>
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-group col-md-12">									
				<label for="upload_text_2" class="">Text Line 2 (Optional)</label>
				<?php echo form_input(array('name' => 'upload_text_2', 'value' => (isset($_POST['upload_text_2']) ? set_value('upload_text_2') : $row['upload_text_2']), 'id' => 'upload_text_2', 'class' => 'form-control', 'placeholder' => ''));?>
				<?php echo form_error('upload_text_2'); ?>
			</div>
		</div>
		

		<div class="form-row">
			<div class="form-group col-md-12">									
				<label for="upload_status" class="">Status <span class="required">*</span></label>
				<?php //echo form_dropdown('upload_status', array('Y'=>'Yes','N'=>'No'), (isset($_POST['upload_status']) ? set_value('upload_status') : $row['upload_status']), array('class' => 'form-control')); ?>
				  	<!--<div class="">-->
						<div class="custom-control custom-radio custom-control-inline">
							<?php
								$radio_is_checked = isset($_POST['upload_status']) ? $_POST['upload_status'] == 'Y' : ($row['upload_status'] == 'Y');

								echo form_radio(array('name' => 'upload_status','value' => 'Y','id' => 'Y','checked' => $radio_is_checked,'class' => 'custom-control-input'), set_radio('upload_status', 'Y'));
							?>
							<label class="custom-control-label" for="Y">Publish</span></label>
						</div>
						
						<div class="custom-control custom-radio custom-control-inline">
							<?php
								$radio_is_checked = isset($_POST['upload_status']) ? $_POST['upload_status'] == 'N' : ($row['upload_status'] == 'N');

								echo form_radio(array('name' => 'upload_status', 'value' => 'N', 'id' => 'N', 'checked' => $radio_is_checked, 'class' => 'custom-control-input'), set_radio('upload_status', 'N'));
							?>
							<label class="custom-control-label" for="N">Unpublish</span></label>
						</div>								
					<!--</div>-->
					<?php echo form_error('upload_status'); ?>
			</div>
		</div>


		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => '<i class="fa fa-fw fa-check-circle"></i> Submit','class' => 'btn btn-primary'));?>
		<a href="<?php echo base_url($this->router->directory.$this->router->class.'/manage_banner');?>" class="ml-2 btn btn-secondary"><i class="fa fa-fw fa-times-circle"></i> Cancel</a>
		<?php echo form_close(); ?>
	</div>
</div>