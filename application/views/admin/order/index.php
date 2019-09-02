<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>

<div class="row">
    <div class="col-lg-12">
        <?php echo isset($alert_message) ? $alert_message : ''; ?>
		
		<div class="table-responsive">
			<table id="example1" class="table ci-table table-striped">
				<thead class="thead-dark">
					<tr>
						<th>Order #</th>
						<th>Date</th>
						<th>Order Amt</th>
						<th>Payment Status</th>
						<th>Customer</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>Order #</th>
						<th>Date</th>
						<th>Order Amt</th>
						<th>Payment Status</th>
						<th>Customer</th>
						<th>Action</th>
					</tr>
				</tfoot>
			</table>
		</div>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->