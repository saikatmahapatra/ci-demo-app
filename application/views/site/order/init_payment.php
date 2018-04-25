<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">My Orders</h1>               
    </div>
</div><!--/.row-->

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
</div><!--/.row-->

<div class="row">
   <div class="col-md-8">
		<?php
		echo form_open(current_url(), array(
			'method' => 'post',			
			'class'=>'ci-form',
			'name' => '',
			'id' => '',
		));
		?>
		<?php echo form_hidden('form_action', 'place_order'); ?>
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
         <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="step1">
               <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					<i class="more-less glyphicon glyphicon-plus"></i>
                  1. LOGIN
                  </a>
               </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="step1">
               <div class="panel-body">
                  <div class="">
                     <h4><?php echo $this->session->userdata['sess_user']['user_firstname'].' '.$this->session->userdata['sess_user']['user_lastname'];?></h4>
                  </div>
                  <div class=""><?php echo $this->session->userdata['sess_user']['user_email'];?></div>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="step2">
               <h4 class="panel-title">
                  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					<i class="more-less glyphicon glyphicon-minus"></i>
                  2. DELIVERY ADDRESS
                  </a>
               </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="step2">
               <div class="panel-body">
                  <?php //echo '<pre>';print_r($shipping_address);?>
                  <?php if(isset($shipping_address)){
                     foreach($shipping_address as $key=>$shipping_addr){
                     ?>
					 
                  <div class="radio">
                     <label>                        
						<?php
							$radio_is_checked = $this->input->post('shipping_address') === $shipping_addr['id'];
							echo form_radio(array(
							'name' => 'shipping_address',
							'value' => $shipping_addr['id'],
							'id' => 'delivery_address_'.$shipping_addr['id'],
							'checked' => $radio_is_checked,
							'class' => '',
							), set_radio('shipping_address', $shipping_addr['id']));
						?>
                        <div class="text-bold">
							<?php echo isset($shipping_addr['name'])?$shipping_addr['name'].'&nbsp;' :'';?>
							<?php echo isset($shipping_addr['phone1'])?$shipping_addr['phone1']:'';?>
						</div>
						<div>
							<?php echo isset($shipping_addr['address']) ? $shipping_addr['address'] : '';?>
							<?php echo isset($shipping_addr['locality'])? ', '.$shipping_addr['address'] : '';?>
							<?php echo isset($shipping_addr['city']) ? ', '.$shipping_addr['city'].', ' : '';?>                                    
						</div>
						<div>
							<?php echo isset($shipping_addr['state']) ? $shipping_addr['state'] : '';?>
							<?php echo isset($shipping_addr['zip']) ? ' - <span class="text-bold">'.$shipping_addr['zip'].'</span>' : '';?>
						</div>
						<?php echo form_error('shipping_address');?>
						<a class="btn btn-dark btn-lg">Deliver Here</a>
                     </label>
                  </div>
                  <?php
                     }
                     }else{
						 ?>
						 <a class="btn btn-dark btn-sm pull-right" href="<?php echo site_url('user/add_address');?>">Add a Shipping Address</a>
						 <?php
					 }
					?>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="step3">
               <h4 class="panel-title">
                  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				  <i class="more-less glyphicon glyphicon-minus"></i>
                  3. ORDER SUMMARY
                  </a>
               </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="step3">
               <div class="panel-body">
                  <?php
                     if (sizeof($cartrows) > 0) {
                     	$row_counter = 1;								
                     	foreach ($cartrows as $row) {									
                     		//print_r($row);
                     		?>
                  <div class="row cart-item" data-rowid="<?php echo $row['rowid']; ?>" data-id="<?php echo $row['id']; ?>">
                     <div class="col-md-12">
                        <div class="media">
                           <div class="media-left media-top">
                              <img src="https://www.w3schools.com/bootstrap/img_avatar1.png" class="media-object" style="width:60px">
                           </div>
                           <div class="media-body">
                              <h4 class="media-heading"><?php echo $row['name']; ?></h4>
                              <div>Size: M</div>
                              <div>Seller: Polo Store</div>
                              <div class="row">
                                 <div class="col-md-2">
                                    Quantity : <?php echo $row['qty']; ?>
                                    <?php echo form_hidden('rowid_'.$row_counter,$row['rowid']);?>
                                 </div>
                                 <div class="col-md-8 text-right text-bold"><?php echo '<span class="currency" id="INR">&#8377;</span>'.number_format($row['line_total'], 2); ?></div>
                              </div>
                           </div>
                        </div>
                        <!--/.media-->
                     </div>
                  </div>
                  <?php
                     $row_counter++;
                     } ?>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="step4">
               <h4 class="panel-title">
                  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
				  <i class="more-less glyphicon glyphicon-minus"></i>
                  3. PAYMENT OPTIONS
                  </a>
               </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="step4">
               <div class="panel-body">
					<div class="form-group">
						<label for="payment_method" class="">Payment Method</label>
						<div>
						<?php						
						if(isset($payment_method)){
							foreach($payment_method as $key=>$val){
								?>
								<label class="">
									<?php
									$radio_is_checked = $this->input->post('payment_method') === $key;
									echo form_radio(array(
									'name' => 'payment_method',
									'value' => $key,
									'id' => $key,
									'checked' => $radio_is_checked,
									'class' => '',
									), set_radio('payment_method', $key));
									?>
									<span><?php echo $val;?></span>
								</label>
								<?php
							}
						}
						?>
						</div>
						<?php echo form_error('payment_method'); ?>
					</div>
               </div>
               <div class="panel-footer">
                  <button type="submit" class="btn btn-primary btn-lg text-upper">Place Order</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
   <div class="col-md-4">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title">Price Details</h3>
         </div>
         <div class="panel-body">
            <div class="row">
               <div class="col-md-6">Price(<?php echo isset($total_items) ? $total_items.' items' : ''; ?>)</div>
               <div class="col-md-6 text-right"><span class="currency" id="INR">&#8377;</span><?php echo isset($cart_total)?number_format($cart_total,2):'';?></div>
            </div>
            <div class="row">
               <div class="col-md-6">Delivery Charges</div>
               <div class="col-md-6 text-right text-success">FREE</div>
            </div>
         </div>
         <div class="panel-footer">
            <div class="row">
               <div class="col-md-6 h4">Amount Payble</div>
               <div class="col-md-6 h4 text-right"><span class="currency" id="INR">&#8377;</span><?php echo isset($cart_total) ? number_format($cart_total,2) :'';?></div>
            </div>
         </div>
      </div>      
   </div>
</div>
<!--/.row-->