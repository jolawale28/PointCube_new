<?php
  include "inc/functions.php";
?>

<?php

  $first_name;
  $middle_name;
  $last_name;
  $school_id;
  $house_name;
  $house_role;
  $points;
  $class;
  $profile_pic;

  if (isset($_GET['qenc'])) {

    $student_id = $_GET['qid'];
    $qenc = $_GET['qenc'];

    if (sha1(sha1($student_id)) == $qenc) {
      
      $result = read_single("students_tb", array("student_id" => $student_id));

      if ($result)  {

        $first_name = $result['first_name'];
        $middle_name = $result['middle_name'];
        $last_name = $result['last_name'];
        $school_id = $result['school_id'];
        $house_name = getNameByID("houses_tb", array("house_id" => $result['house_id']), "house_name" );
        $house_role = getNameByID("house_role_tb", array("house_role_id" => $result['house_role']), "house_role_value");
        $points = $result['points'];
        $class = getNameByID("classes", array("class_id" => $result['class_id']), "class_name" );
        $profile_pic = $result['profile_pic'];
        
      }
      else {
        header("Location: 404");
      }

    }
    else {
      header("Location: 404");
    }
  }
  
  
?>

<?php

$page_title = "Student Profile";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Profile</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm" data-toggle="modal" data-target="#modalAddStudent"><i class="fas fa-edit fa-sm mr-1"></i> Edit Profile</a>


          </div>

          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-3 col-md-4 col-sm-6 mb-1">

          <div class="card shadow mb-4">
            <div class="card-body">
              <img src="<?php echo $profile_pic; ?>" class = "img-fluid img-thumbnail">
              <h4 class = "my-3"><?php echo $first_name . " " . $last_name; ?></h4>
              <p class = "small my-3">ID: <em><?php echo $school_id; ?></em></p>
              <p class = "my-3 small"><span class = "mr-2 btn btn-warning btn-circle btn-sm"><i class = "fas fa-house-damage"></i></span><?php echo $house_name; ?> - <em><?php echo $house_role; ?></em></p>
              <p class = "my-3 small"><span class = "mr-2 btn btn-info btn-circle btn-sm"><i class = "fas fa-users"></i></span><?php echo $class; ?></p>
              <h4 class = "my-3 small"><span class = "mr-2 btn bg-red btn-circle btn-sm"><i class = "fas fa-parking"></i></span><?php if ($points > 0) {echo "+" . $points; } else { echo "+" . $points; } ?></h4><hr>
              <p class = "small">ACCOLADES</p>
              <div style = "text-align: center">
                <i class = "text-green mr-3 fas fa-trophy"></i>
                <i class = "mr-3 fas fa-medal"></i>
                <i class = "mr-3 fas fa-star"></i>
                <i class = "mr-3 fas fa-certificate"></i>
              </div>
            </div>
          </div>
              
            </div>

            <div class="col-xl-9 col-md-8 col-sm-6 mb-1">

              <div class="row">
            <div class="col-md-12">
    
                <!-- Tabs on Plain Card -->
                <div class="card card-nav-tabs card-plain">
                    <div class="card-header card-header-primary">
                        <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#about" data-toggle="tab">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#stats" data-toggle="tab">Statistics</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#history" data-toggle="tab">History</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#awards" data-toggle="tab">Awards</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div><div class="card-body ">
                        <div class="tab-content text-center">
                            <div class="tab-pane active" id="about">
                              <div class = "row">
                                <div class = "col-12">
                                  <h4 class="float-left"><i class="mr-3 fas fa-user"></i> Bio</h4>
                                </div>
                              </div><hr>
                              <div class = row>
                                <div class = "col">
                                  <table class="small table table-striped">
                                    <tbody>
                                      <tr align=left>
                                        <th scope="row"><b>First Name: </b></th>
                                        <td><?php echo $first_name; ?></td>
                                        <th scope="row"><b>Middle Name: </b></th>
                                        <td><?php echo $middle_name; ?></td>
                                        <th scope="row"><b>Last Name: </b></th>
                                        <td><?php echo $last_name; ?></td>
                                      </tr>
                                      <tr align=left>
                                        <th scope="row"><b>Username: </b></th>
                                        <td><?php echo "user"; ?></td>
                                        <th scope="row"><b>Email: </b></th>
                                        <td><?php echo "email"; ?></td>
                                        <th scope="row"><b>Date of Birth: </b></th>
                                        <td><?php echo "dob"; ?></td>
                                      </tr>
                                      <tr align=left>
                                        <th scope="row"><b>Last Logged in: </b></th>
                                        <td>19 May 2019</td>
                                        <th scope="row"><b>Status: </b></th>
                                        <td><em class = "badge badge-success">Online</em></td>
                                        <th scope="row"><b>Rank: </b></th>
                                        <td><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star-half text-warning"></i></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  
                                </div>
                                
                              </div>
                            </div>
                            <div class="tab-pane" id="stats">
                                <p> I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. </p>
                            </div>
                            <div class="tab-pane" id="history">
                                <p> I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at.</p>
                            </div>
                            <div class="tab-pane" id="awards">
                                <p> rtryrytI think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at.</p>
                            </div>
                        </div>
                    </div></div>
                <!-- End Tabs on plain Card -->
            </div>
          </div>
              
            </div>

          </div>

        </div>
        <!-- /.container-fluid -->


        <!----End of Main content---->
      </div>
      <!-- End of Main Content -->

 <?php

 include "inc/footer.php";

 ?>