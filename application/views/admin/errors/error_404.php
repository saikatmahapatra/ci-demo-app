<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
    <div class="col-md-12">
        <p class="error-info">
            Sorry, the requested page could not found !
        </p>
        <a href="<?php echo base_url($this->router->directory);?>" class="btn btn-outline-primary">Go Back to Home</a>  
    </div>
</div>