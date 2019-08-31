<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
	<div class="col-md-8">
		<?php echo isset($alert_message) ? $alert_message : ''; ?>
		<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' =>'form')); ?>
		<?php echo form_hidden('form_action', 'insert'); ?>
		<div class="form-row">
			<div class="form-group col-md-6">									
				<label for="category_name" class="required">Category Name</label>
				<?php 
				echo form_input(array(
				'name' => 'category_name', 
				'value' => set_value('category_name'), 
				'id' => 'category_name', 
				'class' => 'form-control', 
				'placeholder' => ''
				));
				?>
				<?php echo form_error('category_name'); ?>
			</div>
			<div class="form-group col-md-6">									
				<label for="category_parent" class="">Category Root/Parent</label>
				<?php 
				echo form_dropdown('category_parent', $category_dropdown, set_value('category_parent'), array('class' => 'form-control',));
				?>
				<?php echo form_error('category_parent'); ?>
			</div>			
		</div>	
		<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => 'Submit','class' => 'btn btn-primary'));?>
		<a href="<?php echo base_url($this->router->directory.'category');?>" class="btn btn-light">Cancel</a>
		<?php echo form_close(); ?>
	</div>
</div>