<div class="row heading-container">
    <div class="col-md-12">
        <h1 class="page-heading error-heading">
            <i class="icon fa fa-warning" aria-hidden="true"></i>
            <?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<div class="row">
    <div class="col-md-12">
        <p class="error-info">
            You are not authorized to access the link you clicked. For security reason you have been logged out. Please contact to system administrator for more details.
        </p>
        <a href="<?php echo base_url($this->router->directory.'user/login');?>" class="btn btn-primary my-4"><i class="fa fa-fw fa-check-circle" aria-hidden="true"></i> Please login to continue...</a>
        
    </div>
</div>