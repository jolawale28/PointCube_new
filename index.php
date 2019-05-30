<?php
  include "inc/functions.php";

?>


<?php

  $page_title = "Home";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">House Divisions</div>
                      <div class="h4 mb-0 font-weight-bold text-gray-800">

                        <?php 
                          $result = read_all("houses_tb", array("blacklist" => 0));

                          if ($result) {
                            echo mysqli_num_rows($result);
                          }

                        ?>

                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-university fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Students</div>
                      <div class="h4 mb-0 font-weight-bold text-gray-800">
                        
                        <?php 
                          $result = read_all("students_tb", array("blacklist" => 0));

                          if ($result) {
                            echo mysqli_num_rows($result);
                          }

                        ?>

                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Teachers</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h4 mb-0 mr-3 font-weight-bold text-gray-800">
                            
                            <?php 
                              $result = read_all("teachers_tb", array("blacklist" => 0));

                              if ($result) {
                                echo mysqli_num_rows($result);
                              }

                            ?>

                          </div>
                        </div>
                        <div class="col">
                          <!-- <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div> -->
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Classes</div>
                      <div class="h4 mb-0 font-weight-bold text-gray-800">
                        
                        <?php 
                          $result = read_all("classes", array("blacklist" => 0));

                          if ($result) {
                            echo mysqli_num_rows($result);
                          }

                        ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->

          <div class="row">

            <!----Toppers ----->

            <div class="col-xl-3 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Toppers</h6>
                  <!-- <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div> -->
                </div>
                <!-- Card Body -->
                <div class="card-body">

                  <?php

                    $sql = 'SELECT * FROM students_tb ORDER BY points DESC LIMIT 4';

                    $result = mysqli_query($connection, $sql);

                    if ($result)  {
                      while($row = mysqli_fetch_array($result)) { ?>

                        <div class = "row p-2 mb-2 border rounded">
                          <div class = "col-4">
                            <img src="<?php echo $row['profile_pic']; ?>" class = "rounded-circle" width=50px height=50px>
                          </div>
                          <div class = col-8>
                            <p class = "mb-0 font-weight-bold small"><?php echo $row['first_name'] . "" . $row['last_name']; ?></p>
                            <p class = small><span><em><?php echo getNameByID("houses_tb", array("house_id" => $row['house_id']), "house_name"); ?></em></span> &bull; <span class="badge badge-primary">

                              <?php echo ($row['points'] > 0)? "+" . $row['points']: "-" . $row['points']; ?></span>

                              </p>

                          </div>
                    
                        </div>

                      <?php }
                    }
                  ?>
                  
                </div>
              </div>
            </div>

            <!-- Area Chart for House PErformance   -->
            <div class="col-xl-6 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">House Performance</h6>
                  <!-- <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div> -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="housePerformanceChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->

            <div class="col-xl-3 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Bottomers</h6>
                  <!-- <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div> -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <?php

                    $sql = 'SELECT * FROM students_tb ORDER BY points ASC LIMIT 4';

                    $result = mysqli_query($connection, $sql);

                    if ($result)  {
                      while($row = mysqli_fetch_array($result)) { ?>

                        <div class = "row p-2 mb-2 border rounded">
                          <div class = "col-4">
                            <img src="<?php echo $row['profile_pic']; ?>" class = "rounded-circle" width=50px height=50px>
                          </div>
                          <div class = col-8>
                            <p class = "mb-0 font-weight-bold small"><?php echo $row['first_name'] . "" . $row['last_name']; ?></p>
                            <p class = small><span><em><?php echo getNameByID("houses_tb", array("house_id" => $row['house_id']), "house_name"); ?></em></span> &bull; <span class="badge badge-primary">

                              <?php echo ($row['points'] > 0)? "+" . $row['points']: $row['points']; ?></span>

                              </p>

                          </div>
                    
                        </div>

                      <?php }
                    }
                  ?>
                </div>
              </div>
            </div>
            
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Cards Stats</h6>
                </div>
                <div class="card-body">

                  <?php

                    $positive;
                    $negative;

                    $sql_positive = 'SELECT * FROM cards_tb WHERE card_value > 0 AND validated=1';

                    $result_positive = mysqli_query($connection, $sql_positive);

                    if ($result_positive) {
                      $positive = mysqli_num_rows($result_positive);
                    }

                    $sql_negative = 'SELECT * FROM cards_tb WHERE card_value < 0 AND validated=1';

                    $result_negative = mysqli_query($connection, $sql_negative);

                    if ($result_negative)  {
                      $negative = mysqli_num_rows($result_negative);
                    }

                    $sum = $positive + $negative;

                    if ($sum != 0)  {
                      $positive_percent = ($positive/$sum)*100;
                      $negative_percent = ($negative/$sum)*100;
                    }
                    else {
                      $positive_percent = 0;
                      $negative_percent = 0;
                    }

                    
                  ?>
                  <h4 class="small font-weight-bold">Positive <em><?php echo " - " . $positive; ?></em>
                    <span class="float-right">

                    
                    <?php echo number_format($positive_percent) . "%"; ?>
                  </span>
                </h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo number_format($positive_percent) . "%"; ?>" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Negative <em><?php echo " - " . $negative; ?></em>
                    <span class="float-right">
                      
                      <?php echo number_format($negative_percent) . "%"; ?>
                        
                      </span>
                    </h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo number_format($negative_percent) . "%"; ?>" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>

                  <?php
                    $num;

                    $sql = 'SELECT * FROM cards_tb WHERE validated=0';

                    $result = mysqli_query($connection, $sql);

                    if ($result) {
                      $num = mysqli_num_rows($result);
                    }

                  ?>

                  <h4 class="small font-weight-bold">Unvalidated 
                    <span class="float-right">
                      
                      <?php echo ($num);
                      $total_validated;

                        $result = read_all("cards_tb");

                        $total_cards = mysqli_num_rows($result);

                        $result_valid = read_all("cards_tb", array("validated" => 0));

                        if ($result_valid)  {
                          $total_validated = mysqli_num_rows($result_valid);

                        }


                      ?>
                        
                      </span>
                    </h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo number_format($total_validated/$total_cards) . "%"; ?>" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  
                </div>
              </div>

            </div>

            <div class="col-lg-6 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">House Metrics</h6>
                </div>
                <div class="card-body">
                  
                  <canvas id="house_metric"></canvas>

                </div>
              </div>

            </div>
          </div>

          <div class = "row">
            <div class = "col-lg-12 mb-4">

              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">House Rankings</h6>
                </div>
                <div class="card-body">
                  
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php
                      $result = read_all("houses_tb", array("blacklist" => 0));

                      if ($result)  {
                        while ($row = mysqli_fetch_array($result))  { ?>

                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $row['house_name']; ?></a>
                          </li>

                       <?php }

                      }

                    ?>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">Raw denim you
    probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master
    cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro
    keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip
    placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi
    qui.</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Food truck fixie
    locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit,
    blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.
    Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum
    PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS
    salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit,
    sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester
    stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Etsy mixtape
    wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack
    lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard
    locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify
    squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie
    etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog
    stumptown. Pitchfork sustainable tofu synth chambray yr.</div>
</div>

                </div>
              </div>
              
            </div>
            
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

 <?php

 include "inc/footer.php";

 ?>