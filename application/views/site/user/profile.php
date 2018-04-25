<?php
$row = $row[0];
//print_r($address);
?>
<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Profile</h1>               
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
        // Show server side messages
        if (isset($alert_message)) {
            $html_alert_ui = '';
            $html_alert_ui.='<div class="alert-container">';
            $html_alert_ui.='<div class="auto-closable-alert alert ' . $alert_message_css . ' alert-dismissable">';
            $html_alert_ui.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html_alert_ui.=$alert_message;
            $html_alert_ui.='</div>';
            $html_alert_ui.='</div>';
            echo $html_alert_ui;
        }
        ?>              
    </div>
</div>


<div class="row">
    <div class="col-md-8">
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object dp img-circle" src="<?php echo site_url('assets/dist/img/avatar_2x.png'); ?>">
            </a>
            <div class="media-body">
                <h4 class="media-heading">
				<?php
					echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
					echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
					echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
				?>
				<small> India</small></h4>
                <h5><?php echo isset($row['role_name']) ? $row['role_name'] : ''; ?> at <a href="<?php echo site_url();?>">something.com</a></h5>
				<div class="user_intro">
					<?php echo (isset($row['user_intro']) && strlen($row['user_intro'])>0) ? $row['user_intro'] : '<span class="text-greyed-out">Describe who you are...</span>'; ?>
					<a href="<?php echo site_url('user/edit_profile');?>">Edit</a>
				</div>
                <hr style="margin:8px auto">
                <span class="label label-default">HTML5/CSS3</span>
                <span class="label label-default">jQuery</span>
                <span class="label label-info">CakePHP</span>
                <span class="label label-default">Android</span>
            </div>
        </div>

    </div>
</div>
<br>
<div class="row">
    <div class="col-md-8">        
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                        <i class="more-less glyphicon glyphicon-minus"></i>
                        Basic Info
                    </a>
                </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">Name</div>
                        <div class="col-md-8">
                            <?php
                            echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
                            echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
                            echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
                            ?>
                        </div>                        
                    </div><!--/.row-->
                    <div class="row">
                        <div class="col-md-4">Email</div>
                        <div class="col-md-8">
                            <a mailto="<?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?>"><?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?></a>
                        </div>                        
                    </div><!--/.row-->
                    <div class="row">
                        <div class="col-md-4">Phone # 1</div>
                        <div class="col-md-8"><?php echo isset($row['user_mobile_phone1']) ? $row['user_mobile_phone1'] : ''; ?></div>                        
                    </div><!--/.row-->
                    <div class="row">
                        <div class="col-md-4">Phone # 2</div>
                        <div class="col-md-8"><?php echo isset($row['user_mobile_phone2']) ? $row['user_mobile_phone2'] : ''; ?></div>                        
                    </div><!--/.row-->
                    <div class="row">
                        <div class="col-md-4">Gender</div>
                        <div class="col-md-8"><?php echo isset($row['user_gender']) ? (($row['user_gender'] == 'M') ? 'Male' : 'Female') : ''; ?></div>                        
                    </div><!--/.row-->
                    <div class="row">
                        <div class="col-md-4">Date of Birth</div>
                        <div class="col-md-8"><?php echo isset($row['user_dob']) ? date('d-m-Y', strtotime($row['user_dob'])) : ''; ?></div>                        
                    </div><!--/.row-->
                    <div class="row">
                        <div class="col-md-4">Date of Registration</div>
                        <div class="col-md-8"><?php echo isset($row['user_registration_date']) ? date('d-m-Y h:i:s a', strtotime($row['user_registration_date'])) : ''; ?></div>                        
                    </div><!--/.row-->
					<a class="btn btn-dark  pull-right" href="<?php echo site_url('user/edit_profile');?>">Edit</a>
                </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                        <i class="more-less glyphicon glyphicon-plus"></i>
                        Addresses
                    </a>
                </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if(isset($address)){
                        foreach($address as $key=>$addr){
?>
                        <div class="row">
                            <div class="col-md-4"><?php echo isset($address_type[$addr['address_type']])?$address_type[$addr['address_type']]:'Address'; ?></div>
                            <div class="col-md-8">
                                <div class="text-bold">
                                    <?php echo isset($addr['name'])?$addr['name'].'&nbsp;' :'';?>
                                    <?php echo isset($addr['phone1'])?$addr['phone1']:'';?>
                                </div>
                                <div>
									<?php echo isset($addr['address']) ? $addr['address'] : '';?>
									<?php echo isset($addr['locality'])? ', '.$addr['address'] : '';?>
									<?php echo isset($addr['city']) ? ', '.$addr['city'].', ' : '';?>                                    
								</div>
								<div>
									<?php echo isset($addr['state']) ? $addr['state'] : '';?>
									<?php echo isset($addr['zip']) ? ' - <span class="text-bold">'.$addr['zip'].'</span>' : '';?>
								</div>
								<div>								
								<a href="<?php echo site_url('user/edit_address/'.$addr["id"]);?>" class="btn btn-dark">Edit</a>
								<a href="<?php echo site_url('user/delete_address/'.$addr["id"]);?>" class="btn btn-dark">Delete</a>
								</div>
							</div>
                        </div><!--/.row-->
                        <?php
                        }
                    }?>
					<a class="btn btn-dark  pull-right" href="<?php echo site_url('user/add_address');?>">Add New</a>
                </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                        <i class="more-less glyphicon glyphicon-plus"></i>
                        Others
						
                    </a>
                </h4>
                </div>
                <div id="collapse3" class="panel-collapse collapse">
                <div class="panel-body">
						<a class="btn btn-dark  pull-right" href="<?php echo site_url('user/edit_profile');?>">Edit</a>
						<a class="btn btn-dark  pull-right" href="<?php echo site_url('user/edit_profile');?>">Add New</a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>