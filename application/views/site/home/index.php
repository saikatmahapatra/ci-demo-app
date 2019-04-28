<div class="row heading-container mb-3">
    <div class="col-12">
        <h1 class="page-heading"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
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
<div class="card mt-4">
  <div class="card-header h5">
  <i class="fa fa-newspaper-o text-primary" aria-hidden="true"></i> News & Updates
  </div>
  <div class="card-body">
    <?php foreach($data_rows as $key=>$row) { ?>
        <div class="my-2 py-2 border-bottom border-gray">
            <div class="mb-0 lh-125" style="max-height: 130px; overflow: hidden;">
                    <div class="text-gray-dark h4"><a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/details/'.$row['id']);?>"><?php echo isset($row['pagecontent_title']) ? $row['pagecontent_title'] : '';?></a></div>
                    <strong class="text-muted text-gray-dark small">
                        <?php echo $content_type[$row['pagecontent_type']]['text']; ?>
                        <?php echo isset($row['user_firstname']) ? "By ".$row['user_firstname'] : '';?>
                        <?php echo isset($row['user_lastname']) ? $row['user_lastname'].", " : '';?>
                        <?php echo $this->common_lib->display_date($row['pagecontent_created_on'],true,null,'d-M-Y h:i:sa'); ?>
                    </strong>
                    <?php echo isset($row['pagecontent_text']) ? $this->common_lib->remove_empty_p($row['pagecontent_text']) : '';?>
            </div>
        </div>
    <?php } ?>
    <div class="my-3">
        <?php echo $pagination_link;?>
    </div>
  </div>
</div>
<?php } ?>