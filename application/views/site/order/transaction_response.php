<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Your Order</h1>               
    </div>
</div>

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
    </div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Order # <?php echo $order_no;?></h3>
			</div>
			<div class="panel-body">
				<?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form','name' => 'order', 'id' => 'cartForm')); ?>
				<?php echo form_hidden('form_action', 'order'); ?>			
				<div class="text-center">
					<a href="<?php echo site_url('product');?>" class="btn btn-lg btn-secondary">Invoice</a>
					<a href="<?php echo site_url('product');?>" class="btn btn-lg btn-secondary">Continue Shopping</a>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>	
</div>