<?php
// For making nav item active. Add class .active to .nav-item
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
//print_r($user_profile_image);
?>
<div class="sidebar-overlay" data-toggle="sidebar"></div>
<aside class="sidebar">
    <?php if (isset($this->session->userdata['sess_user']['id'])) {   ?>
    <?php
        $img_src = "";
        $default_path = "assets/dist/img/default_user@2x.jpg";
        if(isset($this->session->userdata['sess_user']['user_profile_pic'])){
            $user_dp = "assets/uploads/user/profile_pic/".$this->session->userdata['sess_user']['user_profile_pic'];
            if (file_exists(FCPATH . $user_dp)) {
                $img_src = $user_dp;
            }else{
                $img_src = $default_path;
            }
        }else{
            $img_src = $default_path;
        }
    ?>


    <div class="sidebar-user">
        <a href="<?php echo base_url('user/my_profile'); ?>"><img class="sidebar-user-avatar" src="<?php echo base_url($img_src);?>" alt="Profile Image" /></a>
        <div class="small">
            <p class="sidebar-user-name">
                <?php echo isset($this->session->userdata['sess_user']['user_title'])? $this->session->userdata['sess_user']['user_title']:''; ?>
                <?php echo isset($this->session->userdata['sess_user']['user_firstname']) ? $this->session->userdata['sess_user']['user_firstname'] : '' ;?>
                <?php echo isset($this->session->userdata['sess_user']['user_lastname']) ? ' '.substr($this->session->userdata['sess_user']['user_lastname'], 0, 1) : '' ;?>
            </p>
            <p class="sidebar-user-designation">
                <?php echo isset($this->session->userdata['sess_user']['user_role_name']) ? $this->session->userdata['sess_user']['user_role_name'] :'';?>
            </p>
        </div>
    </div>
    <?php } ?>

    <ul class="menu">
        <li>
            <a class="menu-item" href="<?php echo base_url();?>"><i class="menu-icon fa fa-dashboard" aria-hidden="true"></i><span class="menu-label">Dashboard</span></a>
        </li>
        <li class="treeview">
            <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-list-ul" aria-hidden="true"></i><span class="menu-label">Example</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/bootstrap');?>">Bootstrap Theme</a></li>
				<li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/form_helper');?>">Form Helper</a></li>
				<li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/date_helper');?>">Date Helper</a></li>
				<li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/directory_helper');?>">Directory Helper</a></li>            
				<li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/dom_pdf_gen_pdf');?>">Download as PDF/DOM PDF</a></li>			
				<li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/calendar_lib');?>">Calendar Library</a></li>				
				<a class="treeview-item" href="<?php echo base_url($this->router->directory.'example/contact_form');?>">Contact Form (Email Test)</a></li>
            </ul>
        </li>
        
        <?php if (isset($this->session->userdata['sess_user']) && $this->session->userdata['sess_user']['user_role'] == 1) { ?>			
            <li class="treeview">
                <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-laptop" aria-hidden="true"></i><span class="menu-label">Admin Links</span><i class="treeview-indicator fa fa-angle-right" aria-hidden="true"></i></a>
                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="<?php echo base_url('home/dashboard'); ?>">Dashboard</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('cms/index');?>">Manage CMS</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('cms/add');?>">Create Content</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('cms/manage_banner');?>">Manage Carousel</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('cms/add_banner');?>">Create Carousel</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('product');?>">Manage Products</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('product/add');?>">Create Product</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('category');?>">Manage Categories</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('category/add');?>">Create Category</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('user/manage'); ?>">Manage Users</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('user/create_account'); ?>">Create User</a></li>
                    <li><a class="treeview-item" href="<?php echo base_url('order'); ?>">Manage Orders</a></li>
                </ul>
            </li>
			<?php } ?>
			
			<li>
				<a class="menu-item" href="<?php echo base_url($this->router->directory.'product/shop_online');?>"><i class="menu-icon fa fa-shopping-basket" aria-hidden="true"></i><span class="menu-label">Shop Online</span></a>
			</li>
			<li>
				<a class="menu-item" href="<?php echo base_url($this->router->directory.'order/my_cart');?>"><i class="menu-icon fa fa-shopping-basket" aria-hidden="true"></i><span class="menu-label">Cart</span></a>
			</li>	
			<?php if (isset($this->session->userdata['sess_user']['id'])) {   ?>
			<li>
				<a class="menu-item" href="<?php echo base_url($this->router->directory.'user/people'); ?>"><i class="menu-icon fa fa-users" aria-hidden="true"></i><span class="menu-label">People</span></a>
			</li>			
			<li>
				<a class="menu-item" href="<?php echo base_url($this->router->directory.'timesheet'); ?>"><i class="menu-icon fa fa-clock-o" aria-hidden="true"></i><span class="menu-label">Timesheet</span></a>
			</li>
			<li>
				<a class="menu-item" href="<?php echo base_url($this->router->directory.'document'); ?>"><i class="menu-icon fa fa-file" aria-hidden="true"></i><span class="menu-label">My Documents</span></a>
			</li>
			<?php } ?>
			
			<?php if (!isset($this->session->userdata['sess_user']['id'])) { ?>
				<li>
					<a class="menu-item" href="<?php echo base_url($this->router->directory.'user/registration'); ?>"><i class="menu-icon fa fa-user-plus" aria-hidden="true"></i><span class="menu-label">Sign Up</span></a>
				</li>
				<li>
					<a class="menu-item" href="<?php echo base_url($this->router->directory.'user/login'); ?>"><i class="menu-icon fa fa-sign-in" aria-hidden="true"></i><span class="menu-label">Login</span></a>
				</li>
			<?php } ?>
    </ul>

</aside>