<?php
   $row = $row[0];
   //print_r($address);
?>
<div class="row heading-container">
    <div class="col-md-5">
        <h1 class="page-header"><?php echo isset($page_heading)? $page_heading:'Page Heading'; ?></h1>
    </div>
    <div class="col-md-7">
        <?php echo $breadcrumbs; ?>
    </div>
</div><!--/.heading-container-->

<div class="row">
   <div class="col-12">
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
   <div class="col-md-12">
   <div class="media">
   <img class="img align-self-start mr-3 dp" src="<?php echo site_url('assets/dist/img/avatar_2x.png'); ?>">
  <div class="media-body">
    <h5 class="mt-0">
        <?php
            echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
            echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
            echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
        ?>
    </h5>
    <div class="small"><?php echo isset($row['role_name']) ? $row['role_name'] : ''; ?></div>
    <div class="">
        <i class="fa fa-envelope-o" aria-hidden="true"></i> 
        <a class="mr-2" href="mailto:<?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?>"><?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?></a>
    </div>
    <div class="">
        <i class="fa fa-phone" aria-hidden="true"></i>
        <a class="mr-2" href="tel:<?php echo isset($row['user_mobile_phone1']) ? $row['user_mobile_phone1'] : ''; ?>"><?php echo isset($row['user_mobile_phone1']) ? $row['user_mobile_phone1'] : ''; ?></a>
        <a href="tel:<?php echo isset($row['user_mobile_phone2']) ? $row['user_mobile_phone2'] : ''; ?>"><?php echo isset($row['user_mobile_phone2']) ? $row['user_mobile_phone2'] : ''; ?></a>
        
    </div>            
    <div>
        <?php echo (isset($row['user_bio']) && strlen($row['user_bio'])>0) ? $row['user_bio'] : '<span class="text-greyed-out">Describe who you are...</span>'; ?>
        <a href="<?php echo site_url('admin/user/edit_profile');?>">Edit</a>
    </div>
    <hr style="m-8 auto">
    <span class="label label-default">some text</span>
    <span class="label label-default">some text</span>
  </div>
</div>
</div>
</div>


<br>

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-basic-tab" data-toggle="tab" href="#nav-basic" role="tab" aria-controls="nav-basic" aria-selected="true">Home</a>
	
    <a class="nav-item nav-link" id="nav-address-tab" data-toggle="tab" href="#nav-address" role="tab" aria-controls="nav-address" aria-selected="false">Address</a>
	
    <a class="nav-item nav-link" id="nav-education-tab" data-toggle="tab" href="#nav-education" role="tab" aria-controls="nav-education" aria-selected="false">Education</a>
	
  </div>
</nav>

<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">
	
  </div> <!--/#nav-basic-->
  
  <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
	
  </div> <!--/#nav-address-->
  <div class="tab-pane fade" id="nav-education" role="tabpanel" aria-labelledby="nav-education-tab">
	
  </div> <!--/#nav-education-->
</div>


<div class="row mb-3">
    <div class="col-md-12">
        <a class="btn btn-sm btn-outline-info float-right" href="<?php echo site_url('admin/user/edit_profile');?>"><i class="fa fa-edit" aria-hidden="true"></i></a>
        <h6>Basic Info</h6>        
        <hr>        
        <div class="row">
            <div class="col-sm-2">Name</div>
            <div class="col-sm-4">
            <?php
                echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
                echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
                echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
                ?>
            </div>
            <div class="col-sm-2">Email</div>
            <div class="col-sm-4">
            <a href="mailto:<?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?>"><?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?></a>
            </div>
        </div>
        <!--/.row-->
        
        <div class="row">
            <div class="col-sm-2">Phone # 1</div>
            <div class="col-sm-4"><?php echo isset($row['user_mobile_phone1']) ? $row['user_mobile_phone1'] : ''; ?></div>
            <div class="col-sm-2">Phone # 2</div>
            <div class="col-md-4"><?php echo isset($row['user_mobile_phone2']) ? $row['user_mobile_phone2'] : ''; ?></div>
        </div>
        <!--/.row-->
        
        <div class="row">
            <div class="col-sm-2">Date of Birth</div>
            <div class="col-sm-4"><?php echo isset($row['user_dob']) ? date('d-m-Y', strtotime($row['user_dob'])) : ''; ?></div>
            <div class="col-sm-2">Gender</div>
            <div class="col-sm-4"><?php echo isset($row['user_gender']) ? (($row['user_gender'] == 'M') ? 'Male' : 'Female') : ''; ?></div>
        </div>
        <!--/.row-->

        <div class="row">
            <div class="col-sm-2">Blood Group</div>
            <div class="col-sm-4"></div>
        </div>
        <!--/.row-->
    </div>
</div>


<div class="row mb-3">
    <div class="col-md-12">
    <a class="btn btn-sm btn-outline-success pull-right" href="<?php echo site_url('admin/user/add_address');?>"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
        <h6>Communication Address</h6><hr>
            <?php if(isset($address)){
                foreach($address as $key=>$addr){
                ?>
                    <div class="row mb-3">
                        <div class="col-sm-2">
							<?php echo isset($address_type[$addr['address_type']])?$address_type[$addr['address_type']]:'Address'; ?>
						</div>
                        <div class="col-sm-8">
                            <div class="">
                                <?php echo isset($addr['name'])?$addr['name'].'&nbsp;' :'';?>
                                <?php echo isset($addr['phone1'])?$addr['phone1']:'';?>
                                <?php echo isset($addr['address']) ? $addr['address'] : '';?>
                                <?php echo isset($addr['locality'])? ', '.$addr['address'] : '';?>
                                <?php echo isset($addr['city']) ? ', '.$addr['city'].', ' : '';?>
                                <?php echo isset($addr['state']) ? $addr['state'] : '';?>
                                <?php echo isset($addr['zip']) ? ' - <span class="text-bold">'.$addr['zip'].'</span>' : '';?>                                
                            </div>
                            <div>
                            </div>
                        </div>
                        <div class="col-sm-2">			
                            <a href="<?php echo site_url('admin/user/edit_address/'.$addr["id"]);?>" class="btn btn-sm btn-outline-info"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="<?php echo site_url('admin/user/delete_address/'.$addr["id"]);?>" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <!--/.row-->
                <?php
                }
            }?>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <h6>Other Info</h6><hr>        
    </div>
</div>