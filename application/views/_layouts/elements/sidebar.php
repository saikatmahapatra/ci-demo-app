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
            <a class="menu-item" href="<?php echo base_url();?>"><i class="menu-icon fa fa-dashboard"></i><span class="menu-label">Dashboard</span></a>
        </li>
        <li class="treeview">
            <a class="menu-item" href="#" data-toggle="treeview"><i class="menu-icon fa fa-laptop"></i><span class="menu-label">UI Elements</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?php echo base_url('#');?>"><i class="icon fa fa-circle-o"></i> Bootstrap Elements</a></li>
                <li><a class="treeview-item" href="<?php echo base_url('#');?>" target="_blank" rel="noopener"><i class="icon fa fa-circle-o"></i> Font Icons</a></li>
                <li><a class="treeview-item" href="<?php echo base_url('#');?>"><i class="icon fa fa-circle-o"></i> Cards</a></li>
                <li><a class="treeview-item" href="<?php echo base_url('#');?>"><i class="icon fa fa-circle-o"></i> Widgets</a></li>
            </ul>
        </li>
    </ul>

</aside>