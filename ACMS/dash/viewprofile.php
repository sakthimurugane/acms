<?php

include_once 'header.php';
include_once 'navigator.php';
require_once '../app_config/mysqlcon.php';

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}
if(isset($_REQUEST['pfid'])){
$profileid=clean($_REQUEST['pfid']);
}
else{
    header("location: ".$_REQUEST['HTTP_REFERER']);
}

?>

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">GST Profiles</li>
      </ol>
    
	<div class="row panel panel-success" style="margin-top:2%;margin:10px;padding:20px;">
           <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-16 col-md-16"><i class="fa fa-users"></i> View Profile Details</div>

                    </div>
                </div>
            </div>
            <div class="panel-body">
                						 
                       <?php 
                       $profiledetailsqry="select * from profiles where profile_id=".$profileid;
                       $prfresult=mysqli_query($link,$profiledetailsqry);
                       if($prfresult) {
                           if(mysqli_num_rows($prfresult) == 1) {
                               $prfrow = $prfresult->fetch_assoc();                                   
                                   
                       ?>                                   
                    <div class="row">	
                        <div class="col-lg-12 col-md-12">

                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <center>
                                        <span class="text-left">
                                        <img src="../images/avatar_2x.png" class="img-responsive img-thumbnail">
                                    </span></center>

                                    <div class="table-responsive panel">
                                        <table class="table">
                                            <tbody>
                                                      <tr>
                                                        <td class="text-center small text-muted">
        													Profile id: <?php echo $prfrow['profile_id'];?>
                                    					</td>
                                    					</tr>
                                    					<tr>
                                    					<td class="text-center	small text-muted">
        													Created by: 
        													
        													<?php 
                                                                $personqry="SELECT * from person where is_deleted=0 and person_id=".$prfrow['created_by'];
                                                                $prsnresult=mysqli_query($link,$personqry);
                                                                if($prsnresult) {
                                                                    if(mysqli_num_rows($prsnresult) == 1) {
                                                                        $personrow=$prsnresult->fetch_assoc();
                                                                        if(trim($personrow['short_name'])!=''){
                                                                            echo $personrow['short_name'];
                                                                        }
                                                                        else{
                                                                            echo $personrow['person_name'];
                                                                        }
                                                                    }
                                                                }else{
                                                                    echo $prfrow['created_by'];
                                                                }
                                                                
                                                                ?> 
                                                                
                                                                
                                    					</td>
                                    					</tr>
                                    					<tr>
                                    					<td class="text-center small text-muted">
        													Created on: <?php echo $prfrow['created_on'];?>
                                    					</td>
                                                    </tr>
                                                            							
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                </div>
                                <div class="col-lg-9 col-md-9">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a data-toggle="tab" href="#Summary" class="nav-link text-success active"><i class="fa fa-indent"></i> Summary</a></li>
                                        <li class="nav-item"><a data-toggle="tab" href="#Address" class=" nav-link text-success"><i class="fa fa-home"></i> Address</a></li>
                                        <li class="nav-item"><a data-toggle="tab" href="#kyc" class=" nav-link text-success"><i class="fa fa-info"></i> KYC</a></li>	
                                        <li class="nav-item"><a data-toggle="tab" href="#services" class=" nav-link text-success"><i class="fa fa-cogs"></i> Services</a></li>	
                                    
                                    </ul>

                                    <div class="tab-content">
                                        <div id="Summary" class="tab-pane container active">

                                            <div class="table-responsive panel">
                                                <table class="table">
                                                    <tbody>
    
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-user"></i> Profile Name</td>
                                                                <td><?php echo $prfrow['profile_name'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-user"></i> Owner Name</td>
                                                                <td><?php echo $prfrow['owner_name'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-male"></i> Father Name</td>
                                                                <td><?php echo $prfrow['father_name'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-at"></i> Email</td>
                                                                <td><?php echo $prfrow['email'];?></td>
                                                            </tr>

                                                             <tr>
                                                                <td class="text-success"><i class="fa fa-phone"></i> Mobile</td>
                                                                <td>
                                                                <?php echo $prfrow['mobile'];?>                                                                 </td>
                                                            </tr>
                                                             <tr>
                                                                <td class="text-success"><i class="fa fa-user"></i> Contact Person</td>
                                                                <td>
                                                                <?php echo $prfrow['contact_person'];?>                                                                 </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-phone"></i> Contact Person Mobile</td>
                                                                <td>
                                                                <?php echo $prfrow['contact_person_mobile'];?>                                                                 </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-chain"></i> Referrer</td>
                                                                <td>
                                                                <?php 
                                                                $referrerqry="SELECT REFERRER_NAME from REFERRER where REFERRER_ID=".$prfrow['referrer_id'];
                                                                $rfrresult=mysqli_query($link,$referrerqry);
                                                                if($rfrresult) {
                                                                    if(mysqli_num_rows($rfrresult) == 1) {
                                                                        $rfrrow=$rfrresult->fetch_assoc();
                                                                        echo $rfrrow['REFERRER_NAME'];
                                                                    }
                                                                }else{
                                                                    echo $prfrow['referrer_id'];
                                                                }
                                                                
                                                                ?>                                                                
                                                                 </td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div id="Address" class="tab-pane container">
                                            <div class="table-responsive panel">
                                                	<?php 
    														$addrqry="select * from profile_contact where profile_id=".$prfrow['profile_id']."  and is_deleted=0 order by contact_type";
    														$addrresult=mysqli_query($link,$addrqry);  
    														if($addrresult){
    														    if(mysqli_num_rows($rfrresult) > 0) {
     
    														?>
                                                <table class="table">
                                                    <tbody>
    									
                                                            <?php 
                                                            
                                                            while($addrrow = $addrresult->fetch_assoc()){
                                                                $iconname='';
                                                                if($addrrow['contact_type']=='HOME'){
                                                                    $iconname='home';
                                                                }else if($addrrow['contact_type']=='OFFICE'){
                                                                    $iconname='building';
                                                                }else{
                                                                    $iconname='home';
                                                                }
                                                                
                                                                
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-<?php echo $iconname; ?>"></i> Address</td>
                                                                <td>
                                                                    <address>
                                                                        <?php echo $addrrow['contact_details']; ?>
                                                                    </address>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                            }
                                                            ?>
                                                            </tbody>
                                                </table>
                                                
                                                <?php 
    							
    													
    														    }
    														    
    														    
    														}else{
    														    echo $link->error;
    														    ?>
                                            							<div class="jumbotron">
                                                                          <h1>Oops!</h1> 
                                                                          <p>No Address found</p> 
                                                                        </div>
                                                              <?php
    														}
    														
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <div id="kyc" class="tab-pane container">
                                            <div class="table-responsive panel">
                                                	<?php 
    														$kycqry="select pk.kyc_name,pr.kyc_number from profile_kyc pr, kyc pk where pk.kyc_id=pr.kyc_id and pr.profile_id=".$prfrow['profile_id']."  and pk.is_deleted=0 and pr.is_deleted=0 order by pr.created_on desc";
    														$kycresult=mysqli_query($link,$kycqry);  
    														if($kycresult){
    														    if(mysqli_num_rows($kycresult) > 0) {
     
    														?>
                                                <table class="table">
                                                    <tbody>
    									
                                                            <?php 
                                                            
                                                            while($kycrow = $kycresult->fetch_assoc()){
                     
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-file"></i> <?php echo $kycrow['kyc_name'];?></td>
                                                                <td>
                                                                    <address>
                                                                        <?php echo $kycrow['kyc_number'];?>
                                                                    </address>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                            }
                                                            ?>
                                                            </tbody>
                                                </table>
                                                
                                                <?php 
    							
    													
    														    }
    														    
    														    
    														}else{
    														    echo $link->error;
    														    ?>
                                            							<div class="jumbotron">
                                                                          <h1>Oops!</h1> 
                                                                          <p>No Address found</p> 
                                                                        </div>
                                                              <?php
    														}
    														
                                                ?>
                                            </div>
                                        </div>
                                        
											<div id="services" class="tab-pane container">
                                            <div class="table-responsive panel">
                                                	<?php 
    														$srvqry="select * from services where is_deleted=0 order by service_code desc";
    														$srvresult=mysqli_query($link,$srvqry);  
    														if($srvresult){
    														    if(mysqli_num_rows($srvresult) > 0) {
     
    														?>
                                                <table class="table">
                                                    <tbody>
    									
                                                            <?php 
                                                            
                                                            while($srvrow = $srvresult->fetch_assoc()){
                     
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><i class="fa fa-cog"></i> <?php echo $srvrow['service_name'];?></td>
                                                                <td>
                        											<?php 
                        											
                        											$psrvqry="select sb.sub_service_name from sub_services sb, profile_services ps where sb.sub_service_id=ps.sub_service_id and ps.profile_id=".$prfrow['profile_id']." and ps.sub_service_id=sb.sub_service_id and sb.service_id=".$srvrow['service_id']." order by ps.created_on desc";
                        											$psrvresult=mysqli_query($link,$psrvqry);
                        											if($psrvresult){
                        											    if(mysqli_num_rows($psrvresult) > 0) {
                        											        while($psrvrow = $psrvresult->fetch_assoc()){
                        											            echo $psrvrow['sub_service_name']."<br/>";
                            											    }
                        											    }else{
                            											echo 'None';
                        											}
                        											}
                        											?>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                            }
                                                            ?>
                                                            </tbody>
                                                </table>
                                                
                                                <?php 
    							
    													
    														    }
    														    
    														    
    														}else{
    														    echo $link->error;
    														    ?>
                                            							<div class="jumbotron">
                                                                          <h1>Oops!</h1> 
                                                                          <p>No Address found</p> 
                                                                        </div>
                                                              <?php
    														}
    														
                                                ?>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                <!-- /.table-responsive -->
                <?php 
  
                           }
                           else{
                           ?>
                                <div class="jumbotron">
                                  <h1>Oops!</h1> 
                                  <p>No Profiles found</p> 
                                </div>
                           
                           <?php
                           }
                       }
                           
                       else{
                           
                           ?>
                                <div class="jumbotron">
                                  <h1>Oops!</h1> 
                                  <p>Internal server error. contact system Administrator.</p> 
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