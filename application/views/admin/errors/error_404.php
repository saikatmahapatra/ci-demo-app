<div class="row heading-container">
    <div class="col-md-12">
        <h1 class="page-title error-heading"><i class="icon fa fa-warning" aria-hidden="true"></i>  <?php echo isset($page_title)? $page_title:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<div class="row">
    <div class="col-md-12">
        <p class="error-info">
            Sorry, the requested page could not found !
        </p>
        <a href="<?php echo base_url($this->router->directory);?>" class="btn btn-primary my-4"><i class="fa fa-home" aria-hidden="true"></i> Take me home</a>
        
    </div>
</div>