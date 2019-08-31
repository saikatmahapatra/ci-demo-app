<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
	<div class="col-md-12">
		<?php echo isset($alert_message) ? $alert_message : ''; ?>
	</div>
	
	<div class="col-md-5">
		<?php echo form_open_multipart(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' => 'form'));?>
		<?php echo form_hidden('form_action', 'file_upload'); ?>
		<?php echo isset($upload_error_message) ? $upload_error_message : ''; ?>
		<div class="form-row">
			<div class="form-group col-md-12">
				<label for="upload_file_type_name" class="required">Document</label>
				<?php echo form_dropdown('upload_file_type_name', $arr_upload_file_type_name, set_value('upload_file_type_name'), array('class' => 'form-control','id' => 'upload_file_type_name',));?>
				<?php echo form_error('upload_file_type_name'); ?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-12">
				<label for="userfile" class="required">Select File</label>
				<?php echo form_upload(array('name' => 'userfile', 'id' => 'userfile','class' => 'form-control','aria-describedby'=>'docHelp'));?>				
				<?php echo form_error('userfile'); ?>
				<div id="docHelp" class="small form-text text-muted">Only png, jpg, jpeg, doc, docx, pdf files are allowed. File size should not larger than 2 MB</div>
			</div>		
		</div>
		
		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Upload','class' => 'btn btn-primary'));?>
		<?php echo form_close(); ?>
	</div>
	
	<div class="col-md-7">	
		
		<div class="table-responsive">
			<table class="table ci-table table-bordered table-sm">
				<thead class="thead-dark">
					<tr>
						<th scope="col">Document</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if (isset($all_uploads) && sizeof($all_uploads) > 0) {
					foreach ($all_uploads as $key => $upload) {
				?>
				<tr class="file-container" id="upload_grid_<?php echo $upload['id']; ?>">
						<?php
							$file_path = 'assets/uploads/'.$upload_related_to.'/docs/' . $id . '/' . $upload['upload_file_name'];
							if (file_exists(FCPATH . $file_path)) {
								$file_src = base_url($file_path);
								$btn_class='';
							} else {
								$file_src = '#';
								$btn_class='disabled';	
							}
						?>
						<td>
							<a href="<?php echo $file_src;?>" title="<?php echo $upload['upload_file_type_name'];?>" data-file-name="<?php echo $upload['upload_file_name']; ?>" class="<?php echo $btn_class;?>" target="_new"><?php echo $arr_upload_file_type_name[$upload['upload_file_type_name']]; ?></a>
						</td>
												
						<td>
							<a href="#" class="btn btn-outline-danger btn-sm btn-delete-file" data-confirmation="1" data-confirmation-message="Are you sure, you want to delete this?" data-upload_id="<?php echo $upload['id'];?>" title="Delete <?php echo $upload['upload_file_type_name']; ?>" data-path="<?php echo $file_path;?>"><i class="fa fa-fw fa-close"></i> Delete</a>
						</td>
					
				</tr>
				<?php } //foreach ?>
				<?php }else {?>
				<tr>
					<td colspan="2">No documents found.</td>
				</tr>
				<?php }?>
				</tbody>
			</table>			
		</div><!--/.table-responsive-->
	</div>
</div>