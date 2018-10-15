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
        <li class="breadcrumb-item">
          <a href="index.php">Dashboard</a>
        </li>
         <li class="breadcrumb-item">
          <a href="#">Services</a>
        </li>
        <li class="breadcrumb-item active">Services</li>
      </ol>
      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Search Services
          <a href="addprofile.php?service_code=111" class=" text-center btn btn-success btn-sm btn-default">
           <i class="fa fa-plus"></i> Add Services
			</a>
          </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Service ID</th>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Description</th>
                  <th>Option</th>
                </tr>
              </thead>

              <tbody>
          <?php
            if($result) {
                if(mysqli_num_rows($result) > 0) { 
            while($row = $result->fetch_assoc()){
                
                
           ?>
                <tr>
                  <td><?php echo $row['SERVICE_ID'];?></td>
                  <td><?php echo $row['SERVICE_NAME'];?></td>
                  <td><?php echo $row['SERVICE_CODE'];?></td>
                  <td><?php echo $row['SERVICE_DESC'];?></td>
                   <td class="text-center">
                   <div class="btn-group text-center">
                            <a href="viewsetting.php?sid=1&amp;id=68" class="btn btn-success btn-sm btn-default"><i class="fa fa-eye fa-1x"></i> View Details</a>
                        </div>
                                    </td>
                </tr>
               <?php 
                
                }
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
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->



<?php 
include_once 'footer.php';

?>