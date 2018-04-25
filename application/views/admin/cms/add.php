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
							<div class="col-md-6">
								<div class="form-group">									
									<label for="pagecontent_type" class="">Content Type <span class="star">*</span></label>
									<?php echo form_dropdown('pagecontent_type', $arr_content_type, set_value('pagecontent_type'), array('class' => 'form-control',));?>
									<?php echo form_error('pagecontent_type'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">									
									<label for="pagecontent_title" class="">Content Title <span class="star">*</span></label>
									<?php echo form_input(array('name' => 'pagecontent_title', 'value' => set_value('pagecontent_title'), 'id' => 'pagecontent_title', 'class' => 'form-control', 'placeholder' => '', 'title' => '','minlength' => '','maxlength' => '', ));?>
									<?php echo form_error('pagecontent_title'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">									
									<label for="pagecontent_text" class="">Content(HTML) <span class="star">*</span></label>
									<?php echo form_textarea(array('name' => 'pagecontent_text','value' => set_value('pagecontent_text'),'class' => 'form-control textarea','id' => 'pagecontent_text','rows' => '4','cols' => '50','placeholder' => '','title' => '','minlength' => '','maxlength' => '', 'style'=>'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' )); ?>
									<?php echo form_error('pagecontent_text'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">									
									<label for="pagecontent_meta_keywords" class="">Meta Keywords</label>
									<?php echo form_input(array('name' => 'pagecontent_meta_keywords','value' => set_value('pagecontent_meta_keywords'),'id' => 'pagecontent_meta_keywords','class' => 'form-control','rows' => '4','cols' => '50','placeholder' => '', 'title' => '','minlength' => '','maxlength' => '',)); ?>
									<?php echo form_error('pagecontent_meta_keywords'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">									
									<label for="pagecontent_meta_description" class="">Meta Description</label>
									<?php echo form_input(array('name' => 'pagecontent_meta_description','value' => set_value('pagecontent_meta_description'),'id' => 'pagecontent_meta_description','class' => 'form-control','rows' => '4','cols' => '50','placeholder' => '','title' => '', 'minlength' => '10','maxlength' => '200',));?>
									<?php echo form_error('pagecontent_meta_description'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">									
									<label for="pagecontent_meta_author" class="">Meta Author</label>
									<?php echo form_input(array('name' => 'pagecontent_meta_author','value' => set_value('pagecontent_meta_author'),'class' => 'form-control','id' => 'pagecontent_meta_author','placeholder' => '','title' => '',));?>
									<?php echo form_error('pagecontent_meta_author'); ?>
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