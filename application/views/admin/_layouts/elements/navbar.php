<ul class="navbar-nav">
	<li class="nav-item <?php echo ($this->uri->segment(2)=='home') ? 'active':''?>">
		<a class="nav-link" href="<?php echo site_url('admin/home'); ?>">Home
			<span class="sr-only">(current)</span>
		</a>
	</li>	
	<li class="nav-item dropdown <?php echo ($this->uri->segment(2)=='cms') ? 'active':''?>">
		<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="false">CMS</a>
		<div class="dropdown-menu" aria-labelledby="dropdown01">
			<a class="dropdown-item" href="<?php echo site_url('admin/cms');?>">View All</a>
			<a class="dropdown-item" href="<?php echo site_url('admin/cms/add');?>">Add</a>
		</div>
	</li>

	<li class="nav-item dropdown <?php echo ($this->uri->segment(2)=='product' || $this->uri->segment(2) == 'category') ? 'active':''?>">
		<a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="false">Products</a>
		<div class="dropdown-menu" aria-labelledby="dropdown02">
			<a class="dropdown-item" href="<?php echo site_url('admin/product');?>">View Products</a>
			<a class="dropdown-item" href="<?php echo site_url('admin/product/add');?>">Add Product</a>
			<a class="dropdown-item" href="<?php echo site_url('admin/category');?>">View Categories</a>
			<a class="dropdown-item" href="<?php echo site_url('admin/category/add');?>">Add Category</a>
		</div>
	</li>
	
	<li class="nav-item <?php echo ($this->uri->segment(3)=='manage') ? 'active':''?>">
		<a class="nav-link"href="<?php echo site_url('admin/user/manage'); ?>">Customers</a>
	</li>
	<li class="nav-item <?php echo ($this->uri->segment(2)=='order') ? 'active':''?>">
		<a class="nav-link" href="<?php echo site_url('admin/order'); ?>">Orders</a>
	</li>
	
	<?php if (isset($this->session->userdata['sess_user']['id'])) {  ?>
	<li class="nav-item dropdown <?php echo ($this->uri->segment(2)=='user' && $this->uri->segment(3)!='manage') ? 'active':''?>">
		<a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="false"><i aria-hidden="true" class="fa fa-user-circle"></i> My Account </a>
		<div class="dropdown-menu" aria-labelledby="dropdown03">
			
			<div class="dropdown-item welcome-user-container">
			<!--<a class="dropdown-item" href="#">-->
				<div class=""><?php echo isset($this->session->userdata['sess_user']['user_firstname']) ? $this->session->userdata['sess_user']['user_firstname'].' '.$this->session->userdata['sess_user']['user_lastname']:'Guest';?></div>
				<div class="small d-none"><?php echo isset($this->session->userdata['sess_user']['user_role']) ? $this->session->userdata['sess_user']['user_role_name'] :'';?></div>
				<div class="small"><?php echo isset($this->session->userdata['sess_user']['user_email']) ? $this->session->userdata['sess_user']['user_email'] :'';?></div>
				<div class="small">Last Login: 03/04/2018 10.30am</div>
			<!--</a>-->
			</div><!--/.welcome-user-container-->
			
			<div class="dropdown-divider mt-3"></div>			
			<a class="dropdown-item"  href="<?php echo site_url('admin/user/profile/'.$this->session->userdata['sess_user']['id']); ?>">Profile</a>
			<a class="dropdown-item" href="<?php echo site_url('admin/user/change_password'); ?>">Change Password</a>
			<a class="dropdown-item" href="<?php echo site_url('admin/user/logout'); ?>">Logout</a>			
		</div>
	</li>

	<?php } ?>

</ul>