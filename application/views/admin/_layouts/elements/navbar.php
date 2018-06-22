<?php
// For making nav item active. Add class .active to .nav-item
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
?>
<nav class="navbar navbar-expand-md navbar-dark bg-ca fixed-top" id="navbar1">
	<a class="navbar-brand" href="<?php echo base_url($this->router->directory); ?>">
		<img src="<?php echo base_url('assets/src/img/logo.png');?>" width="180px">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
		aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	
	<div class="navbar-collapse collapse ">		
		<ul class="navbar-nav mr-auto">                
			<li class="nav-item">
				<a class="nav-link" href="#"><h1>Admin Portal</h1></a>
			</li>
		</ul>
		<ul class="navbar-nav ">                
			<li class="nav-item mr-2">
				<div class="media text-white">					 
					<img data-src="holder.js/75x75" class="mr-3 rounded" alt="50x50" style="width: 50px; height: 50px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2275%22%20height%3D%2275%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2075%2075%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_16422628995%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_16422628995%22%3E%3Crect%20width%3D%2275%22%20height%3D%2275%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2219.34375%22%20y%3D%2242%22%3E75x75%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">					  
				  <div class="media-body">
					<div class="mt-0">Hello, Saikat Mahapatra</div>						
					<div class="mt-0 small">mahapatra.saikat@gmail.com</div>						
					<div class="mt-0"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</div>					
				  </div>
				</div>
			</li>
			
			<!--<li class="nav-item">
				<a class="nav-link" href="#">Pricing</a>
			</li>-->
		</ul>			
		
	</div>
</nav>
	
<nav class="navbar navbar-expand-md navbar-dark pt-0 bg-dark pb-0 fixed-top" id="navbar2">
	
	<div class="collapse navbar-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav">
			<li class="nav-item <?php echo ($segment2=='home') ? 'active':''?>">
				<a class="nav-link" href="<?php echo base_url($this->router->directory.'home'); ?>">Home
					<span class="sr-only">(current)</span>
				</a>
			</li>	
			<li class="nav-item dropdown <?php echo ($segment2=='cms') ? 'active':''?>">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true"
					aria-expanded="false">CMS</a>
				<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'cms');?>">View All</a>
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'cms/add');?>">Add</a>
				</div>
			</li>

			<li class="nav-item dropdown <?php echo ($segment2=='product' || $segment2 == 'category') ? 'active':''?>">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true"
					aria-expanded="false">Products</a>
				<div class="dropdown-menu" aria-labelledby="dropdown02">
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'product');?>">View Products</a>
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'product/add');?>">Add Product</a>
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'category');?>">View Categories</a>
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'category/add');?>">Add Category</a>
				</div>
			</li>
			
			<li class="nav-item <?php echo ($segment3 == 'manage' || $segment3 == 'create_account') ? 'active':''?>">
				<a class="nav-link"href="<?php echo base_url($this->router->directory.'user/manage'); ?>">Customers</a>
			</li>
			<li class="nav-item <?php echo ($segment2=='order') ? 'active':''?>">
				<a class="nav-link" href="<?php echo base_url($this->router->directory.'order'); ?>">Orders</a>
			</li>
			
			<?php if (isset($this->session->userdata['sess_user']['id'])) {  ?>
			<li class="nav-item dropdown <?php echo ($segment2 == 'user' && ($segment3 != 'manage' || $segment3 != 'create_account')) ? 'active':''?>">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true"
					aria-expanded="false"><i aria-hidden="true" class="fa fa-user-circle"></i> My Account </a>
				<div class="dropdown-menu" aria-labelledby="dropdown03">
					
					<div class="dropdown-item welcome-user-container">
					<!--<a class="dropdown-item" href="#">-->				
						<div class=""><?php echo isset($this->session->userdata['sess_user']['user_firstname']) ? $this->session->userdata['sess_user']['user_firstname'].' '.$this->session->userdata['sess_user']['user_lastname']:'Guest';?></div>
						<div class="small"><?php echo isset($this->session->userdata['sess_user']['user_email']) ? $this->session->userdata['sess_user']['user_email'] :'';?></div>
						<div class="small">Role: <?php echo isset($this->session->userdata['sess_user']['user_role_name']) ? $this->session->userdata['sess_user']['user_role_name'] :'';?></div>
						<div class="small d-none">Last Login: 03/04/2018 10.30am</div>
					<!--</a>-->
					</div><!--/.welcome-user-container-->
					
					<div class="dropdown-divider mt-3"></div>			
					<a class="dropdown-item"  href="<?php echo base_url($this->router->directory.'user/profile/'.$this->session->userdata['sess_user']['id']); ?>">Profile</a>
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'user/change_password'); ?>">Change Password</a>
					<a class="dropdown-item" href="<?php echo base_url($this->router->directory.'user/logout'); ?>">Logout</a>			
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>
</nav>