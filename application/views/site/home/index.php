<div class="row heading-container mb-3">
    <div class="col-12">
        <h1 class="page-title"><?php echo isset($page_title)? $page_title:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->
<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<?php if(isset($sliders) && sizeof($sliders)>0){ ?>
<div class="row my-2">
	<div class="col-md-12">
		<div id="demo" class="carousel slide" data-ride="carousel">
		<ul class="carousel-indicators">			
			<!--<li data-target="#demo" data-slide-to="0" class="active"></li>
			<li data-target="#demo" data-slide-to="1"></li>
			<li data-target="#demo" data-slide-to="2"></li>-->
			<?php foreach($sliders as $key=> $row){ ?>
				<li data-target="#demo" data-slide-to="<?php echo $key;?>"></li>
			<?php }?>
		</ul>
		<div class="carousel-inner w-100 h-100">			
			<?php foreach($sliders as $key=> $row){ ?>
				<?php
					$img_src = "";
					$default_path = "assets/src/img/no-image.png";
					if(isset($row['upload_file_name'])){					
						$banner_img = "assets/uploads/banner_img/".$row['upload_file_name'];					
						if (file_exists(FCPATH . $banner_img)) {
							$img_src = $banner_img;
						}else{
							$img_src = $default_path;
						}
					}else{
						$img_src = $default_path;
					}
				?>
				<div class="carousel-item <?php echo ($key==0)? 'active': '';?>">
					<img src="<?php echo base_url($img_src);?>">
					<div class="carousel-caption">
						<h3><?php echo isset($row['upload_text_1']) ? $row['upload_text_1'] : '';?></h3>
						<p><?php echo isset($row['upload_text_2']) ? $row['upload_text_2'] : '';?></p>
					</div>   
				</div><!--/.carousel-item-->
			<?php }?>
		</div><!--/.carousel-inner-->
		<a class="carousel-control-prev" href="#demo" data-slide="prev">
			<span class="carousel-control-prev-icon"></span>
		</a>
		<a class="carousel-control-next" href="#demo" data-slide="next">
			<span class="carousel-control-next-icon"></span>
		</a>
		</div>
	</div><!--/.col-->
</div><!--/.row-->
<?php } ?>

<?php if( isset($data_rows) && sizeof($data_rows) > 0 ){ ?>
	<div class="card news-card">
            <div class="card-header h6">
            <i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> News
            </div>
            <div class="card-body">
            
                <ul class="list-group list-group-flush">
                <?php foreach($data_rows as $key=>$row) { ?>
                    <li class="list-group-item">
                        <div class="subject-title"><a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/details/'.$row['id']);?>"><?php echo isset($row['content_title']) ? $row['content_title'] : '';?></a></div>
                        <div class="text-muted small">
                            <?php echo $content_type[$row['content_type']]['text']; ?>
                            <?php echo isset($row['user_firstname']) ? "By ".$row['user_firstname'] : '';?>
                            <?php echo isset($row['user_lastname']) ? $row['user_lastname'].", " : '';?>
                            <?php echo $this->common_lib->display_date($row['content_created_on'],true,null,'d-M-Y h:i:s a'); ?>
                        </div>
                        <div class="mb-0 lh-125" style="max-height: 120px; overflow: hidden;">
                            <?php echo isset($row['content_text']) ? ($this->common_lib->remove_empty_p($row['content_text'])) : '';?>
                        </div>
                    </li>
                <?php }  ?>
                </ul>

                <?php /*foreach($data_rows as $key=>$row) { ?>
                    <div class="my-2 py-2 border-bottom border-gray">
                        <div class="mb-0 lh-125" style="max-height: 130px; overflow: hidden;">
                                <div class="subject-title"><a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/details/'.$row['id']);?>"><?php echo isset($row['content_title']) ? $row['content_title'] : '';?></a></div>
                                <strong class="text-muted small">
                                    <?php echo $content_type[$row['content_type']]['text']; ?>
                                    <?php echo isset($row['user_firstname']) ? "By ".$row['user_firstname'] : '';?>
                                    <?php echo isset($row['user_lastname']) ? $row['user_lastname'].", " : '';?>
                                    <?php echo $this->common_lib->display_date($row['content_created_on'],true,null,'d-M-Y h:i:sa'); ?>
                                </strong>
                                <?php echo isset($row['content_text']) ? $this->common_lib->remove_empty_p($row['content_text']) : '';?>
                        </div>
                    </div>
                <?php } */ ?>
            </div>
            <div class="card-footer text-center">
                <?php echo $pagination_link;?>
            </div>
        </div><!--/.card-->
<?php } ?>