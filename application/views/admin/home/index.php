<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<div class="row heading-container mb-3">
    <div class="col-12">
        <h1 class="page-title"><?php echo isset($page_title)? $page_title:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<div class="row text-center home-card">
    <div class="col-sm-6 col-md-3">
        <div class="card my-1 border border-danger">
            <div class="card-header text-danger">
                <i class="icon fa fa-lg fa-3x fa-calendar-check-o"></i>
            </div>
            <div class="card-body p-0 pt-2">
                <h6 class="card-title mb-0">Data 1</h6>
                <p class="card-text">28-Apr</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card my-1 border border-info">
            <div class="card-header text-info">
                <i class="icon fa fa-lg fa-3x fa-user-o"></i>
            </div>
            <div class="card-body p-0 pt-2">
                <h6 class="card-title mb-0">Data 2</h6>
                <p class="card-text">38</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card my-1 border border-warning">
            <div class="card-header text-warning">
                <i class="icon fa fa-lg fa-3x fa-cubes"></i>
            </div>
            <div class="card-body p-0 pt-2">
                <h6 class="card-title mb-0">Data 3</h6>
                <p class="card-text">40</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card my-1 border border-success">
            <div class="card-header text-success">
                <i class="icon fa fa-lg fa-3x fa-clock-o"></i>
            </div>
            <div class="card-body p-0 pt-2">
                <h6 class="card-title mb-0">Data 4</h6>
                <p class="card-text">1</p>
            </div>
        </div>
    </div>
</div>