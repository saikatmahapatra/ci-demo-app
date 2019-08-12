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
		<div class="grid-action-holder row my-2 px-3">
			<div class="col-md-8">
			<span class="mx-2"><i class="fa fa-fw fa-bookmark-o text-success" aria-hidden="true"></i> Success</span>
			<span class="mx-2"><i class="fa fa-fw fa-bookmark-o text-warning" aria-hidden="true"></i> Cancelled</span>
			</div>
			<div class="col-md-4 text-right">
			
			</div>		
		</div><!--/.grid-action-holder-->
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
		</div><!--/.grid-action-holder-->
    </div><!-- /.col-md-12 -->
</div><!-- /.row -->