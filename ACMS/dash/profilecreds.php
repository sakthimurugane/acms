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

$profile_id='';
if(isset($_REQUEST['pfid'])){
    $profile_id=clean($_REQUEST['pfid']);
}else{
    if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
    }else{
        header("location: ../dash/profile.php");
    }
}

$_GLOBALS['FORMSTOVALIDATE']='#addCredForm';

?>

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Dashboard</a>
        </li>
         <li class="breadcrumb-item">
          <a href="#">Profiles</a>
        </li>
        <li class="breadcrumb-item">
          <a href="#">GST Profiles</a>
        </li>
        <li class="breadcrumb-item active">Credential Manager</li>
      </ol>
       <?php
       
          if( isset($_SESSION['ERRMSG_ADDCRED'])) {
              echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_ADDCRED'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['ERRMSG_ADDCRED']);
          }elseif(isset($_SESSION['SCSMSG_ADDCRED'])){
              echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_ADDCRED'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['SCSMSG_ADDCRED']);
          }
          
          if( isset($_SESSION['ERRMSG_EDITCRED'])) {
              echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_EDITCRED'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['ERRMSG_EDITCRED']);
          }elseif(isset($_SESSION['SCSMSG_EDITCRED'])){
              echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_EDITCRED'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['SCSMSG_EDITCRED']);
          }
          
          if( isset($_SESSION['ERRMSG_DELCRED'])) {
              echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_DELCRED'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['ERRMSG_DELCRED']);
          }elseif(isset($_SESSION['SCSMSG_DELCRED'])){
              echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_DELCRED'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['SCSMSG_DELCRED']);
          }
          
          
          ?>
       <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Profile Credential Manager &nbsp;
          <a data-toggle="modal" data-target="#addoption" class="btn btn-success btn-sm btn-default"><i class="fa fa-plus fa-1x"></i> Add new credential</a>
          </div>
          
         			 <!-- Modal to upload pofile image-->
                        <div class="modal fade" id="addoption" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" style="width:30%;height:30%;">
                                <div class="modal-content">
                                	<form id="addCredForm" name="addCredForm" method="post" action="../controllers/addcredcntrl.php">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-success" id="myModalLabel"><i class="fa fa-lock"></i> <span class="text-right"> New Credentials</span></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    
                                    </div>
                                    <div class="modal-body">
                                    <div>
                                    
                                    <select name="credType" id="credType" class="custom-select mb-3" data-validation="required">
                                    <option value="PAN">PAN Card</option>
                                    <option value="Aadhar">Aadhar Card</option>
                                    <option value="GSTIN">GSTIN</option>
                                    <option value="Passport">Passport</option>
                                    <option value="Voter id">Voter ID</option>
                                    <option value="Ration card">PDES Card</option>
                                    <option value="Other">Other</option>

                                    </select>
                                    </div>
                                   <div>
                                    <input class="form-control" Placeholder="Description" type="text" name="credDesc" id="credDesc" data-validation="required"/ >
                                    </div>	
                                    <div>
                                    <input class="form-control" Placeholder="Username" type="text" name="credLogin" id="credLogin" data-validation="required" />
                                    </div>
                                    <div>
                                    <input class="form-control" Placeholder="Password" type="text" name="credPass" id="credPass" data-validation="required">
                                    </div>
                                    <div>
                                    <input class="form-control" Placeholder="MFA Details" type="text" name="credMFA" id="credMFA" data-validation="required">
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" type="submit" name="submitcred"><i class="fa fa-plus"></i> Add</button>
                                        <a class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-close"></i> Cancel</a>
                                    </div>
                                    <input type="hidden" name="profileid" value="<?php echo $profile_id; ?>"/>
                                 </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        
                        
          <?php    
          
          $credlistqry="SELECT * from profile_credentials where profile_id=".$profile_id." and is_deleted=0 order by created_on desc";
          $credresult=mysqli_query($link,$credlistqry);
          if($credresult) {
              if(mysqli_num_rows($credresult) > 0) { 
           ?>
           

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Desc</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>MFA</th>
                  <th>Options</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Type</th>
                  <th>Desc</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>MFA</th>
                  <th>Options</th>
                </tr>
              </tfoot>
              <tbody>
                
                <?php 

                while($row = $credresult->fetch_assoc()){
                ?>
                <tr>
                  <td><?php echo $row['cred_type'];?></td>
                  <td><?php echo $row['cred_desc'];?></td>
                  <td><?php echo $row['cred_login'];?></td>
                  <td><?php echo $row['cred_password'];?></td>
                  <td><?php echo $row['cred_mfa_details'];?></td>
                  
                   <td class="text-center">
                   <div class="btn-group text-center">
                                               <a data-toggle="modal" data-target="#editoption<?php echo $row['profile_id'];?>" class="btn btn-success btn-sm btn-default"><i class="fa fa-edit fa-1x"></i> Edit</a>
                   
                            <a data-toggle="modal" data-target="#deleteoption<?php echo $row['profile_id'];?>" class="btn btn-success btn-sm btn-default"><i class="fa fa-trash fa-1x"></i> Delete</a>
                            
                        </div>
                        <!-- Modal to upload pofile image-->
                        <div class="modal fade" id="deleteoption<?php echo $row['profile_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['profile_id'];?>" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" style="width:30%;height:30%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-success" id="myModalLabel<?php echo $row['profile_id'];?>"><i class="fa fa-trash"></i> <span class="text-right"> Delete Credentials</span></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    
                                    </div>
                                    <div class="modal-body">
                                    	Are you sure want to delete this Credentials ? <br/>
                                    	Type: <?php echo $row['cred_type']; ?> &nbsp;<br/>
                                    	Desc: <?php echo $row['cred_desc']; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="../controllers/deletecredcntrl.php?pfid=<?php echo $row['profile_id']; ?>&credid=<?php echo $row['profile_cred_id']; ?>" class="btn btn-success"><i class="fa fa-trash"></i> Delete</a>
                                        <a class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-close"></i> Cancel</a>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal --> 
                        
                        <!-- Modal to Edit cred details-->
                        <div class="modal fade" id="editoption<?php echo $row['profile_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['profile_id'];?>" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" style="width:30%;height:30%;">
                                <div class="modal-content">
                                 <form id="editCredForm" name="editCredForm" method="post" action="../controllers/editcredcntrl.php">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-success" id="myModalLabel<?php echo $row['profile_id'];?>"><i class="fa fa-key"></i> <span class="text-right"> Edit Credential</span></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    
                                    </div>
                                    <div class="modal-body">
                                    	
                                    	<div class="table-responsive panel">
                                                <table class="table">
                                                    <tbody>
    
                                                            <tr>
                                                                <td class="text-success">Type</td>
                                                                <td><?php echo $row['cred_type'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success">Desc</td>
                                                                <td><input type="text" name="ecredDesc" id="ecredDesc" class="form-control" value="<?php echo $row['cred_desc'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success">Login</td>
                                                                <td><input type="text" name="ecredLogin" id="ecredLogin" class="form-control" value="<?php echo $row['cred_login'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success">Password</td>
                                                                <td><input type="text" name="ecredPass" id="ecredPass" class="form-control" value="<?php echo $row['cred_password'];?>" /></td>
                                                            </tr>

                                                             <tr>
                                                                <td class="text-success">MFA</td>
                                                                <td>
																<input type="text" name="ecredMFA" id="ecredMFA" class="form-control" value="<?php echo $row['cred_mfa_details'];?>" />                                                                </td>
                                                            </tr>
                                                    </tbody>
                                                </table>	
                                            </div>
                                            
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" type="submit" name="updatecred" id="updatecred"><i class="fa fa-save"></i> Update</button>
                                        <a class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-close"></i> Cancel</a>
                                    </div>
                                    <input type="hidden" name="credid" value="<?php echo $row['profile_cred_id']; ?>"/>
                                    
                                    </form>
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
                else{
                    ?>
                    
                         <div class="jumbotron">
                                  <h1>Oops!</h1> 
                                  <p>No Files found. Click on Add Credentials button to upload a new credential</p> 
                          </div>
                    <?php 
                    
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
                                  <p>Something went wrong. Click on Add Credentials button to add a new credential</p> 
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