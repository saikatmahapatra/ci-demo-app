<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
    <div class="col-lg-8 mb-3">
        <div class="card news-card">
            <div class="card-header h6">
            <i class="fa fa-fw fa-newspaper-o fa-lg" aria-hidden="true"></i> Recent Posts
            </div>
            <div class="card-body">
                <?php if( isset($data_rows) && sizeof($data_rows) > 0 ){ ?>
                <ul class="list-group list-group-flush">
                <?php foreach($data_rows as $key=>$row) { ?>
                    <li class="list-group-item">
                        <div class="subject-title"><a target="blank" href="<?php echo base_url($this->router->class.'/details/'.$row['id']);?>"><?php echo isset($row['content_title']) ? $row['content_title'] : '';?></a></div>
                        <div class="text-muted small">
                            <?php echo $content_type[$row['content_type']]['text']; ?>
                            <?php echo isset($row['user_firstname']) ? "By ".$row['user_firstname'] : '';?>
                            <?php echo isset($row['user_lastname']) ? $row['user_lastname'].", " : '';?>
                            <?php echo $this->common_lib->display_date($row['content_created_on'],true,NULL,'d-M-Y h:i:s a'); ?>
                        </div>
                        <div class="mb-0 lh-125" style="max-height: 120px; overflow: hidden;">
                            <?php echo isset($row['content_text']) ? ($this->common_lib->remove_empty_p($row['content_text'])) : '';?>
                        </div>
                    </li>
                <?php }  ?>
                </ul>
                <?php } ?>
            </div>
            <div class="card-footer text-center">
                <?php echo $pagination_link;?>
            </div>
        </div><!--/.card-->
    </div>
    
    <div class="col-lg-4 mb-3">
        <div class="card card-stat">
            <div class="card-header h6">
            <i class="fa fa-fw fa-line-chart fa-lg" aria-hidden="true"></i> Statistics
            </div>
            <div class="card-body">
                <div class="d-flex flex-column">
                    <?php if ($this->session->userdata['sess_user']['user_role'] == 1) { ?>
                    
                        <div class="stat media">
                            <a title="View Details" class="d-flex" href="<?php echo base_url($this->router->directory.'user/manage'); ?>">
                            <i class="fa fa-fw fa-user fa-2x align-middle" aria-hidden="true" style="color: #0062cc;"></i>
                            <div class="media-body">
                                <span class="count"><?php echo isset($user_count) ? $user_count['data_rows'][0]['total'] : '0'; ?></span> customers
                            </div>
                            </a>
                        </div>

                        <div class="stat media">
                            <a title="View Details" class="d-flex" href="<?php echo base_url($this->router->directory.'cms'); ?>">
                            <i class="fa fa-fw fa-puzzle-piece fa-2x align-middle" aria-hidden="true" style="color: #007bff;"></i>
                            <div class="media-body">
                                <span class="count"><?php echo isset($post_count) ? $post_count['data_rows'][0]['total'] : '0'; ?></span> posts
                            </div>
                            </a>
                        </div>

                        <div class="stat media">
                            <a title="View Details" class="d-flex" href="<?php echo base_url($this->router->directory.'order'); ?>">
                            <i class="fa fa-fw fa-shopping-cart fa-2x align-middle" aria-hidden="true" style="color: #495057;"></i>
                            <div class="media-body">
                                <span class="count"><?php echo isset($order_count) ? $order_count['data_rows'][0]['total'] : '0'; ?></span> orders
                            </div>
                            </a>
                        </div>

                        <div class="stat media">
                            <a title="View Details" class="d-flex" href="#">
                                <i class="fa fa-fw fa-inr fa-2x align-middle" aria-hidden="true" style="color: #fd7e14;"></i>
                                <div class="media-body">
                                    <span class="count">99,706</span> some analysis
                                </div>
                            </a>
                        </div>
                    <?php } else{ ?>
                        <!-- <p>
                            Oops! There are nothing to display here for you.
                        </p> -->
                    <?php } ?>

                    <div class="stat media">
                        <a title="View Details" class="d-flex" href="#">
                            <i class="fa fa-fw fa-check-square-o fa-2x align-middle" aria-hidden="true" style="color: #6f42c1;"></i>
                            <div class="media-body">
                                <span class="count">91,600</span> orders deliverd in this month across all locations
                            </div>
                        </a>
                    </div>
                </div><!--/.flex-column-->
            </div><!--/.card-body-->
            <div class="card-footer text-center text-muted small">
            <i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Updated on <?php echo date('d-M-Y h:i:s a');?>
            </div>
        </div><!--/.card-->
    </div>
</div>