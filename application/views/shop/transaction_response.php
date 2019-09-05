<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
	<div class="col-lg-12">
		<?php echo isset($alert_message) ? $alert_message : ''; ?>
		<div class="card">
			<div class="card-header">
                Order # <?php echo $order_no;?>
			</div>
			<div class="card-body">
                <p class="text-center">Thank you for shopping with us. We have received your order. You will get order status email notifications soon.<p>
				<?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form','name' => 'order', 'id' => 'cartForm')); ?>
				<?php echo form_hidden('form_action', 'order'); ?>			
				<div class="text-center">
					<a href="<?php echo base_url($this->router->directory.'shop/download_invoice');?>" class="btn btn-primary">Download Invoice</a>
					<a href="<?php echo base_url($this->router->directory.'shop');?>" class="btn btn-primary">Continue Shopping</a>
				</div>
				<?php echo form_close(); ?>
			</div>            
		</div>
	</div>	
</div>