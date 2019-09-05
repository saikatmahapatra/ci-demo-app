<?php
$row = $rows[0];
?>
<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
	<div class="col-lg-8">
		<?php echo isset($alert_message) ? $alert_message : ''; ?>
		<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' =>'form')); ?>
		<?php echo form_hidden('form_action', 'update'); ?>
		<?php echo form_hidden('id', $row['id']); ?>
		
		<div class="form-row">							
			<div class="form-group col-lg-4">
				<label for="category_name" class="required">Name</label>
				<?php 
				echo form_input(array(
				'name' => 'category_name', 
				'value' => (isset($_POST['category_name']) ? set_value('category_name') : $row['category_name']),
				'id' => 'category_name', 
				'class' => 'form-control', 
				'placeholder' => '', 
				'title' => '', 
				'minlength' => '', 
				'maxlength' => '', 
				));
				?>
				<?php echo form_error('category_name'); 
				?>
			</div>
			<div class="form-group col-lg-4">
				<label for="category_parent" class="">Root/Parent</label>
				<?php echo form_dropdown('category_parent', $category_dropdown, (isset($_POST['category_parent']) ? set_value('category_parent') : $row['category_parent']), array('class' => 'form-control',));?>
				<?php echo form_error('category_parent'); ?>
			</div>
			
			<div class="form-group col-lg-4">
				<label for="category_status" class="">Status</label>									
				<?php 
				echo form_dropdown('category_status', array('Y'=>'Shown','N'=>'Hidden'), (isset($_POST['category_status']) ? set_value('category_status') : $row['category_status']), array(
				'class' => 'form-control'
				)); 
				?>
				<?php echo form_error('category_status'); ?>
			</div>
		</div>
		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Submit','class' => 'btn btn-primary'));?>
		<a href="<?php echo base_url($this->router->directory.'category');?>" class="btn btn-light">Cancel</a>
		<?php echo form_close(); ?>
	</div>
	<!-- /.col-lg-12 -->
</div>