<?php

include_once 'header.php';
include_once 'navigator.php';
include_once '../app_config/mysqlcon.php';

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

$scodebreadcumb='';
$selectedservice = clean($_GET['service_code']);
if($selectedservice=='GST'){
    $scodebreadcumb='GST Profiles';
}
elseif ($selectedservice=='ITR'){
    $scodebreadcumb='IT Return Profiles';
}
elseif($selectedservice=='OTH'){
    $scodebreadcumb='Common Profiles' ;
}
else{
    header("location: index.php");
}
$servicelistqry="SELECT PROFILE_ID,PROFILE_NAME,MOBILE,EMAIL,CONTACT_PERSON FROM PROFILES where profile_id in ".
                "(select distinct profile_id from profile_services where is_deleted=0 and ".
                "sub_service_id in (select sub_service_id from sub_services where service_id in (select service_id from services where service_code='$selectedservice' and is_deleted=0) and is_deleted=0)) and is_deleted=0 order by created_on desc";

$result=mysqli_query($link,$servicelistqry);

?>

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Dashboard</a>
        </li>
         <li class="breadcrumb-item">
          <a href="#">Services</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $scodebreadcumb; ?></li>
      </ol>
       <?php
                 if( isset($_SESSION['ERRMSG_DELPROFILE'])) {
              echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_DELPROFILE'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['ERRMSG_DELPROFILE']);
          }elseif(isset($_SESSION['SCSMSG_DELPROFILE'])){
              echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_DELPROFILE'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['SCSMSG_DELPROFILE']);
          }
          ?>
       <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Search <?php echo $scodebreadcumb; ?>
          <a href="addprofile.php?service_code=<?php echo $selectedservice; ?>" class=" text-center btn btn-success btn-sm btn-default">
           <i class="fa fa-plus"></i> Add <?php echo $scodebreadcumb; ?>
			</a>
          </div>
          <?php    
          if($result) {
           ?>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Dealer ID</th>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Contact Person</th>
                  <th>Option</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Dealer ID</th>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Contact Person</th>
                  <th>Option</th>
                </tr>
              </tfoot>
              <tbody>
                
                <?php 
                if(mysqli_num_rows($result) > 0) { 
            while($row = $result->fetch_assoc()){
                
                
           ?>
                <tr>
                  <td><?php echo $row['PROFILE_ID'];?></td>
                  <td><?php echo $row['PROFILE_NAME'];?></td>
                  <td><?php echo $row['MOBILE'];?></td>
                  <td><?php echo $row['EMAIL'];?></td>
                  <td><?php echo $row['CONTACT_PERSON'];?></td>
                   <td class="text-center">
                   <div class="btn-group text-center">
                            <a href="viewprofile.php?pfid=<?php echo $row['PROFILE_ID'];?>&service_code=<?php echo $selectedservice;?>" class="btn btn-success btn-sm btn-default" title="View" ><i class="fa fa-eye fa-1x"></i></a>
                            <a href="editprofile.php?pfid=<?php echo $row['PROFILE_ID'];?>&service_code=<?php echo $selectedservice;?>" class="btn btn-success btn-sm btn-default" title="Edit" ><i class="fa fa-edit fa-1x"></i></a>
                            <a href="uploadkyc.php?pfid=<?php echo $row['PROFILE_ID'];?>&service_code=<?php echo $selectedservice;?>" class="btn btn-success btn-sm btn-default" title="KYC"><i class="fa fa-file fa-1x"></i></a>
                            <a href="profilecreds.php?pfid=<?php echo $row['PROFILE_ID'];?>&service_code=<?php echo $selectedservice;?>" class="btn btn-success btn-sm btn-default" title="Credentials"><i class="fa fa-key fa-1x"></i></a>
                           
                            <a data-toggle="modal" data-target="#deleteoption<?php echo $row['PROFILE_ID'];?>" class="btn btn-success btn-sm btn-default" title="Delete"><i class="fa fa-trash fa-1x"></i></a>
                            
                        </div>
                        <!-- Modal to upload pofile image-->
                        <div class="modal fade" id="deleteoption<?php echo $row['PROFILE_ID'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['PROFILE_ID'];?>" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" style="width:30%;height:30%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-success" id="myModalLabel<?php echo $row['PROFILE_ID'];?>"><i class="fa fa-trash"></i> <span class="text-right"> Delete Profile</span></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    
                                    </div>
                                    <div class="modal-body">
                                    	Are you sure want to delete this profile ? <br/>
                                    	Profile ID: <?php echo $row['PROFILE_ID']; ?> &nbsp;
                                    	Profile Name: <?php echo $row['PROFILE_NAME']; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="../controllers/deleteprofile.php?pfid=<?php echo $row['PROFILE_ID']; ?>" class="btn btn-success"><i class="fa fa-trash"></i> Delete</a>
                                        <a class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-close"></i> Cancel</a>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal --> 
                  </td>
                </tr>
               <?php 
                
                }
                }
                ?>
                
                </tbody>
                </table>
                </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                
                <?php 
            }
            else{
                
                ?>
                              <div class="jumbotron">
                                  <h1>Oops!</h1> 
                                  <p>No Profiles found. Click on Add <?php echo $scodebreadcumb; ?> button to create add new profile</p> 
                                </div>
                              <?php
                }
                ?>	

      </div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->


<?php 
include_once 'footer.php';

?>