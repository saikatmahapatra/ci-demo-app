<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>


<div class="row">
    <div class="col-md-12">
        <?php
		// Show server side flash messages
		if (isset($alert_message)) {
			$html_alert_ui = '';                
			$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
			echo $html_alert_ui;
		}
		?>
		
		<div class="ci-link-group">
			<a href="<?php echo base_url($this->router->directory.$this->router->class.'/create_account');?>" class="btn btn-sm btn-outline-success" title="Add"> <i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add New</a>
			
			<form class="form-inline ml-3" name="download" method="post" action="<?php echo current_url();?>">
				<input type="hidden" name="form_action" value="download">
				<button class="btn btn-sm btn-outline-secondary" title="Download"> <i class="fa fa-fw fa-download" aria-hidden="true"></i> Download</button>
			</form>

		</div>

		<div class="table-responsive">
				<table id="user-datatable" class="table ci-table table-striped">
					<thead class="thead-dark">
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Mobile</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Mobile</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
		</div><!--/.table-responsive-->
    </div>
</div>