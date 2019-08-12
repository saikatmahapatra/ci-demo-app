<?php
   $row = $row[0];
   $user_row = $row;
   //print_r($address);
?>
<?php 
	// Display profile tab
	$display_address = false;
	$display_education = false;
	$display_experience = false;
	if($is_self_account !== true){
		if($this->common_lib->is_auth(array('view-user-address'),false) == true){
			$display_address = true;
		}else{
			$display_address = false;
		}
	}else{
		$display_address = true;
	}

	if($is_self_account !== true){
		if($this->common_lib->is_auth(array('view-user-education'),false) == true){
			$display_education = true;
		}else{
			$display_education = false;
		}
	}else{
		$display_education = true;
	}
	
	if($is_self_account !== true){
		if($this->common_lib->is_auth(array('view-user-exp'),false) == true){
			$display_experience = true;
		}else{
			$display_experience = false;
		}
	}else{
		$display_experience = true;
	}
?>
<?php //echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
<h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Page Heading'; ?></h1>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header h6">
				<i class="fa fa-user-circle " aria-hidden="true"></i>
				<?php echo isset($row['user_emp_id']) ? 'Customer Code - ABC/'.$row['user_emp_id'] : ''; ?>
			</div><!--/.card-header-->
			<div class="card-body">
				<?php
				// Show server side flash messages
				if (isset($alert_message)) {
					$html_alert_ui = '';                
					$html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$alert_message.'</div>';
					echo $html_alert_ui;
				}
				?>
				<div class="row">
					<div class="col-lg-3 text-center mb-3">
						<?php   
							$img_src = "";
							$default_path = "assets/dist/img/default_user.jpg";
							if(isset($profile_pic)){					
								$user_dp = "assets/uploads/user/profile_pic/".$profile_pic;					
								if (file_exists(FCPATH . $user_dp)) {
									$img_src = $user_dp;
								}else{
									$img_src = $default_path;
								}
							}else{
								$img_src = $default_path;
							}
						?>
						<img class="dp rounded mx-auto d-block img-thumbnail" src="<?php echo base_url($img_src);?>">
						<?php if($is_self_account == true) { ?>
						<a class="small" href="<?php echo base_url($this->router->directory.$this->router->class.'/profile_pic');?>" data-toggle="tooltip" title="Change or remove this photo"><i class="fa fa-camera"></i> Change</a>
						<?php } ?>
						<div class="h5 my-2">
							<?php
								//echo isset($row['user_title']) ? $row['user_title'] . '&nbsp;' : '';
								echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
								echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
								echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
							?>
						</div>
						<div class="">
							<a class="" href="mailto:<?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?>"><?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?></a>
						</div>
						<div class="">
							<a class="" href="tel:<?php echo isset($row['user_phone1']) ? $row['user_phone1'] : ''; ?>"><?php echo isset($row['user_phone1']) ? $row['user_phone1'] : ''; ?></a>
							<a href="tel:<?php echo isset($row['user_phone2']) ? $row['user_phone2'] : ''; ?>"><?php echo isset($row['user_phone2']) ? ' / '.$row['user_phone2'] : ''; ?></a>        
						</div>
						<div class="">Employee ID - <?php echo isset($row['user_emp_id']) ? $row['user_emp_id'] : ''; ?></div>
						<div class="">Designation - <?php echo isset($row['designation_name']) ? $row['designation_name'] : ''; ?></div>
						<div class="">Department - <?php echo isset($row['department_name']) ? $row['department_name'] : ''; ?></div>
					</div><!--/.col-md-3-->
					<div class="col-lg-9">
						<nav>
							<div class="nav nav-tabs ci-nav-tab" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="nav-1" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true"><i class="fa fa-info-circle" aria-hidden="true"></i> Basic Info</a>

								<?php if($display_address == true){  ?>
								<a class="nav-item nav-link" id="nav-2" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false"><i class="fa fa-map-marker" aria-hidden="true"></i> Address</a>
								<?php } ?>

								<?php if($display_education == true){  ?>
								<a class="nav-item nav-link" id="nav-3" data-toggle="tab" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false"><i class="fa fa-certificate" aria-hidden="true"></i> Education</a>
								<?php } ?>

								<?php if($display_experience == true){  ?>
								<a class="nav-item nav-link" id="nav-4" data-toggle="tab" href="#tab-4" role="tab" aria-controls="tab-4" aria-selected="false"><i class="fa fa-briefcase" aria-hidden="true"></i> Experiences</a>
								<?php } ?>
							</div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="nav-1">
								<div class="row mt-3">
									<div class="col-md-12">
									<?php if($is_self_account == true) { ?>
									<a class="btn btn-outline-secondary btn-sm mb-3" href="<?php echo base_url($this->router->directory.$this->router->class.'/edit_profile');?>"><i class="fa fa-edit" aria-hidden="true"></i> Edit Basic Info</a>
									<?php } ?>
									<!--<h6>Basic Info</h6><hr>-->		
									<dl class="row">
										<dt class="col-md-2">Name</dt>
										<dd class="col-md-4">
											<?php
											echo isset($row['user_title']) ? $row['user_title'] . '&nbsp;' : '';
											echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
											echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
											echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
											?>
										</dd>
										<dt class="col-md-2">Employee ID</dt>
										<dd class="col-md-4"><?php echo isset($row['user_emp_id']) ? $row['user_emp_id'] : '-'; ?></dd>
									
										<dt class="col-md-2">Date of Joining</dt>
										<dd class="col-md-4"><?php echo isset($row['user_doj']) ? $this->common_lib->display_date($row['user_doj']) : '-'; ?></dd>
										<dt class="col-md-2">Designation</dt>
										<dd class="col-md-4"><?php echo isset($row['designation_name']) ? $row['designation_name'] : '-'; ?></dd>
									
										<dt class="col-md-2">Email (Office)</dt>
										<dd class="col-md-4"><a href="mailto:<?php echo isset($row['user_email']) ? $row['user_email'] : '-'; ?>"><?php echo isset($row['user_email']) ? $row['user_email'] : '-'; ?></a></dd>
										<dt class="col-md-2">Mobile (Office)</dt>
										<dd class="col-md-4"><?php echo isset($row['user_phone2']) ? $row['user_phone2'] : '-'; ?></dd>
									
										<dt class="col-md-2">Email (Personal)</dt>
										<dd class="col-md-4"><a href="mailto:<?php echo isset($row['user_email_secondary']) ? $row['user_email_secondary'] : '-'; ?>"><?php echo isset($row['user_email_secondary']) ? $row['user_email_secondary'] : '-'; ?></a></dd>			
										<dt class="col-md-2">Mobile (Personal)</dt>
										<dd class="col-md-4"><?php echo isset($row['user_phone1']) ? $row['user_phone1'] : '-'; ?></dd>						
										
										<dt class="col-md-2"><?php echo ($is_self_account == true) ? "Date of Birth" : "Birth Day";?></dt>
										<dd class="col-md-4">
										<?php if($is_self_account == true) {?>	
										<?php echo isset($row['user_dob']) ? $this->common_lib->display_date($row['user_dob']) : '-'; ?>
										<?php } else{?>
										<?php echo isset($row['user_dob']) ? $this->common_lib->display_date($row['user_dob'],NULL, TRUE) : '-'; ?>
										<?php } ?>
										</dd>

										<dt class="col-md-2">Gender</dt>
										<dd class="col-md-4"><?php echo isset($row['user_gender']) ? (($row['user_gender'] == 'M') ? 'Male' : 'Female') : ''; ?></dd>
									
										<dt class="col-md-2">Blood Group</dt>
										<dd class="col-md-4"><?php echo isset($row['user_blood_group']) ? $row['user_blood_group'] : ''; ?></dd>								
									</dl><!--/dl.row-->
									
									</div>
								</div>
							</div> <!--/#tab-1-->
							
							<?php if($display_address == true){  ?>
							<div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="nav-2">
								<div class="row mt-3">
									<div class="col-md-12">
										<?php if($is_self_account == true) { ?>
										<a class="btn btn-outline-success btn-sm mb-3" href="<?php echo base_url($this->router->directory.$this->router->class.'/add_address');?>"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
										<?php } ?>

										<div class="table-responsive-sm">
												<table class="table table-striped">
													<thead class="thead-light">
														<tr>
														<th scope="col">Address Type</th>
														<th scope="col">Address</th>
														<?php if($is_self_account == true) { ?>
														<th scope="col"></th>
														<?php } ?>
														</tr>
													</thead>
													<tbody>
													<?php if(isset($address)){
															foreach($address as $key=>$addr){
															?>
															<tr>
																<td>
																	<?php echo isset($address_type[$addr['address_type']]) ? $address_type[$addr['address_type']] : 'Address'; ?>
																</td>
																<td>
																	<?php //echo isset($addr['name'])? $addr['name'].',&nbsp;' :'';?>
																	<?php echo isset($addr['address']) ? $addr['address'] : '';?>
																	<?php echo isset($addr['locality'])? ', '.$addr['locality'] : '';?>
																	<?php echo isset($addr['city']) ? ', '.$addr['city'].', ' : '';?>
																	<?php echo isset($addr['state_name']) ? $addr['state_name'] : '';?>
																	<?php echo isset($addr['zip']) ? ' - '.$addr['zip'] : '';?>  
																	<?php echo isset($addr['phone1'])? '<div>Phone: '.$addr['phone1'].'</div> ':'';?>
																	<?php echo isset($addr['landmark'])? '<div>Landmark: '.$addr['landmark'].'</div> ':'';?>
																</td>
																<?php if($is_self_account == true) { ?>
																<td>
																	<a href="<?php echo base_url($this->router->directory.$this->router->class.'/edit_address/'.$addr["id"]);?>" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-lg fa-edit" aria-hidden="true"></i></a>
																	<!--<a href="<?php echo base_url($this->router->directory.$this->router->class.'/delete_address/'.$addr["id"]);?>" class="btn btn-outline-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>-->
																</td>
																<?php } ?>
															</tr>
															<?php
															}
														}?>
													</tbody>
												</table>
											</div><!--/.table-responsive-sm-->
									</div>
								</div>
							</div> <!--/#tab-2-->
							<?php } ?>
							
							<?php if($display_education == true){  ?>
							<div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="nav-3">
								<div class="row mt-3">
									<div class="col-md-12">
										<?php if($is_self_account == true) { ?>
										<a class="btn btn-outline-success btn-sm mb-3" href="<?php echo base_url($this->router->directory.$this->router->class.'/add_education');?>"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
										<?php } ?>
											<div class="table-responsive-sm">
												<table class="table table-striped">
													<thead class="thead-light">
														<tr>
														<th scope="col">Degree & Specialization</th>
														<th scope="col">University/Board/Council</th>
														<th scope="col">Duration</th>
														<th scope="col">Marks</th>
														<?php if($is_self_account == true) { ?>
														<th scope="col"></th>
														<?php } ?>
														</tr>
													</thead>
													<tbody>
													<?php if(isset($education)){
															foreach($education as $key=>$edu){
															?>
															<tr>
																<td>
																	<?php echo isset($edu['qualification_name'])?$edu['qualification_name']: ' ';?> - <?php echo isset($edu['degree_name'])?$edu['degree_name']:'';?><br>
																	<?php echo isset($edu['specialization_name'])?$edu['specialization_name']:'';?>
																</td>
																<td><?php echo isset($edu['institute_name']) ? $edu['institute_name']: '';?></td>
																<td><?php echo isset($edu['academic_from_year']) ? $edu['academic_from_year'].'-'.$edu['academic_to_year']:'';?></td>
																<td><?php echo isset($edu['academic_marks_percentage'])?$edu['academic_marks_percentage'].' %':'';?></td>
																<?php if($is_self_account == true) { ?>
																<td><a href="<?php echo base_url($this->router->directory.$this->router->class.'/edit_education/'.$edu["id"]);?>" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-lg fa-edit" aria-hidden="true"></i></a></td>
																<?php } ?>
															</tr>
															<?php
															}
														}?>
													</tbody>
												</table>
											</div><!--/.table-responsive-sm-->
									</div>
								</div>
							</div> <!--/#tab-3-->
							<?php } ?>
							
							<?php if($display_experience == true){  ?>
							<div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="nav-4">
								<div class="row mt-3">
									<div class="col-md-12">
									<?php if($is_self_account == true) { ?>
										<a class="btn btn-outline-success btn-sm mb-3" href="<?php echo base_url($this->router->directory.$this->router->class.'/add_work_experience');?>"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
									<?php } ?>
											<div class="table-responsive-sm">
												<table class="table table-striped">
													<thead class="thead-light">
														<tr>
															<th scope="col">Employer</th>
															<th scope="col">Designation/Role</th>
															<th scope="col">From</th>
															<th scope="col">To</th>
															<?php if($is_self_account == true) { ?>
															<th scope="col"></th>
															<?php } ?>
														</tr>
														<tr>
															<td>ABC Corporation</td>
															<td><?php echo isset($row['designation_name']) ? $row['designation_name'] : '-'; ?></td>
															<td><?php echo isset($row['user_doj']) ? $this->common_lib->display_date($row['user_doj']) : '-'; ?></td>
															<td><?php echo isset($row['user_dor']) ? $this->common_lib->display_date($row['user_dor']) : '-'; ?></td>
															<?php if($is_self_account == true) { ?>
															<td>-</td>
															<?php } ?>
														</tr>
													</thead>
													<tbody>
														<?php if(isset($job_exp)){
															foreach($job_exp as $key=>$row){
															?>
																<tr>
																	<td><?php echo isset($row['company_name'])? $row['company_name']: ' ';?></td>
																	<td><?php echo isset($row['designation_name']) ? $row['designation_name'] : '-'; ?></td>
																	<td><?php echo isset($row['from_date']) ? $this->common_lib->display_date($row['from_date']) :'';?></td>
																	<td><?php echo isset($row['to_date']) ? $this->common_lib->display_date($row['to_date']) :'';?></td>											<?php if($is_self_account == true) { ?>				
																	<td><a href="<?php echo base_url($this->router->directory.$this->router->class.'/edit_work_experience/'.$row["id"]);?>" class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-lg fa-edit" aria-hidden="true"></i></a></td>
																	<?php } ?>
																</tr>
															<?php
															}
														}?>
													</tbody>
												</table>
											</div><!--/.table-responsive-sm-->
									</div>
								</div>
							</div><!--/#tab-4-->
							<?php } ?>

						</div><!--/.tab-content-->
					</div><!--/.col-md-9-->
				</div><!--/.row-->
			</div><!--./card-body-->
			<div class="card-footer text-center text-muted small">
				<i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Last login on <?php echo isset($user_row['user_login_date_time']) ? $this->common_lib->display_date($user_row['user_login_date_time'],true,null,'d-M-Y h:i:s a') : '-'; ?>
			</div>
		</div><!--/.card-->
		
	</div><!--/.col-->
</div><!--/.row-->