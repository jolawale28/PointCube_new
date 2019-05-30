<?php
  include "inc/functions.php";
?>

<?php

  $alert_message;

  //  Set Captain

  if (isset($_GET['id']) && isset($_GET['enc']))  {

    $student_id = $_GET['id'];
    $enc = $_GET['enc'];

    if ($enc == sha1(sha1($student_id)))  {

      $result = update("students_tb", array("student_id" => $student_id), array("house_role" => 1));

      if ($result)  {

         $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Captain selected for house.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';

      }
      else {

         $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Could not select Captain for house.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
      }
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

  //  House Blacklist Status

  if (isset($_GET['house_blacklist_status'])) {
    if ($_GET['house_blacklist_status'] == "success")  {
      $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. All students in this house have been blacklisted.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else if ($_GET['house_blacklist_status'] == "failed") {
      $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
  }

  //  Blacklist HOuse

  if (isset($_GET['blacklist_house']))  {
    $house_id = $_GET['blacklist_house'];
    $enc = $_GET['enc'];

    if ($enc == sha1(sha1($house_id)))  {
      $result = update("students_tb", array("house_id" => $house_id), array("blacklist" => 0));

      if ($result)  {
        redirect_to("houses?house_blacklist_status=success");
      }
      else {
        redirect_to("houses?house_blacklist_status=failed");
      }
    }
  }

  //  Delete House using id supplied in url

  if (isset($_POST['confirm_delete']))  {
    
    $id = $_POST['id'];
    $enc = $_POST['enc'];

    if ($enc == sha1(sha1($id)))  {
      
      $result = update("houses_tb", array("house_id" => $id), array("blacklist" => 1));

      if ($result)  {

        echo "houses?delete_status=success";
        return;
      }
      else {
        echo "houses?delete_status=failed";
        return;
      }
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

  $page_title = "Houses";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Houses</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddHouse"><i class="fas fa-plus fa-sm"></i> Add House</a>

<!-----Add House Modal ---->

<div class="modal fade" id="modalAddHouse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Add House</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body mx-3">

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Name</span>
  </div>
  <input type="text" aria-label="First name" class="form-control" name = "house_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Color</span>
  </div>
  <input type="color" value = "#AF34FF" aria-label="First name" class="form-control" name = "house_color" required>
  
</div>
    </div>

    <!--Coordinator select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Coordinator</span>
  </div>
  <select class="custom-select" name = "house_coord" required>
    <option value="">Select</option>
  <?php
    $result = read_all("teachers_tb");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['teacher_id']; ?>"><?php echo $row['first_name'] . " " . $row['last_name']; ?></option>
      <?php }
    }

  ?>
   </select>
</div>
        
    </div>

<!--/Coordinator select-->

<!--Profile Pic select-->
<div class="form-row mb-4">
    <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
  </div>
  <div class="custom-file">
    <input type="file" class="custom-file-input" name="house_pic"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "add_house">Submit Details</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-----Add House Modal---->
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

            <?php

              $resultHouse = read_all("houses_tb", array("blacklist" => 0, "deleted" => 0));

              if ($resultHouse)  {
                while ($rowHouse = mysqli_fetch_array($resultHouse))  { ?>

                  <div class="col-xl-3 col-md-3 col-sm-6 mb-4">

              <!-- DataTables Example -->
          <div class="card shadow mb-1">
            <div class="card-body">
              <div style = "border-radius: 5px 5px 0px 0px; height: 100px; background: <?php echo $rowHouse['house_color']; ?>; margin: -20px -20px" class = "">
                
              </div>
              <div style = "margin-top: -50px" class = "mb-2 text-center">
                <img style = "border: 5px solid rgba(250, 250, 250, 0.3)" src="<?php echo $rowHouse['house_icon']; ?>" class = "rounded-circle" width=100px height=100px>
              </div>
              <p class = "small mb-2"><button class = "btn btn-sm btn-primary mr-3 btn-circle"><i class = "fas fa-house-damage"></i></button><?php echo $rowHouse['house_name']; ?></p>
              <p class = "mb-2 small"><button class = "btn btn-sm btn-warning mr-3 btn-circle"><i class = "fas fa-user"></i></button><?php echo getNameByID("teachers_tb", array("teacher_id" => $rowHouse['house_coord']), "first_name") . " " . getNameByID("teachers_tb", array("teacher_id" => $rowHouse['house_coord']), "last_name"); ?></p>
              <div class = "mb-2 small"><button class = "btn btn-sm bg-purple mr-3 btn-circle"><i class = "fas fa-user-tag"></i></button>

            
                <?php
                  $student = read_single("students_tb", array("house_id" => $rowHouse['house_id'], "house_role" => 1, "blacklist" => 0));

                ?>

                <?php
                  if ($student) {
                    echo $student['first_name'] . " " . $student['last_name'];
                  }
                  else { ?>
                    <em>[<a href=""></a>No Captain for this house]</em>
                    <span class="small" type="button" id="dropdownMenuButton" data-toggle="modal" data-target="#exampleModalCenter<?php echo $rowHouse['house_id']; ?>">
                      Choose
                    </span>
                  <?php }

                ?>

                    <!-- Select Captain Modal -->
                    <div class="modal fade" id="exampleModalCenter<?php echo $rowHouse['house_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header" style = "background: <?php echo $rowHouse['house_color']; ?>">
                            <div>
                            <img style = "border: 5px solid rgba(250, 250, 250, 0.3)" src="<?php echo $rowHouse['house_icon']; ?>" class = "rounded-circle mr-3" width=70px height=70px>
                            <span class="text-white h4 modal-title" id="exampleModalCenterTitle"><?php echo $rowHouse['house_name']; ?></span>
                          </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">

                              <div class = "container-fluid datalist" style = "">

                                <?php

                                  $getCaptains = read_all("students_tb", array("house_id" => $rowHouse['house_id'], "blacklist" => 0));

                                  if ($getCaptains)  {
                                    while($rowGetCaptains = mysqli_fetch_array($getCaptains)) { ?>

                                      <div class = "row mb-2 border p-2 rounded clickable_row" data_enc = "<?php echo sha1(sha1($rowGetCaptains['student_id'])); ?>" data_id = "<?php echo $rowGetCaptains['student_id'] ?>">
                                        <div class = "col-sm-9">
                                          <img src="<?php echo $rowGetCaptains['profile_pic']; ?>" width = "40px" height="40px" class = "mr-3 rounded-circle img-profile">
                                          <span class="mr-2"><?php echo $rowGetCaptains['first_name'] . " " . $rowGetCaptains['last_name']; ?></span>
                                          <span class = "mr-2 badge badge-primary"><?php echo $rowGetCaptains['points']; ?></span>
                                          <span class = ""><em><?php echo getNameByID("house_role_tb", array("house_role_id" => $rowGetCaptains['house_role']), "house_role_value"); ?></em><span>
                                        </div>
                                      </div>

                                    <?php }
                                  }
                                  else { ?>

                                    <div class = "row">
                                      <div class = "col-sm-9">
                                        This house has no member yet.
                                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddStudent<?php echo $rowGetCaptains['student_id']; ?>"><i class="fas fa-plus fa-sm"></i> Add Student</a>

                                        <!-----Add Student to house Modal ---->

<div class="modal fade" id="modalAddStudent<?php echo $rowGetCaptains['student_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="students" method=post enctype="multipart/form-data">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Add Student</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body mx-3">

        <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Student ID</span>
  </div>
  <input type="text" aria-label="First name" class="form-control" name = "school_id" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">First Name</span>
  </div>
  <input type="text" aria-label="First name" class="form-control" name = "first_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Middle Name</span>
  </div>
  <input type="text" aria-label="First name" class="form-control" name = "mid_name" required>
  
</div>
    </div>

    <!-- Middle name -->
    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Last Name</span>
  </div>
  <input type="text" aria-label="First name" class="form-control" name = "last_name" required>
  
</div>
    </div>

    <!--House select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">House</span>
  </div>
  <select class="custom-select" name = "house" required>
    <option value="">Select</option>
  <?php
    $result = read_all("houses_tb", array("blacklist" => 0));

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['house_id']; ?>"><?php echo $row['house_name']; ?></option>
      <?php }
    }

  ?>
   </select>
</div>
        
    </div>

<!--/House select-->

<!--Class select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Class</span>
  </div>
  <select class="custom-select" name = "class" required>
    <option value="">Select</option>
          <?php
    $result = read_all("classes");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['class_id']; ?>"><?php echo $row['class_name']; ?></option>
      <?php }
    }

  ?>
        </select>
</div>
    </div>

<!--/Class select-->

<!--Role select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Role</span>
  </div>
  <select class="custom-select" name = "role" required>
    <option value="">Select</option>
          <?php
    $result = read_all("house_role_tb");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['house_role_id']; ?>"><?php echo $row['house_role_value']; ?></option>
      <?php }
    }

  ?>
        </select>
</div>
    </div>

<!--/Role select-->

<!--Profile Pic select-->
<div class="form-row mb-4">
    <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
  </div>
  <div class="custom-file">
    <input type="file" class="custom-file-input" name="profile_pic"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-lg btn-primary" name = "add_student">Submit Details</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-----Add student Modal---->
                                        
                                      </div>
                                      
                                    </div>

                                  <?php }

                                ?>

                            </div>
                              
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
                            <a href="javascript:void(0)" type="button" class="btn btn-sm btn-info" name = "set_captain">Set Captain</a>
                          </div>
                        </div>
                      </div>
                    </div>

              </div>
            </div>
            <div class="card-footer">
              <span class = "badge badge-primary">

              <?php
                $students_points = read_all("students_tb", array("house_id" => $rowHouse['house_id'], "blacklist" => 0));

                $points = 0;

                if ($students_points)  {
                  while ($rowPoints = mysqli_fetch_array($students_points)) {

                    $points += $rowPoints['points'];

                  }
                }

                echo $points;

              ?>

            </span>
              <div style = "display: inline-block; float:right" class="dropdown no-arrow">
                    <span class="btn-circle dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class = "fas fa-ellipsis-v"></i>
                    </span>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="student_profile?qid=&qenc="><span class = "text-primary"><i class = "fas fa-eye"></i></span>View Details</a>
                      <a data-toggle="modal" data-target="#modalEditHouse<?php echo $rowHouse['house_id']; ?>" class="dropdown-item edit_student" data_id = "" href="javascript:void(0)"><span class = "text-warning"><i class = "fas fa-edit"></i></span>Edit</a>
                      <div class="dropdown-divider"></div>
                      <a data-toggle="modal" data-target="#deleteHouseModal<?php echo $rowHouse['house_id']; ?>" class="dropdown-item delete" href="javascript:void(0)" ><span class = "text-danger"><i class = "fas fa-trash-alt"></i></span>Delete</a>
                      <a data-target="#deleteHouseModal<?php echo $rowHouse['house_id']; ?>" class="dropdown-item delete" href="houses?blacklist_house=<?php echo $rowHouse['house_id']; ?>&enc=<?php echo sha1(sha1($rowHouse['house_id'])); ?>" ><span class = "text-secondary"><i class = "fas fa-circle"></i></span>Blacklist</a>
                      <a data-target="#deleteHouseModal<?php echo $rowHouse['house_id']; ?>" class="dropdown-item delete" href="houses?blacklist_house=<?php echo $rowHouse['house_id']; ?>&enc=<?php echo sha1(sha1($rowHouse['house_id'])); ?>" ><span class = "text-success"><i class = "fas fa-circle-notch"></i></span>Remove Blacklist</a>
                    </div>
                  </div>
            </div>


            <!-----Edit House Modal ---->

