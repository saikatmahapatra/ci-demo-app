<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        <?php echo $breadcrumbs; ?>
    </div>
</div><!--/.heading-container-->

<a href="<?php echo base_url($this->router->directory.'user/create_account');?>" class="btn btn-sm btn-success" title="Add"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add New</a>

<div class="row">    
	
	
	<?php
	// Show server side flash messages
	if (isset($alert_message)) {
		$html_alert_ui = '';                
		$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
		echo $html_alert_ui;
	}
	?>
		
	<?php //print_r($data_rows); ?>
				<?php
				if(isset($data_rows)){
					foreach($data_rows as $key=>$row){
						?>						
						<div class="col-md-3">
							<div class="card mb-1">
							  <img class="card-img-top" src="" alt="Card image cap">
							  <div class="card-body">
								<h5 class="card-title"><?php echo $row['user_firstname'].' '.$row['user_lastname']; ?></h5>
								<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
								<a href="#" class="btn btn-primary">Go somewhere</a>
							  </div>
							</div>
						</div>
						
						<?php
					}
				}
				?>
</div>
<div class=""><?php echo $pagination_link;?></div>