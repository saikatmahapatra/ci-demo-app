<?php
$row = $rows[0];
?>
<!--<h2 class="page-header"><?php echo isset($page_heading)? $page_heading:'Untitled Page'; ?></h2>-->
<?php echo $breadcrumbs; ?>
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
			<div class="card-header">Order # 0187282882929</div>
			<div class="card-body">
				<div class="col-md-7">
					
					<div class="row">
						<div class="col-md-4 col-heading">Order Amount</div>
						<div class="col-md-8">INR 3200.00</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Payment Mode</div>
						<div class="col-md-8">Net Banking</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Payment Status</div>
						<div class="col-md-8">Successful</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Payment Mode</div>
						<div class="col-md-8">Net Banking</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4">Payment Status</div>
						<div class="col-md-8">Successful</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Payment Mode</div>
						<div class="col-md-8">Net Banking</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Payment Status</div>
						<div class="col-md-8">Successful</div>                        
					</div><!--/.row-->
				</div>
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-4 col-heading">Order Date/Time</div>
						<div class="col-md-8">02-12-2016 03.25pm</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Customer Details</div>
						<div class="col-md-8">
							<div>Saikat Mahapatra</div>
							<div class="">mahapatra.saikat@gmail.com</div>
							<div class="">9831616696</div>
						</div>                        
					</div><!--/.row-->
					<div class="row">
						<div class="col-md-4 col-heading">Deliver To</div>
						<div class="col-md-8">
							<div>Saikat Mahapatra</div>
							<div>Maa Apt, Flat # 2A, Floor 2nd, 3 Netaji Lane, Durganagar, Uttarbadra, North 24 Parganas, Kolkata, West Bengal 700079, IN</div>
							<div>9830098300</div>
						</div>                        
					</div><!--/.row-->
				</div>
				
								
				
				
			</div>
		</div>
		
		<div class="panel-group" id="accordion">
		
            <div class="card ">
                <div class="card-header">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
							<i class="more-less glyphicon glyphicon-minus"></i>
							1/1 of 0187282882929
						</a>
					</h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
					<div class="card-body">
						<div class="row order-product">
							<div class="col-md-12">
								<div class="media">
									<div class="media-left media-top hidden-xs">
										<img src="https://www.w3schools.com/bootstrap/img_avatar1.png" class="media-object" style="width:180px;">
									</div>
									<div class="media-body">
										<h4 class="media-heading">Louis Philippe Formal Shirt</h4>
										<div>Seller: Polo Store</div>
										<div class="row">
											<div class="col-md-3 col-heading">Qty</div>
											<div class="col-md-9">2</div>                        
										</div><!--/.row-->
										<div class="row">
											<div class="col-md-3 col-heading">Price</div>
											<div class="col-md-9">100.00 INR</div>                        
										</div><!--/.row-->
										<div class="row">
											<div class="col-md-3 col-heading">Total Price</div>
											<div class="col-md-9">200.00 INR</div>                        
										</div><!--/.row-->
										<div class="row">
											<div class="col-md-3 col-heading">Disscount Coupon</div>
											<div class="col-md-9">PUJA20</div>                        
										</div><!--/.row-->
										<div class="row">
											<div class="col-md-3 col-heading">Disscount Amount</div>
											<div class="col-md-9">40.00 INR</div>                        
										</div><!--/.row-->
										<div class="row">
											<div class="col-md-3 col-heading">Delivery Charges</div>
											<div class="col-md-9">FREE</div>                        
										</div><!--/.row-->						
										<div class="row">
											<div class="col-md-3 col-heading">Order Total</div>
											<div class="col-md-9">160.00 INR</div>                        
										</div><!--/.row-->
									</div>
								</div><!--/.media-->
							</div>                       
						</div><!--/.row.order-product-->
						
						
						
						
						
							<div class="row hidden">
								<div class="col-md-12">									
									<div style="display:inline-block;width:100%;overflow-y:auto;">
									<ul class="timeline timeline-horizontal">
										<li class="timeline-item">
											<div class="timeline-badge primary"><i class="glyphicon glyphicon-record"></i></div>
										</li>
										<li class="timeline-item">
											<div class="timeline-badge success"><i class="glyphicon glyphicon-record"></i></div>
										</li>
										<li class="timeline-item">
											<div class="timeline-badge info"><i class="glyphicon glyphicon-record"></i></div>
										</li>
										<li class="timeline-item">
											<div class="timeline-badge danger"><i class="glyphicon glyphicon-record"></i></div>
										</li>
										<li class="timeline-item">
											<div class="timeline-badge warning"><i class="glyphicon glyphicon-record"></i></div>
										</li>
										<li class="timeline-item">
											<div class="timeline-badge"><i class="glyphicon glyphicon-record"></i></div>
										</li>
									</ul>
								</div>
								</div>
							</div>
							
						
						
						
					</div><!--/.card-body-->
                </div>
            </div><!--/.panel-->
            
        </div>
		<!-- /.panel -->
	</div>
	<!-- /.col-md-12 -->
</div>