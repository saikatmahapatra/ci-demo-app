<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">My Cart</h1>               
    </div>
</div>

<div class="row">
    <div class="col-12">
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
	<div class="col-md-8">
		<?php echo form_open(current_url(), array('method' => 'post', 'class' => 'ci-form','name' => 'cartForm', 'id' => 'cartForm')); ?>
		<?php echo form_hidden('form_action', 'update_cart'); ?>
		<div class="card">
			<div class="card-header">
				My Cart (<?php echo $total_items; ?>)
			</div><!--/.card-header-->
			<div class="card-body">				
				<?php
					if (sizeof($cartrows) > 0) {
						$row_counter = 1;								
						foreach ($cartrows as $row) {									
							//print_r($row);
							?>
							<div class="row cart-item" data-rowid="<?php echo $row['rowid']; ?>" data-id="<?php echo $row['id']; ?>">
								<div class="col-md-12">
									<div class="media">
										<div class="mr-3 media-left media-top">
										<img src="https://www.w3schools.com/bootstrap/img_avatar1.png" class="media-object" style="width:60px">
										</div>
										<div class="media-body">											
										<div class="mt-0"><?php echo $row['name']; ?></div>
										<div>Size: M</div>
										<div>Seller: Polo Store</div>
										<div hidden><?php echo '<span class="currency" id="INR">&#8377;</span> ' . number_format($row['price'], 2); ?></div>
										
										<div class="row">
											<div class="col-md-2">
											<input name="quantity_<?php echo $row_counter; ?>" type="number" value="<?php echo $row['qty']; ?>" min="1" max="5" maxlength="2" class="form-control"/>
											<?php echo form_hidden('rowid_'.$row_counter,$row['rowid']);?>
											</div>
											<div class="col-md-2">											
											<a href="<?php echo site_url('order/remove_cart/'.$row['rowid']);?>" class="">REMOVE</a>
											</div>
											<div class="col-md-8 text-right text-bold"><?php echo '<span class="currency" id="INR">&#8377;</span>'.number_format($row['line_total'], 2); ?></div>
										</div>												
										</div>
									</div><!--/.media-->
								</div>
							</div>
							<?php
							$row_counter++;
						}
						?>                             

					<?php } else { ?>				  
						<div class="row">
							<div class="col-md-12">your shopping cart is empty</div>
						</div>
					<?php } ?>
				
			</div><!--/.card-body-->
			<div class="card-footer">
				<div class="text-right">
					<a href="<?php echo site_url('product');?>" class="btn btn-primary">Continue Shopping</a>
					<?php
					if (sizeof($cartrows) > 0) {
						?>
						<button type="submit" class="btn btn-primary">Update Cart</button>
						<a href="<?php echo site_url('order/init_payment');?>" class="btn btn-primary">Place Order</a>
						<?php						
					}
					?>
				</div>
			</div><!--/.card-footer-->			
		</div><!--/.card-->
		<?php echo form_close(); ?>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">Price Details</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">Price(<?php echo isset($total_items) ? $total_items.' items' : ''; ?>)</div>
					<div class="col-md-6 text-right"><span class="currency" id="INR">&#8377;</span><?php echo isset($cart_total)?number_format($cart_total,2):'';?></div>
				</div>
				<div class="row">
					<div class="col-md-6">Delivery Charges</div>
					<div class="col-md-6 text-right text-success">FREE</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<h5>Amount Payble</h5>
					<div class="col-md-6 h4 text-right"><span class="currency" id="INR">&#8377;</span><?php echo isset($cart_total) ? number_format($cart_total,2) :'';?></div>
				</div>
			</div>
		</div>
		<p>
			Safe and Secure Payments. Easy returns. 100% Authentic products.
		</p>
	</div>
</div>