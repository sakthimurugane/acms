<?php
include_once 'header.php';
include_once 'navigator.php';
include_once '../app_config/mysqlcon.php';
$profileid='';
if(isset($_REQUEST['pfid'])){
    $profileid=$_REQUEST['pfid'];
    $servicecode=$_REQUEST['service_code'];
}else{
    if(isset($_SERVER['HTTP_REFERER'])){
        header("location:" . $_SERVER['HTTP_REFERER']);
    }else{
        header("location: ../dash/profile.php");
    }
    exit();
}
$_GLOBALS['FORMSTOVALIDATE'] = "#editProfileForm";
$profileqry="SELECT * FROM PROFILES where IS_DELETED=0 and profile_id=".$profileid;

?>

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item ">
        <a href="profile.php?service_code=<?php echo $servicecode;?>">Profiles</a>
        
        </li>
        <li class="breadcrumb-item active">Edit Profiles</li>
      </ol>
    
	<div class="row panel panel-success" style="margin-top:2%;margin:10px;padding:20px;">
           <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-16 col-md-16"><i class="fa fa-edit"></i> Edit Profiles (Profile id - <?php echo $profileid; ?>)</div>

                    </div>
                </div>
            </div>
            
             <?php
                    if( isset($_SESSION['ERRMSG_EDITPROFILE'])) {
                        echo '<div class="alert alert-danger alert-dismissible">',$_SESSION['ERRMSG_EDITPROFILE'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
                        unset($_SESSION['ERRMSG_EDITPROFILE']);
                    }elseif(isset($_SESSION['SCSMSG_EDITPROFILE'])){
                        echo '<div class="alert alert-success alert-dismissible">',$_SESSION['SCSMSG_EDITPROFILE'],'<a href="#" aria-label="close" class="close" data-dismiss="alert">&times;</a></div><br>';
                        unset($_SESSION['SCSMSG_EDITPROFILE']);
                    }
               ?>

  	
  	
            <form action="../controllers/editprofilecontroller.php" name="editProfileForm" id="editProfileForm" method="post">
               
             <div class="row">
                        <div class="col-lg-12 col-md-12">

                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <center>
                                        <span class="text-left">
                                        <img src="../images/avatar_2x.png" class="img-responsive img-thumbnail">


                                            <!-- Modal to upload pofile image-->
                                            <div class="modal fade" id="PhotoOption" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog" style="width:30%;height:30%;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title text-success" id="myModalLabel"><i class="fa fa-gear"></i> <span class="text-right"> Upload Profile pic</span></h4>
                                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        
                                                        </div>
                                                        <div class="modal-body">
    														<center><img src="../images/avatar_2x.png" class="img-responsive img-thumbnail"></center>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="" class="btn btn-success"><i class="fa fa-photo"></i> Upload</a>
                                                            <a href="" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->                    
                                    </span></center>

                                    <div class="table-responsive panel">
                                    
                                        <table class="table">
                                            <tbody>
                                                        <tr>
                                                        <td class="text-center">
                                                            <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#PhotoOption"><i class="fa fa-photo"></i> Change</a>
                                                            
                                                        </td>
                                                    </tr>
                                                      <tr>
                                                        <td class="text-center">
                                                                           
                                    </td>
                                                    </tr>
                                                            							
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                </div>
                                <div class="col-lg-9 col-md-9">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a data-toggle="tab" href="#Summary" class="nav-link text-success active"><i class="fa fa-indent"></i> Summary</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="Summary" class="tab-pane container active">

                                            <div class="table-responsive panel">
                                                <table class="table">
                                                    <tbody>
    
                                                            <?php 
                                                            $profileresult = mysqli_query($link,$profileqry);
                                                            if($profileresult) {
                                                                if(mysqli_num_rows($profileresult) == 1) {
                                                                    while($profilerow = $profileresult->fetch_assoc()){
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-user"></i> Profile Name</td>
                                                                <td><input type="text" class="form-control" name="pfname" id="pfname"size="20"  Placeholder="Fisrtname Lastname" data-validation="required alphanumeric" data-validation-allowing=".-_"  value="<?php echo $profilerow['profile_name'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-user-o"></i> Owner Name</td>
                                                                <td><input type="text" class="form-control" name="owname" id="owname" size="20" Placeholder="Fisrtname Lastname" data-validation="required alphanumeric" data-validation-allowing=".-_" value="<?php echo $profilerow['owner_name'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-male"></i> Father Name</td>
                                                                <td><input type="text" class="form-control" name="fathname" id="fathname" size="20" Placeholder="Fisrtname Lastname" data-validation="required alphanumeric" data-validation-allowing=".-_" value="<?php echo $profilerow['father_name'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-group"></i> Referred by</td>
                                                                <td>
                                                                  <select name="selectreferer" name="selectreferer" id="selectreferer" class="custom-select mb-3" data-validation="required">
                                                                        
                                              			<?php 
                                                                $refserquery="SELECT REFERRER_ID, REFERRER_NAME from REFERRER where is_deleted=0";
                                                                $refresult = mysqli_query($link,$refserquery);
                                                                
                                                                ?>
                                                                        <option selected value="">--None--</option>
                                                            <?php 
                                                                if($refresult) {
                                                                    if(mysqli_num_rows($refresult) > 0) {
                                                                        while($refrow = $refresult->fetch_assoc()){
                                                                ?>
                                                                    <option value="<?php echo $refrow['REFERRER_ID'];?>"  <?php if($refrow['REFERRER_ID']==$profilerow['referrer_id']){echo "selected";}?> ><?php echo $refrow['REFERRER_NAME'];?></option>
                                                                    
                                                                    <?php 
                                                                        }}}
                                                                    ?>
                                                                      </select>
                                                                
                                                                </td>
                                                            </tr>
                                                           <?php 
                                                           $profilecntqry="select * from profile_contact where is_deleted=0 and profile_id=".$profileid;
                                                           $prfcntresult = mysqli_query($link,$profilecntqry);
                                                            if($prfcntresult) {
                                                                if(mysqli_num_rows($prfcntresult) > 0) {
                                                                    while($prfcntrow = $prfcntresult->fetch_assoc()){
                                                                        if($prfcntrow['contact_type']=='HOME'){
                                                            ?>
                                                            
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-home"></i>  Home Address</td>
                                                                    <td><input type="text" class="form-control" name="homeaddr" id="homeaddr" size="20" Placeholder="No, Street, Locality" value="<?php echo $prfcntrow['contact_details'];?>" /></td>
    
                                                            </tr>
                                                            <?php 
                                                                        }
                                                                        else if($prfcntrow['contact_type']=='OFFICE'){
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-building"></i> Office Address</td>
                                                                    <td><input type="text" class="form-control" name="officeaddr" id="officeaddr" size="20" Placeholder="No, Street, Locality" value="<?php echo $prfcntrow['contact_details'];?>" /></td>
    
                                                            </tr>
                                                            <?php 
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-envelope-o"></i> Email ID</td>
                                                                <td><input type="text" class="form-control" name="pfemail" id="pfemail" size="20" Placeholder="example@exmaple.com" data-validation="required email" value="<?php echo $profilerow['email'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-mobile"></i> Mobile Number</td>
                                                                <td><input type="text" class="form-control" name="pfmobile" id="pfmobile" size="20" Placeholder="9876543210" data-validation="required number length" data-validation-length="8-13" value="<?php echo $profilerow['mobile'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-flag"></i> Nationality</td>
                                                                <td><input type="text" class="form-control" name="pfnationality" id="pfnationality" size="20" Placeholder="India, France, etc..," data-validation="required alphanumeric" data-validation-allowing=".-_" value="<?php echo $profilerow['nation'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-user"></i> Cotanct Person Name</td>
                                                                <td><input type="text" class="form-control" name="pfcname" id="pfcname" size="20" Placeholder="Firstname Lastname" data-validation="required alphanumeric" data-validation-allowing=".-_" value="<?php echo $profilerow['contact_person'];?>" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-mobile"></i> Contact Person Mobile</td>
                                                                <td><input type="text" class="form-control" name="pfcmobile" id="pfcmobile" size="20" Placeholder="9876543210" data-validation="required number length" data-validation-length="8-13" value="<?php echo $profilerow['contact_person_mobile'];?>"/></td>
                                                            </tr>
                                                            <?php 
                                                                    }
                                                                }
                                                            }
                                                            
                                                            //$kycqry="select * from profile_kyc where is_deleted=0 and profile_id=".$profileid;
                                                            $kycqry="select k.kyc_code, pk.kyc_number from kyc k, profile_kyc pk where k.is_deleted=0 and  pk.is_deleted=0 and k.kyc_id=pk.kyc_id and pk.profile_id=".$profileid;
                                                            $kycresult = mysqli_query($link,$kycqry);
                                                            if($kycresult) {
                                                                if(mysqli_num_rows($kycresult) > 0) {
                                                                    while($kycrow = $kycresult->fetch_assoc()){
                                                                        if($kycrow['kyc_code']=='ADH'){
                                                            ?>
                                                            
                                                            
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-file-text"></i> Aadhar Number</td>
                                                                <td><input type="text" class="form-control" name="pfaadhar" id="pfaadhar" size="20" maxlength="12" Placeholder="xxxx-xxxx-xxxx" value="<?php echo $kycrow['kyc_number'];?>"/></td>
                                                            </tr>  
                                                            <?php 
                                                                        }
                                                                        else if($kycrow['kyc_code']=='PAN'){
                                                                            
                                                            ?>                                      
                                                                <tr>
                                                                <td class="text-success"><i class="fa fa-file-text"></i> PAN Number</td>
                                                                <td><input type="text" class="form-control" name="pfpan" id="pfpan" size="20" maxlength="10" Placeholder="AABBC0123B" value="<?php echo $kycrow['kyc_number'];?>" /></td>
                                                            </tr>  
                                                            <?php 
                                                                        }
                                                                        else if($kycrow['kyc_code']=='GST'){
                                                            ?> 
                                                           <tr>
                                                                <td class="text-success"><i class="fa fa-file-text"></i> GSTIN Number</td>
                                                                <td><input type="text" class="form-control" name="pfgstin" id="pfgstin" size="20" maxlength="20" Placeholder="xxxx-xxxx-xxxx" value="<?php echo $kycrow['kyc_number'];?>" /></td>
                                                            </tr>
                                                            <?php 
                                                                        }else if($kycrow['kyc_code']=='PST'){
                                                            ?>   
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-file-text"></i> Passport No</td>
                                                                <td><input type="text" class="form-control" name="pfpassprt" id="pfpassprt" size="20" maxlength="12" Placeholder="xxxx-xxxx-xxxx" value="<?php echo $kycrow['kyc_number'];?>" /></td>
                                                            </tr>   
                                                            
                                                             <?php 
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $profilesubservice=array();
                                                            $prfsubserqry="select sub_service_id from profile_services where profile_id=".$profileid;
                                                            $prfsubrs=mysqli_query($link,$prfsubserqry);
                                                            if($prfsubrs){
                                                                if(mysqli_num_rows($prfsubrs) > 0) {
                                                                    while($row = $prfsubrs->fetch_assoc()){
                                                                        array_push($profilesubservice,$row['sub_service_id']);
                                                                    }
                                                                  }
                                                            }

                                                            $servicelistqry="SELECT SERVICE_ID, SERVICE_CODE, SERVICE_NAME FROM SERVICES where IS_DELETED=0 order by created_on desc";
                                         
                                                             $result=mysqli_query($link,$servicelistqry);
                                                                    if($result) {
                                                                        $servicecount=0;
                                                                        if(mysqli_num_rows($result) > 0) {
                                                                            while($row = $result->fetch_assoc()){
                                                                                $servicecount++;
                                                                                
                                                            ?>
                                                                <tr>
                                                                <td class="text-success"><i class="fa fa-cog"></i> <?php echo $row['SERVICE_NAME'];?></td>
                                                                <td>
                                                                
                                                                <select name="service<?php echo $servicecount;?>[]" class="custom-select" multiple>
                                                                <?php 
                                                                $subserquery="SELECT SUB_SERVICE_ID, SUB_SERVICE_NAME, SUB_SERVICE_CODE from SUB_SERVICES where is_deleted=0 and service_id=". $row['SERVICE_ID'];
                                                                $subresult = mysqli_query($link,$subserquery);
                                                                
                                                                ?>
                                                                <option>None</option>
                                                                <?php 
                                                                if($subresult) {
                                                                    if(mysqli_num_rows($subresult) > 0) {
                                                                        while($subrow = $subresult->fetch_assoc()){
                                                                ?>
                                                                    <option value="<?php echo $subrow['SUB_SERVICE_ID'];?>"   <?php if(in_array($subrow['SUB_SERVICE_ID'],$profilesubservice)){echo "selected";}?> ><?php echo $subrow['SUB_SERVICE_NAME'];?></option>
                                                                    
                                                                    <?php 
                                                                        }}}
                                                                    ?>
                                                                  </select>
                                                                
                                                                </td>
                                                            </tr> 
                                              <?php 
                                                    }
                                                    
                                                }
                                                
                                            }
                                            ?>     
                                                     
                                                     <tr>
                                                     <td>
                                                     </td>
                                                     <td>
                                                      <div class="text-center">
                                                      <input type="hidden" name="profileid" id="profileid" value="<?php echo $profileid; ?>"/>
                                                      <input type="hidden" name="srvcount" id="srvcount" value="<?php echo $servicecount; ?>"/>
                            <button type="submit" name="submitaddprofile" class="btn btn-success btn-sm btn-default"><i class="fa fa-floppy-o fa-1x"></i> Save</button>
                            <button type="reset" class="btn btn-danger btn-sm btn-default"><i class="fa fa-window-close-o	 fa-1x"></i> Cancel</button>
                            
                        </div>
                                                     </td>
                                                     </tr>
                                                          
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                <!-- /.table-responsive -->
            </form>
        </div>
              </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->



<?php 
include_once 'footer.php';

?>