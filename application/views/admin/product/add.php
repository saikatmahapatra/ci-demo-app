<!--<h2 class="page-header"><?php echo isset($page_heading)? $page_heading:'Untitled Page'; ?></h2>-->
<?php echo $breadcrumbs; ?>
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
		<div class="card ">
			<div class="card-header">Add</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' =>'form')); ?>
						<?php echo form_hidden('form_action', 'insert'); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="product_name" class="">Product Name <span class="star">*</span></label>
									<?php echo form_input(array('name' => 'product_name', 'value' => set_value('product_name'),'id' => 'product_name', 'class' => 'form-control', 'minlength' => '3', 'maxlength' => '200',));?>
									<?php echo form_error('product_name'); ?>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">									
									<label for="category_id" class="">Select Product Category <span class="star">*</span></label>
									<?php echo form_dropdown('category_id', $category_dropdown, set_value('category_id'), array('class' =>'form-control',));?>
									<?php echo form_error('category_id'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">									
									<label for="product_mrp" class="">MRP <span class="star">*</span></label>
									<?php echo form_input(array('name' => 'product_mrp','value' => set_value('product_mrp'),'id' => 'product_mrp','class' => 'form-control numeric-decimal','minlength' => '1','maxlength' => '10',));?>
									<?php echo form_error('product_mrp'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">									
									<label for="product_price" class="">Price <span class="star">*</span></label>
									<?php echo form_input(array('name' => 'product_price', 'value' => set_value('product_price'),'id' => 'product_price','class' => 'form-control numeric-decimal','minlength' => '1','maxlength' => '10',));?>
									<?php echo form_error('product_price'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">									
									<label for="product_description" class="">Description <span class="star">*</span></label>
									<?php echo form_textarea(array('name' => 'product_description', 'value' => set_value('product_description'), 'id' => 'product_description','class' => 'form-control','rows' => '4','cols' => '50',));?>
									<?php echo form_error('product_description'); ?> 
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">									
									<label for="product_size" class="">Size</label>
									<?php echo form_input(array('name' => 'product_size','value' => set_value('product_size'),'id' => 'product_size','class' => 'form-control','minlength' => '1','maxlength' => '5',));?>
									<?php echo form_error('product_size'); ?>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">									
									<label for="product_color" class="">Color</label>
									<?php echo form_input(array('name' => 'product_color','value' => set_value('product_color'),'id' => 'product_color','class' => 'form-control'));?>
									<?php echo form_error('product_color'); ?>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="product_weight" class="">Weight</label>
									<?php echo form_input(array('name' => 'product_weight','value' => set_value('product_weight'),'id' => 'product_weight','class' => 'form-control'));?>
									<?php echo form_error('product_weight'); ?>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="product_height" class="">Height</label>
									<?php echo form_input(array('name' => 'product_height','value' => set_value('product_height'),'id' => 'product_height','class' => 'form-control'));?>
									<?php echo form_error('product_height'); ?>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="product_width" class="">Width</label>
									<?php echo form_input(array('name' => 'product_width','value' => set_value('product_width'),'id' => 'product_width','class' => 'form-control'));?>
									<?php echo form_error('product_width'); ?>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="product_length" class="">Length</label>
									<?php echo form_input(array('name' => 'product_length','value' => set_value('product_length'),'id' => 'product_length','class' => 'form-control'));?>
									<?php echo form_error('product_length'); ?>
								</div>
							</div>
						</div>
						<?php echo form_submit(array('name' => 'submit','value' => 'Submit','class' => 'btn btn-primary'));?>                             
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-md-12 -->
</div>