<?php
  include "inc/functions.php";
?>

<?php

  $alert_message;

  //  Set Captain

  if (isset($_GET['message']))  {

    $message = $_GET['message'];

      if ($message == "success")  {

         $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Point has been awarded. This will still need to be vaildated by the Admin.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';

      }
      else {

         $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Could not award point.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
      }
    }

  //  Show delete result
  if (isset($_GET['delete_status']))  {
    if ($_GET['delete_status'] == "success")  {
      $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. House record deleted from database.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else if ($_GET['delete_status'] == "failed") {
      $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Could not delete house record from database.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
  }

  //  Collect form details and edit student profile

  if (isset($_POST['edit_house']))  {
    
    $house_id = mysql_prep($_POST['house_id']);

    $house_name = mysql_prep($_POST['house_name']);
    $house_color = mysql_prep($_POST['house_color']);
    $house_coord = mysql_prep($_POST['house_coord']);
    $profile_pic = uploadFile($_FILES['house_icon'], "img/profile_pics/", array("jpg", "jpeg", "png", "gif"));
    $date_modified = mysql_prep(strtotime(date("Y-m-d h:i:sa")));

    $data = array(
      "house_color" => $house_color,
      "house_name" => $house_name,
      "house_coord" => $house_coord,
      "house_icon" => $profile_pic,
      "date_modified" => $date_modified

    );

    if (!$profile_pic) {

      $data = array(
        "house_color" => $house_color,
        "house_name" => $house_name,
        "house_coord" => $house_coord,
        "date_modified" => $date_modified

      );

    }

    $result = update("houses_tb", array("house_id" => $house_id) ,$data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. House record was updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. House record was not updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

  }

  //  Collect form details and add Student

  if (isset($_POST['add_house']))  {

    $house_name = mysql_prep($_POST['house_name']);
    $house_color = mysql_prep($_POST['house_color']);
    $house_coord = mysql_prep($_POST['house_coord']);
    $profile_pic = $_FILES['house_pic'];

    $data = array(
        "house_name" => $house_name,
        "blacklist" => 0

      );

    //  Check if student exists

    $check_duplicate = read_single("houses_tb", $data);

    if (!$check_duplicate) {

      $profile_pic_dest = uploadFile($profile_pic, "img/house_pics/", array("jpg", "jpeg", "png", "gif"));

      $data = array(

        "house_name" => $house_name,
        "house_coord" => $house_coord,
        "house_color" => $house_color,
        "house_icon" => $profile_pic_dest,
        "date_created" => strtotime(date("Y-m-d h:i:sa")),
        "house_icon" => $profile_pic_dest
      );

      $result = create("houses_tb", $data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. ' . $house_name . ' House record added to database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }

    }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Found duplicate record. ' . $house_name . ' House record exists already in database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }


  }
  
?>

<?php

  $page_title = "Award Points";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Award Points</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddHouse"><i class="fas fa-plus fa-sm"></i> Add House</a> -->


          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-xl-12 col-md-12 mb-1">

              <?php 
                if (isset($alert_message))  {
                  echo $alert_message;
                }

              ?>
              
            </div>

            <div class="col-xl-12 col-md-12 col-sm-12 mb-4">

              <div class="card shadow mb-1">
                <div class="card-body">

                  <!-- Tabs on Plain Card -->
                <div class="card card-nav-tabs card-plain">
                    <div class="card-header card-header-primary">
                        <!-- colors: "header-primary", "header-info", "header-success", "header-warning", "header-danger" -->
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#about" data-toggle="tab"><i class = "text-blue fas fa-file-contract mr-2"></i>Single</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#stats" data-toggle="tab"><i class = "text-blue fas fa-copy mr-2"></i>Batch</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div><div class="card-body ">
                        <div class="tab-content text-center">
                            <div class="tab-pane active" id="about">
                              <div class = "row">
                                <div class = "col-12">
                                  <!-- <p class="float-left">Fill in card details to award points to a student.</p> -->
                                </div>
                              </div>
                              <div class = row>
                                <div class = "col">
                                  <div class="card col-sm-12 col-md-5 col-lg-5 p-0">
                                    <div class="p-4 house_bg" style = "color: white; background: rgb(200, 200, 200); height: 100px; border-radius: 5px 5px 0px 0px;">
                                      
                                      
                                    </div>
                                    <div style = "position: relative; margin-top: -60px" class = "text-center">
                                        <div class="dropdown no-arrow">
                                        <button style = "position: absolute; top: 10px" data-toggle="dropdown" class = "dropdown-toggle btn btn-circle bg-dark text-white btn-sm select_student"><i class="fas fa-pencil-alt"></i></button>

                                        <div class="dropdown-menu dropdown-primary p-2" id = "mydropdown">

                                          <div class="input-group mb-3">
                                            <input class = "form-control student_name" placeholder = "Type to search" type="text" list="students" name="" aria-label="Recipient's username" aria-describedby="button-addon2">
                                            
                                            <div class="input-group-append">
                                              <button class="btn btn-sm btn-primary m-0 waves-effect set_student" type="button" id="button-addon2" class = "small">Select
                                              </button>
                                            </div>
                                          </div>

                                        <datalist id = "students">
                                          <?php
                                            $result = read_all("students_tb", array("blacklist" => 0, "deleted" => 0));

                                            if ($result)  {
                                              while($rowStudent = mysqli_fetch_array($result))  { ?>

                                                <option value = "<?php echo $rowStudent['fullname']; ?>">

                                             <?php }
                                            }


                                          ?>

                                        </datalist>

                                        <!-- <div class="dropdown-divider"></div> -->
                                        <b>All Students</b> &bull; <em class=small>Fast pick</em>
                                        <div class="dropdown-divider"></div>

                                        <div class = "my-custom-scrollbar my-custom-scrollbar-primary" style = "height: 250px; overflow-y: scroll; overflow-x: hidden">
                                          <?php
                                            $result = read_all("students_tb", array("blacklist" => 0, "deleted" => 0));

                                            if ($result)  {
                                              while($rowStudent = mysqli_fetch_array($result))  { ?>

                                                <a id = "<?php echo $rowStudent['student_id']; ?>" house_color = "<?php echo getNameByID("houses_tb", array("house_id" => $rowStudent['house_id']), "house_color"); ?>" pic = "<?php echo $rowStudent['profile_pic']; ?>" stud_house = "<?php echo $rowStudent['house_id']; ?>" class="dropdown-item student_selected" href="javascript:void(0)"><img width = "40px" height="40px" class="mr-3 img-profile rounded-circle" src="<?php echo $rowStudent['profile_pic']; ?>"><?php echo $rowStudent['first_name'] . " " . $rowStudent['middle_name'] . " " . $rowStudent['last_name']; ?></a>

                                             <?php }
                                            }


                                          ?>
                                          
                                        </div>
                                        
                                      </div>

                                    </div>
                                      <img data-toggle="tooltip" title="Select a student" style = "border: 5px solid rgba(250, 250, 250, 0.3)" src="img/student_avatar.png" class = "rounded-circle student_pic" width=120px height=120px>

                                      <h5 class="mt-3 student_name">Student Name</h5>
                                    </div>
                                    <div class="card-body w-30">
                                      <center>
                                      <input class = "form-control w-25 form-control-sm text-center set_points" type="number" value = "0" name="points"></center>
                                    </div> 
                                    <div class="px-3">
                                      
                                      <p style = "font-size: 0.7rem" data-toggle="tooltip" title="Click to write comment" class = "set_comment mt-2 text-wrap">
                                        <i class = "fas fa-quote-left"></i>
                                        Comment.
                                        <i class="fas fa-quote-right"></i>
                                      </p>

                                      <textarea placeholder="Behaviour..." style = "font-size: 0.7rem; resize: none" rows = 4 class = "comment_input d-none form-control border-0"></textarea>

                                    </div><hr>
                                    <div class = "p-2 mb-2">
                                      <form action="inc/ajax_request" method="post">
                                        <input type="hidden" value = "" name="house_id" id = "house_id">
                                        <input type="hidden" value = "" name="student_id" id = "student_id">
                                        <input type="hidden" value = "" name="comment" id = "comment">
                                        <input type="hidden" value = "" name="points" id = "points">
                                        <button data-toggle="tooltip" title="Click button to send card. Button is initially disabled until all parameters are complete." name = "award_point" class = "btn btn-circle btn-primary p-4 send_point" disabled><i class="fas fa-paper-plane"></i></button>
                                      </form>
                                    </div>
                                  </div>
                                  
                                </div>
                                
                              </div>
                            </div>
                            <div class="tab-pane" id="stats">

 <div class="col-xl-6 col-lg-6 col-sm-12 col-md-12">
<div class="card mb-2">
                <!-- Card Header - Dropdown -->
                <div class="card-header">
                  <div class="input-group col-8 p-0">
                      <input data-toggle="tooltip" title="Type to select name from list" class = "resettable form-control group_student_name" placeholder = "Type to search" type="text" list="students" name="" aria-label="Recipient's username" aria-describedby="button-addon2">

                      <div class="input-group-append">
                        <button class="btn btn-sm btn-primary m-0 waves-effect group_add_student" type="button" id="button-addon2" class = "small">Select</button>
                      </div>
                  </div>

                  <input data-toggle="tooltip" title="Enter points" class = "resettable col-2 form-control group_student_point" placeholder = "0" type="number" name="" aria-label="Recipient's username" aria-describedby="button-addon2">

                  <!-- <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Actions:</div>
                      <a class="dropdown-item group_add_student" href="javascript:void(0)"><i class = "text-success fas fa-plus mr-3"></i>Add Student</a>
                      <a class="dropdown-item" href="#"><i class = "fas fa-paper-plane mr-3 text-primary"></i>Send</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#"><i class = "fas fa-recycle text-danger mr-3"></i>Reset</a>
                    </div>
                  </div> -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <ul style = "overflow-y: auto; height: 200px" class="name_list text-left list-group list-group-flush"></ul>

  <!-- <li class="border mb-1 list-group-item"><img src="img/profile_pics/student_avatar.jpg" class = "rounded-circle mr-3" width=30px height=30px>Cras justo odio<button class = "btn btn-danger float-right btn-sm remove_from_list"><i class="fas fa-trash-alt"></i></button></li> -->

  <div class="mt-3 pl-0">
                                      
    <p style = "font-size: 0.7rem" data-toggle="tooltip" title="Click to write comment" class = "set_comment mt-2 text-wrap">
      <i class = "fas fa-quote-left"></i>
      Comment.
      <i class="fas fa-quote-right"></i>
    </p>

    <textarea placeholder="Behaviour..." style = "font-size: 0.7rem; resize: none" rows = 4 class = "resettable comment_input d-none form-control border-0"></textarea>

  </div>
</div>
                <div class="card-footer text-left">
                  <a data-toggle="tooltip" title="Send to Vault" class="mr-3 send_group_point btn-sm btn btn-primary" href="javascript:void(0)"><i class = "fas fa-paper-plane mr-3"></i>Send</a>
                  <span style = "display: none" class = "text-danger notif_alert small">Incomplete details!</span>
                  <a data-toggle="tooltip" title="Reset values" class="reset_values float-right btn btn-warning btn-sm" href="javascript:void(0)"><i class = "fas fa-undo mr-3"></i>Reset</a>
                </div>
              </div>
            </div>
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

        <!----End of Main content---->
        </div>
      <!-- End of Row Content -->

      </div>
    <!-- /.container-fluid -->

      <script type="text/javascript">
          
          setTimeout(function() {
            $(".alert").fadeOut();
            $(".notif_alert").fadeOut();
          }, 5000);

      </script>

 <?php

 include "inc/footer.php";

 ?>