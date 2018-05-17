<?php
// For making nav item active. Add class .active to .nav-item
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
?>

<a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo $this->config->item('app_html_title'); ?></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
	aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarsExampleDefault">
	<ul class="navbar-nav">
		<li class="nav-item <?php echo ($segment1 == '' || $segment1 == 'home') ? 'active':''?>">
			<a class="nav-link" href="<?php echo site_url();?>">Home</a>
			<span class="sr-only">(current)</span>
		</li>
		<li class="nav-item <?php echo ($segment1 == 'product') ? 'active':''?>">
			<a class="nav-link" href="<?php echo site_url('product');?>">Shop Online</a>
		</li>
		<li class="nav-item <?php echo ($segment1 == 'order') ? 'active':''?>">
			<a class="nav-link" href="<?php echo site_url('order/my_cart');?>">Cart</a>
		</li>
		<li class="nav-item <?php echo ($segment1 == 'contact') ? 'active':''?>">
			<a class="nav-link" href="<?php echo site_url('contact');?>">Contact Us</a>
		</li>

		<li class="nav-item dropdown <?php echo ($segment1 == 'ci_example') ? 'active':''?>">
			<a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true"
				aria-expanded="false">Examples Modules</a>
				<div class="dropdown-menu" aria-labelledby="dropdown02">                                
					<a class="dropdown-item" href="<?php echo site_url('ci_example/form_helper');?>">Form Helper</a>
					<a class="dropdown-item" href="<?php echo site_url('ci_example/date_helper');?>">Date Helper</a>
					<a class="dropdown-item" href="<?php echo site_url('ci_example/directory_helper');?>">Directory Helper</a>               
					<a class="dropdown-item" href="<?php echo site_url('ci_example/dom_pdf_gen_pdf');?>">Download as PDF/DOM PDF</a>
				</div>
		</li>

		<?php
		if (isset($this->session->userdata['sess_user']['id'])) {
			?>
			<li class="nav-item dropdown <?php echo ($segment2 == 'profile' || $segment2 == 'change_password') ? 'active':''?>">
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
				<a class="dropdown-item"  href="<?php echo site_url('user/profile/'.$this->session->userdata['sess_user']['id']); ?>">Profile</a>
				<a class="dropdown-item" href="<?php echo site_url('user/change_password'); ?>">Change Password</a>
				<a class="dropdown-item" href="<?php echo site_url('user/logout'); ?>">Logout</a>			
			</div>
			</li>
			<?php
		} else {
			?>
			<li class="nav-item <?php echo ($segment2 == 'create_account') ? 'active':''?>">            
				<a class="nav-link" href="<?php echo site_url('user/create_account');?>">Register</a>
			</li>
			<li class="nav-item <?php echo ($segment2 == 'login') ? 'active':''?>">
				<a class="nav-link" href="<?php echo site_url('user/login');?>">Login</a>
			</li>
			<?php
		}
		?>
	</ul>
</div>