<div class="modal fade" id="modalEditHouse<?php echo $rowHouse['house_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
        <input type="hidden" name="house_id" value = "<?php echo $rowHouse['house_id']; ?>">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Edit House</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body mx-3">

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Name</span>
  </div>
  <input value = "<?php echo $rowHouse['house_name']; ?>" type="text" aria-label="First name" class="form-control" name = "house_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Color</span>
  </div>
  <input type="color" value = "<?php echo $rowHouse['house_color']; ?>" aria-label="First name" class="form-control" name = "house_color" required>
  
</div>
    </div>

    <!--Coordinator select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Coordinator</span>
  </div>
  <select class="custom-select" name = "house_coord" required>
    <option value="">Select</option>
  <?php
    $result = read_all("teachers_tb");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['teacher_id']; ?>" <?php if ($rowHouse['house_coord'] == $row['teacher_id']) { echo "selected"; } ?>><?php echo $row['first_name'] . " " . $row['last_name']; ?></option>
      <?php }
    }

  ?>
   </select>
</div>
        
    </div>

<!--/Coordinator select-->

<!--Profile Pic select-->
<div class="form-row mb-4 row">

  <div class="col-sm-2">
    <img width = "60px" height="60px" class="image_upload_preview rounded-circle" src="<?php echo $rowHouse['house_icon']; ?>">
  </div>

  <div class = "col-sm-10">
    <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroupFileAddon01">Change Picture</span>
  </div>
  <div class="custom-file">
    <input type="file" value = "<?php echo $rowHouse['house_icon']; ?>" class="custom-file-input profile_pic_input" name="house_icon"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "edit_house">Submit Details</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-----Edit House Modal---->



            <!----Delete House Confirmation Modal ----->

            <div class="modal fade" id="deleteHouseModal<?php echo $rowHouse['house_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
              <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete confirmation</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
         <div class="modal-body">

          <div>Sure to delete?</div>
                                    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-warning confirm_delete_house" data_enc = "<?php echo sha1(sha1($rowHouse['house_id'])); ?>" data_id = "<?php echo $rowHouse['house_id']; ?>">Delete</button>
        </div>
      </div>
      </div>
    </div>

<!------Delete House Confirmation Modal--->
          </div>
              
            </div>

               <?php }
              }

            ?>

          </div>

        </div>
        <!-- /.container-fluid -->


        <!----End of Main content---->
      </div>
      <!-- End of Main Content -->

      <script type="text/javascript">
          
          setTimeout(function() {
            $(".alert").fadeOut();
          }, 5000);

      </script>

 <?php

 include "inc/footer.php";

 ?>