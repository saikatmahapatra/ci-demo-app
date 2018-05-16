<?php
$row = $rows[0];
?>
<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        <?php echo $breadcrumbs; ?>
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
		<div class="card ">
			<div class="card-header">Edit</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<?php echo form_open(current_url(), array('method' => 'post', 'class'=>'ci-form','name' => 'myform','id' => 'myform','role' =>'form')); ?>
						<?php echo form_hidden('form_action', 'update'); ?>
						<?php echo form_hidden('id', $row['id']); ?>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="pagecontent_type" class="">Content Type <span class="required">*</span></label>							
									<?php echo form_dropdown('pagecontent_type', $arr_content_type, (isset($_POST['pagecontent_type']) ? set_value('pagecontent_type') : $row['pagecontent_type']), array('class' => 'form-control',));?>
									<?php echo form_error('pagecontent_type'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="pagecontent_title" class="">Content Title <span class="required">*</span></label>
									<?php echo form_input(array('name' => 'pagecontent_title', 'value' => (isset($_POST['pagecontent_title']) ? set_value('pagecontent_title') : $row['pagecontent_title']), 'id' => 'pagecontent_title', 'class' => 'form-control', 'placeholder' => ''));?>
									<?php echo form_error('pagecontent_title'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="pagecontent_text" class="">Content(HTML) <span class="required">*</span></label>
									<?php echo form_textarea(array('name' => 'pagecontent_text','value' => (isset($_POST['pagecontent_text']) ? set_value('pagecontent_text') : $row['pagecontent_text']),'class' => 'form-control textarea','id' => 'pagecontent_text','rows' => '2','cols' => '50','placeholder' => '')); ?>
									<?php echo form_error('pagecontent_text'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="pagecontent_meta_keywords" class="">Meta Keywords</label>
									<?php echo form_input(array('name' => 'pagecontent_meta_keywords','value' => (isset($_POST['pagecontent_meta_keywords']) ? set_value('pagecontent_meta_keywords') : $row['pagecontent_meta_keywords']),'id' => 'pagecontent_meta_keywords','class' => 'form-control', 'placeholder' => '')); ?>
									<?php echo form_error('pagecontent_meta_keywords'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="pagecontent_meta_description" class="">Meta Description</label>
									<?php echo form_input(array('name' => 'pagecontent_meta_description','value' => (isset($_POST['pagecontent_meta_description']) ? set_value('pagecontent_meta_description') : $row['pagecontent_meta_description']),'id' => 'pagecontent_meta_description','class' => 'form-control', 'placeholder' => ''));?>
									<?php echo form_error('pagecontent_meta_description'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="pagecontent_meta_author" class="">Meta Author</label>
									<?php echo form_input(array('name' => 'pagecontent_meta_author','value' => (isset($_POST['pagecontent_meta_author']) ? set_value('pagecontent_meta_author') : $row['pagecontent_meta_author']),'class' => 'form-control','id' => 'pagecontent_meta_author','placeholder' => ''));?>
									<?php echo form_error('pagecontent_meta_author'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">									
									<label for="pagecontent_status" class="">Status</label>
									<?php echo form_dropdown('pagecontent_status', array('Y'=>'Shown','N'=>'Hidden'), (isset($_POST['pagecontent_status']) ? set_value('pagecontent_status') : $row['pagecontent_status']), array('class' => 'form-control')); ?>
									<?php echo form_error('pagecontent_status'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">									
									<label for="pagecontent_archived" class="">Archived</label>
									<?php echo form_dropdown('pagecontent_archived', array('Y'=>'Yes','N'=>'No'), (isset($_POST['pagecontent_archived']) ? set_value('pagecontent_archived') : $row['pagecontent_archived']), array('class' => 'form-control'));?>
									<?php echo form_error('pagecontent_archived'); ?>
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