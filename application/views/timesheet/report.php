<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
	<div class="col-lg-12">
		<?php echo isset($alert_message) ? $alert_message : ''; ?>
				
		<div class="row mb-3">
			<div class="col-lg-12">
			<?php echo form_open(current_url(), array( 'method' => 'get','class'=>'ci-form','name' => '','id' => 'timesheet-search-form')); ?>
			<?php echo form_hidden('form_action', 'search'); ?>		  
				<div class="form-row">
					<div class="form-group col-lg-4 ci-select2">
						<label for="q_emp" class="">Employee</label>
						<?php echo form_dropdown('q_emp', $user_arr, $this->input->get_post('q_emp'),array('class' => 'form-control select2-control', 'id'=>'q_emp')); ?> 
						<?php echo form_error('q_emp'); ?>
					</div>
					
					<div class="form-group col-lg-4 ci-select2">
						<label for="q_project" class="">Project</label>
						<?php echo form_dropdown('q_project', $project_arr, $this->input->get_post('q_project'),array('class' => 'form-control select2-control','id'=>'q_project')); ?> 
						<?php echo form_error('q_project'); ?>
					</div>

					<div class="form-group col-lg-2">									
						<label for="from_date" class="required">From</label>
						<?php 
							$first_day_this_month = date('01-m-Y');
							$last_day_this_month  = date('t-m-Y');
						?>
						<?php echo form_input(array('name' => 'from_date','value' => (isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : $first_day_this_month),'id' => 'from_date','class' => 'form-control report-datepicker', 'placeholder' => 'dd-mm-yyyy','readonly'=>true));?>
						<?php echo form_error('from_date'); ?>
					</div>
				
					<div class="form-group col-lg-2">									
						<label for="to_date" class="required">To</label>
						<?php echo form_input(array('name' => 'to_date','value' => (isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : $last_day_this_month),'class' => 'form-control report-datepicker','id' => 'to_date','placeholder' => 'dd-mm-yyyy','readonly'=>true));?>
						<?php echo form_error('to_date'); ?>
					</div>


				</div>					
				<?php echo form_button(array('name' => 'submit_btn','type' => 'submit','content' => '<i class="fa fa-fw fa-search"></i> Search','class' => 'btn btn-primary'));?>
				<?php echo form_button(array('name' => 'reset_btn','type' => 'reset','content' => 'Reset','class' => 'btn btn-secondary','id'=>'reset_timesheet_form'));?>
					
				<?php echo form_close(); ?>
			</div>
		</div>
		
		<?php if(isset($data_rows) && sizeof($data_rows)>0){ ?>
		<div class="table-responsive">
			<?php echo form_open(current_url(), array('method' => 'GET', 'class' => 'form-inline my-3 ml-2', 'name' => 'download_data')); ?>
				<input type="hidden" name="form_action" value="search">
				<input type="hidden" name="form_action_primary" value="download">
				<input type="hidden" name="q_emp" value="<?php echo $this->input->get('q_emp');?>">
				<input type="hidden" name="q_project" value="<?php echo $this->input->get('q_project');?>">
				<input type="hidden" name="from_date" value="<?php echo $this->input->get('from_date');?>">
				<input type="hidden" name="to_date" value="<?php echo $this->input->get('to_date');?>">
				<button type="submit" class="btn btn-sm btn-outline-secondary" title="Download"> <i class="fa fa-fw fa-download" aria-hidden="true"></i> Download as Excel</button>
			<?php echo form_close(); ?>

			<table class="table table-striped">
				<thead class="thead-dark">
					<tr>
						<th scope="col" style="width:10%;">Date</th>
						<th scope="col" style="width:15%;">Employee</th>
						<th scope="col" style="width:20%;">Project</th>
						<th scope="col" style="width:20%;">Activity</th>
						<th scope="col" style="width:5%;">Hours</th>
						<th scope="col" style="width:30%;">Description</th>
						
					</tr>
				</thead>
				<tbody>
						<?php foreach($data_rows as $row){ ?>
							<tr>
								<td><?php echo $this->app_lib->display_date($row['timesheet_date']);?></td>
								<td><?php echo $row['user_firstname'].' '.$row['user_lastname'];?></td>
								<td><?php echo $row['project_number'].'-'.$row['project_name'];?></td>
								<td><?php echo $row['task_activity_name'];?></td>
								<td><?php echo $row['timesheet_hours'];?></td>
								<td><?php echo $row['timesheet_description'];?></td>
							</tr>
						<?php } ?>
			
				</tbody>
				<tfoot>
					<tr>
						<th scope="col">Date</th>
						<th scope="col">Employee</th>
						<th scope="col">Project</th>
						<th scope="col">Activity</th>
						<th scope="col">Hours</th>
						<th scope="col">Description</th>								
					</tr>
				</tfoot>
			</table>					
		</div>
		<?php } else {?>
			<div class="alert alert-warning">No result found</div>
		<?php }?>
		<?php echo isset($pagination_link) ? $pagination_link : ''; ?>
			
		</div>
	</div>
</div><!--/.row-->
