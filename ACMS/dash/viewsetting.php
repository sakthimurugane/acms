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

$servicelistqry="SELECT SERVICE_ID,SERVICE_CODE,SERVICE_NAME,SERVICE_DESC FROM SERVICES";
$result=mysqli_query($link,$servicelistqry);

?>

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">
          <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Setings</li>
        <li class="breadcrumb-item active">Services</li>
        
      </ol>
    
	<div class="row panel panel-success" style="margin-top:2%;margin:10px;padding:20px;">
           <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-16 col-md-16"><i class="fa fa-users"></i> Services Settings</div>
                    </div>
            </div>
            <div class="panel-body">                               
                    <div class="row">
                        <div class="col-lg-12 col-md-12">

                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                     <div class="table-responsive panel">
                                        <table class="table">
                                            <tbody>
                                                        <tr>
                                                        <td class="text-center">
                                                            <span class="btn btn-danger text-success btn-block"><i class="fa fa-rupee"></i> Unpaid</span>
                                                            <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#PhotoOption"><i class="fa fa-photo"></i> Change</a>
                                                        </td>
                                                    </tr>
                                                      <tr>
                                                        <td class="text-center">
                                                                            <div class="btn-group text-center">
                            <a href="student-view.php?sid=1&amp;id=68" class="btn btn-success btn-sm btn-default"><i class="fa fa-eye fa-1x"></i></a>
                            <a href="student-modify.php?sid=1&amp;id=68" class="btn btn-success btn-sm btn-default"><i class="fa fa-edit fa-1x"></i></a>
                            <a href="student-print.php?sid=1&amp;id=68" class="btn btn-success btn-sm btn-default"><i class="fa fa-print fa-1x"></i></a>
                            <a href="student-delete.php?sid=1&amp;id=68" class="btn btn-success btn-sm btn-default"><i class="fa fa-trash-o fa-1x"></i></a>
                        </div>
                                    </td>
                                                    </tr>
                                                            							
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9">
                                    <ul class="nav nav-tabs">
                                    <?php
                                    if($result) {
                                        if(mysqli_num_rows($result) > 0) { 
                                    $i=1;
                                    while($row = $result->fetch_assoc()){
                                        
                                       ?>
                                 <li class="nav-item"><a data-toggle="tab" href="#<?php echo $row['SERVICE_CODE']?>" class="nav-link text-success <?php if($i==1)echo 'active';?>"> <?php echo $row['SERVICE_CODE'];?></a></li>
                                    <?php 
                                    
                                    $i++;
                                    }
                                    
                                    ?>
                                    </ul>
									

									
                                    <div class="tab-content">
                                    <?php 
                                    $result2=mysqli_query($link,$servicelistqry);
                                    if($result2) {
                                        if(mysqli_num_rows($result2) > 0) { 
									$i=1;
									while($row = $result2->fetch_assoc()){
									    
									?>
                                        <div id="<?php echo $row['SERVICE_CODE']?>" class="tab-pane container <?php if($i==1)echo 'active';?>">

                                            <div class="table-responsive panel">
                                                <table class="table">
                                                    <tbody>
    
                                                            <tr>
                                                                <td class="text-success"> Service Name</td>
                                                                <td><?php echo $row['SERVICE_NAME']?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-success"> Service Code</td>
                                                                <td><?php echo $row['SERVICE_CODE']?></td>
                                                            </tr>
                                                           <tr>
                                                                <td class="text-success "> Description</td>
                                                                <td class="text-justify"><?php echo $row['SERVICE_DESC']?></td>
                                                            </tr>
                                                            <tr>
                                                            <td class="text-success "> Options
                                                            </td>
                                                            <td>
                                                            <div class="btn-group text-center">
                													<button type="button" data-toggle="modal" data-target="#delete<?php echo $row['SERVICE_CODE']?>" class="btn btn-danger btn-sm btn-default">
                													
                													<i class="fa fa-trash fa-1x"></i> Delete</button>
            												</div>
            												

                                                            </td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        

            												                                            <!-- Delete Modal-->
                                        <div class="modal" id="delete<?php echo $row['SERVICE_CODE']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['SERVICE_CODE']?>" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel<?php echo $row['SERVICE_CODE']?>">Delete Services</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">×</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">Are you sure want to delete <?php echo $row['SERVICE_NAME']?> ?</div>
                                              <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <a class="btn btn-primary" href="deleteservices.php?service_code=<?php echo $row['SERVICE_ID']?>">Delete</a>
                                              </div>
                                            </div>
                                          </div>
                                        </div> 
									<?php 
									
									$i++;
									}
                                        }
                                    }
									?>
									
                                    </div>
                         <!-- /.tab-content -->
                                    

                                </div>

                            </div>
                        </div>
                    </div>
                <!-- /.table-responsive -->
                

                
            </div>
            
            <?php 
                          }
                          else{
                              ?>
                              <div class="jumbotron">
                                  <h1>Oops!</h1> 
                                  <p>No Services found. Click on Add Services button to create a new one</p> 
                                </div>
                              <?php
                          }
                      }
            ?>
        </div>
        
              </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->



<?php 
include_once 'footer.php';

?>