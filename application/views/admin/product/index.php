<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
    <div class="col-lg-12">
        <?php echo isset($alert_message) ? $alert_message : ''; ?>
		
		<div class="ci-link-group">
			<a href="<?php echo base_url($this->router->directory.$this->router->class.'/add');?>" class="btn btn-sm btn-outline-success" title="Add"> <i class="fa fa-fw fa-plus"></i> Add New</a>
		</div>

		<div class="table-responsive">
			<table id="product-datatable" class="table ci-table table-striped">
				<thead class="thead-dark">
					<tr>
						<th>SKU</th>
						<th>Product</th>
						<th>Category</th>
						<th>MRP</th>
						<th>Price</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>SKU</th>
						<th>Product</th>
						<th>Category</th>
						<th>MRP</th>
						<th>Price</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</tfoot>
			</table>
		</div><!--/.table-responsive-->
    </div>
</div><!--/.row-->