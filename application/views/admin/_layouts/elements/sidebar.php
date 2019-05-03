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
        <a href="<?php echo base_url($this->router->directory.'user/my_profile'); ?>"><img class="sidebar-user-avatar" src="<?php echo base_url($img_src);?>" alt="Profile Image" /></a>
        <div class="small">
            <p class="sidebar-user-name">
                <?php echo isset($this->session->userdata['sess_user']['user_firstname']) ? $this->session->userdata['sess_user']['user_firstname'] : '' ;?>                
            </p>
            <p class="sidebar-user-designation">
                <?php echo isset($this->session->userdata['sess_user']['user_role_name']) ? $this->session->userdata['sess_user']['user_role_name'] :'';?>
            </p>
        </div>
    </div>
    <?php } ?>

    <ul class="menu">
        <li>
            <a class="menu-item" href="<?php echo base_url($this->router->directory.'home'); ?>"><i class="menu-icon fa fa-dashboard" aria-hidden="true"></i><span class="menu-label">Dashboard</span></a>
        </li>

        <li class="treeview">
            <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-folder" aria-hidden="true"></i><span class="menu-label">CMS</span><i class="treeview-indicator fa fa-angle-right" aria-hidden="true"></i></a>
            <ul class="treeview-menu">                
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'cms/index');?>">Manage Contents</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'cms/add');?>">Add New Content</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'cms/manage_banner');?>">Manage Banners</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'cms/add_banner');?>">Add New Banner</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-folder" aria-hidden="true"></i><span class="menu-label">Manage Store</span><i class="treeview-indicator fa fa-angle-right" aria-hidden="true"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'order'); ?>">Order Management</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'product');?>">Manage Products</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'product/add');?>">Add Product</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'category');?>">Manage Categories</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'category/add');?>">Add Category</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-folder" aria-hidden="true"></i><span class="menu-label">Manage Users</span><i class="treeview-indicator fa fa-angle-right" aria-hidden="true"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'user/manage'); ?>">Manage Users</a></li>
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'user/create_account'); ?>">Create New User</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-folder" aria-hidden="true"></i><span class="menu-label">Report</span><i class="treeview-indicator fa fa-angle-right" aria-hidden="true"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?php echo base_url($this->router->directory.'timesheet/report'); ?>">Timesheet</a></li>
            </ul>
        </li>
    </ul>

</aside>