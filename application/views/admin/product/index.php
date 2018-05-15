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
                <div class="card-header">
                    <span class="">Products</span>
					<span class="float-right">
						<a href="<?php echo base_url('admin/product/add');?>" class="btn btn-sm btn-success" title="Add"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add New</a>
					</span>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="product-datatable" class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product Details</th>
                                <th>Price Details</th>
                                <th>Spec/Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>Product Details</th>
                                <th>Price Details</th>
                                <th>Spec/Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.panel -->
    </div>
    <!-- /.col-md-12 -->
</div>