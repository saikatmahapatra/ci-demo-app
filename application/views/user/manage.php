<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>


<div class="row">
    <div class="col-lg-12">
        <?php echo isset($alert_message) ? $alert_message : ''; ?>
		
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