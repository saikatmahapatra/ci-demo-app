<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
    <div class="col-md-12">
        <?php echo isset($alert_message) ? $alert_message : ''; ?>
		
		<div class="ci-link-group">
			<a href="<?php echo base_url($this->router->directory.$this->router->class.'/add');?>" class="btn btn-sm btn-outline-success" title="Add"> <i class="fa fa-fw fa-plus"></i> Add New</a>
		</div>
		
		<div class="table-responsive">
			<table id="category-datatable" class="table ci-table table-striped">
				<thead class="thead-dark">
					<tr>
						<th>Category Name</th>
						<th>Visible Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>Category Name</th>
						<th>Visible Status</th>
						<th>Action</th>
					</tr>
				</tfoot>
			</table>
		</div><!--/.table-responsive-->
    </div>
</div>