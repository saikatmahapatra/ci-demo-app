<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>

<div class="row heading-container mb-3">
    <div class="col-12">
        <h1 class="h3 mb-3 font-weight-normal"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
</div><!--/.heading-container-->

<?php
$count = 1;
foreach($data_rows as $key=>$row){
	?>
	<?php if ($count%3 == 1){ echo '<div class="row my-3">'; } ?>
		<div class="col-md-4 mb-2" data-id="<?php echo $row['id'];?>">
			<div data-cms-type="<?php echo $row['pagecontent_type'];?>" class="card-news pl-2">
				<div class="card-news-header h4">
					<a class="" href="<?php echo base_url($this->router->directory.$this->router->class.'/details/'.$row['id']);?>"><?php echo isset($row['pagecontent_title']) ? $row['pagecontent_title'] : '';?></a>
				</div>
				<div class="card-news-sig text-muted small">
					<div><?php echo ucwords($row['pagecontent_type']);?></div>
					<div>
						<?php echo isset($row['user_firstname']) ? "By ".$row['user_firstname'] : '';?>
						<?php echo isset($row['user_lastname']) ? $row['user_lastname'].", " : '';?>
						<?php echo $this->common_lib->display_date($row['pagecontent_created_on'],true); ?>
					</div>
				</div>
				<div class="card-news-body">
					<?php echo isset($row['pagecontent_text']) ? word_limiter($row['pagecontent_text'],30) : '';?>
					<a class="d-none" href="<?php echo base_url($this->router->directory.$this->router->class.'/details/'.$row['id']);?>">Read more</a>				
				</div>
			</div>
		<?php if ($count%3 == 0){ echo '</div>'; } ?>
	</div>
	<?php
	$count++;
}
//if ($count%3 != 1) echo "</div>"; 
?>

<div class="row">
<div class="col-md-12"><?php echo $pagination_link; ?></div>
</div>
