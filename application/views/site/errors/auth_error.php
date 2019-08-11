<div class="row heading-container">
    <div class="col-md-12">
        <h1 class="page-title error-heading">
            <i class="icon fa fa-warning" aria-hidden="true"></i>
            <?php echo isset($page_title)? $page_title:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<div class="row">
    <div class="col-md-12">
        <p class="error-info">We're sorry! You are not authorized to access the link or page you are trying to access. Your session has been terminated forcefully.</p>
        <a href="<?php echo base_url($this->router->directory.'user/login');?>" class="btn btn-outline-primary">Please login to continue...</a>
        
    </div>
</div>