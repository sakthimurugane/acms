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

$_GLOBALS['FORMSTOVALIDATE']='#uploadDocForm';

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
        <li class="breadcrumb-item active">Upload Documents</li>
      </ol>
       <?php
       
          if( isset($_SESSION['ERRMSG_UPLOADKYC'])) {
              echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_UPLOADKYC'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['ERRMSG_UPLOADKYC']);
          }elseif(isset($_SESSION['SCSMSG_UPLOADKYC'])){
              echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_UPLOADKYC'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['SCSMSG_UPLOADKYC']);
          }
          
          if( isset($_SESSION['ERRMSG_DELKYC'])) {
              echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_DELKYC'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['ERRMSG_DELKYC']);
          }elseif(isset($_SESSION['SCSMSG_DELKYC'])){
              echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_DELKYC'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
              unset($_SESSION['SCSMSG_DELKYC']);
          }
          
          
          ?>
       <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Profile KYC documents &nbsp;
          <a data-toggle="modal" data-target="#addoption" class="btn btn-success btn-sm btn-default"><i class="fa fa-plus fa-1x"></i> Add Documents</a>
          </div>
          
          <!-- Modal to upload pofile image-->
                        <div class="modal fade" id="addoption" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" style="width:30%;height:30%;">
                                <div class="modal-content">
                                	<form id="uploadDocForm" enctype="multipart/form-data" name="uploadDocForm" method="post" action="../controllers/uploadkyccntrl.php">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-success" id="myModalLabel"><i class="fa fa-file"></i> <span class="text-right"> Upload Documents</span></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    
                                    </div>
                                    <div class="modal-body">
                                    <div>
                                    <select name="fileType" name="filetype" id="filetype" class="custom-select mb-3" data-validation="required">
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
                                    <input class="form-control" Placeholder="file descripton" type="text" name="fileDesc" id="fileDesc" data-validation="required">
                                    <br/>
                                    </div>	
                                    <div>
                                    <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" data-validation="required size" data-validation-max-size="3M" data-validation-allowing="jpg, png, gif, pdf, doc, docx, xls, xlsx, txt">
                                    </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" type="submit" name="submitdoc"><i class="fa fa-plus"></i> Upload</button>
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
          
          $uploadlistqry="SELECT * from profile_uploads where profile_id=".$profile_id." and is_deleted=0 order by created_on desc";
          $upresult=mysqli_query($link,$uploadlistqry);
          if($upresult) {
              if(mysqli_num_rows($upresult) > 0) { 
           ?>
           

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>File ID</th>
                  <th>File Name</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Option</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>File ID</th>
                  <th>File Name</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Option</th>
                </tr>
              </tfoot>
              <tbody>
                
                <?php 

                    while($row = $upresult->fetch_assoc()){
                ?>
                <tr>
                  <td><?php echo $row['profile_uploads_id'];?></td>
                  <td><?php echo $row['file_name'];?></td>
                  <td><?php echo $row['file_type'];?></td>
                  <td><?php echo $row['file_desc'];?></td>
                   <td class="text-center">
                   <div class="btn-group text-center">
                            <a data-toggle="modal" data-target="#deleteoption<?php echo $row['profile_id'];?>" class="btn btn-success btn-sm btn-default"><i class="fa fa-trash fa-1x"></i> Delete</a>
                            
                        </div>
                        <!-- Modal to upload pofile image-->
                        <div class="modal fade" id="deleteoption<?php echo $row['profile_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['profile_id'];?>" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" style="width:30%;height:30%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-success" id="myModalLabel<?php echo $row['profile_id'];?>"><i class="fa fa-trash"></i> <span class="text-right"> Delete Profile</span></h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    
                                    </div>
                                    <div class="modal-body">
                                    	Are you sure want to delete this Document ? <br/>
                                    	Document Type: <?php echo $row['file_type']; ?> &nbsp;<br/>
                                    	Document Name: <?php echo $row['file_name']; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="../controllers/deletekyccntrl.php?pfid=<?php echo $row['profile_id']; ?>&file_id=<?php echo $row['profile_uploads_id']; ?>" class="btn btn-success"><i class="fa fa-trash"></i> Delete</a>
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
                else{
                    ?>
                    
                         <div class="jumbotron">
                                  <h1>Oops!</h1> 
                                  <p>No Files found. Click on Add Documents button to upload a new file</p> 
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
                                  <p>Something went wrong. Click on Add Files button to upload a new file</p> 
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