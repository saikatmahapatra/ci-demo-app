<?php
   $row = $row[0];
   //print_r($address);
   ?>
<div class="row">
   <div class="col-12">
      <h1>Profile</h1>
   </div>
</div>
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
   <div class="col-md-8">
      <div class="media">
         <a class="pull-left" href="#">
         <img class="align-self-start mr-3 dp" src="<?php echo site_url('assets/dist/img/avatar_2x.png'); ?>">
         </a>
         <div class="media-body">
            <h4 class="media-heading mt-0">
               <?php
                  echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
                  echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
                  echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
                  ?>
               <small> India</small>
            </h4>
            <h5><?php echo isset($row['role_name']) ? $row['role_name'] : ''; ?> at <a href="<?php echo site_url();?>">something.com</a></h5>
            <div class="user_intro">
               <?php echo (isset($row['user_intro']) && strlen($row['user_intro'])>0) ? $row['user_intro'] : '<span class="text-greyed-out">Describe who you are...</span>'; ?>
               <a href="<?php echo site_url('user/edit_profile');?>">Edit</a>
            </div>
            <hr style="m-8 auto">
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
      <div id="accordion">
         <div class="card">
            <div class="card-header" id="headingOne">
               <h5 class="mb-0">
                  <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Basic Info
                  </button>
               </h5>
            </div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
               <div class="card-body mb-10">
                  <div class="row">
                     <div class="col-md-4">Name</div>
                     <div class="col-md-8">
                        <?php
                           echo isset($row['user_firstname']) ? $row['user_firstname'] . '&nbsp;' : '';
                           echo isset($row['user_midname']) ? $row['user_midname'] . '&nbsp;' : '';
                           echo isset($row['user_lastname']) ? $row['user_lastname'] . '&nbsp;' : '';
                           ?>
                     </div>
                  </div>
                  <!--/.row-->
                  <div class="row">
                     <div class="col-md-4">Email</div>
                     <div class="col-md-8">
                        <a mailto="<?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?>"><?php echo isset($row['user_email']) ? $row['user_email'] : ''; ?></a>
                     </div>
                  </div>
                  <!--/.row-->
                  <div class="row">
                     <div class="col-md-4">Phone # 1</div>
                     <div class="col-md-8"><?php echo isset($row['user_mobile_phone1']) ? $row['user_mobile_phone1'] : ''; ?></div>
                  </div>
                  <!--/.row-->
                  <div class="row">
                     <div class="col-md-4">Phone # 2</div>
                     <div class="col-md-8"><?php echo isset($row['user_mobile_phone2']) ? $row['user_mobile_phone2'] : ''; ?></div>
                  </div>
                  <!--/.row-->
                  <div class="row">
                     <div class="col-md-4">Gender</div>
                     <div class="col-md-8"><?php echo isset($row['user_gender']) ? (($row['user_gender'] == 'M') ? 'Male' : 'Female') : ''; ?></div>
                  </div>
                  <!--/.row-->
                  <div class="row">
                     <div class="col-md-4">Date of Birth</div>
                     <div class="col-md-8"><?php echo isset($row['user_dob']) ? date('d-m-Y', strtotime($row['user_dob'])) : ''; ?></div>
                  </div>
                  <!--/.row-->
                  <div class="row">
                     <div class="col-md-4">Date of Registration</div>
                     <div class="col-md-8"><?php echo isset($row['user_registration_date']) ? date('d-m-Y h:i:s a', strtotime($row['user_registration_date'])) : ''; ?></div>
                  </div>
                  <!--/.row-->
                  <a class="btn btn-secondary" href="<?php echo site_url('user/edit_profile');?>">Edit</a>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-header" id="headingTwo">
               <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Address Details
                  </button>
               </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
               <div class="card-body mb-10">
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
                           <a href="<?php echo site_url('user/edit_address/'.$addr["id"]);?>" class="btn btn-secondary btn-sm">Edit</a>
                           <a href="<?php echo site_url('user/delete_address/'.$addr["id"]);?>" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                     </div>
                  </div>
                  <!--/.row-->
                  <?php
                     }
                     }?>
                  <a class="btn btn-secondary  pull-right" href="<?php echo site_url('user/add_address');?>">Add New</a>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-header" id="headingThree">
               <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Others
                  </button>
               </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
               <div class="card-body mb-10">
                  <a class="btn btn-secondary  pull-right" href="<?php echo site_url('user/edit_profile');?>">Edit</a>
                  <a class="btn btn-secondary  pull-right" href="<?php echo site_url('user/edit_profile');?>">Add New</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>