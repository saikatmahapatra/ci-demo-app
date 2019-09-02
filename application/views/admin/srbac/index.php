<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
	<div class="col-lg-12">
		<?php echo isset($alert_message) ? $alert_message : ''; ?>
		<div class="card">
			<div class="card-header">
				<span class="">Data Table</span>
				<span class="float-right">
					<a href="<?php echo base_url($this->router->directory.$this->router->class.'/add');?>" class="btn btn-sm btn-primary" title="Add"> Add New</a>
				</span>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="cms-datatable" class="table table-sm">
						<thead>
							<tr>
								<th scope="col">Type</th>								
								<th scope="col">Title</th>
								<th scope="col">Created On</th>
								<th scope="col">Status</th>								
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<th scope="col">Type</th>								
								<th scope="col">Title</th>
								<th scope="col">Created On</th>
								<th scope="col">Status</th>								
								<th scope="col">Action</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div><!--/.row-->
