<?php
  include "inc/functions.php";
?>

<?php

  $alert_message;

  //  Show delete result
  if (isset($_GET['delete_status']))  {
    if ($_GET['delete_status'] == "success")  {
      $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Student\'s record deleted from database.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else if ($_GET['delete_status'] == "failed") {
      $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Could not delete student\'s record from database.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
  }

  //  Show delete result
  if (isset($_GET['blacklist_status']))  {
    if ($_GET['blacklist_status'] == "success")  {
      $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check-circle"></i></span> Operation success. The student has been blacklisted. This can be undone in PointsVault
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else if ($_GET['blacklist_status'] == "failed") {
      $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times-circle"></i></span> Operation failed. Could not blacklist student.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
  }

  //  Remove Blacklist

    //  Show delete result
  if (isset($_GET['remove_blacklist_status']))  {
    if ($_GET['remove_blacklist_status'] == "success")  {
      $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check-circle"></i></span> Operation success. The student has been removed from blacklist.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else if ($_GET['remove_blacklist_status'] == "failed") {
      $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times-circle"></i></span> Operation failed.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
  }

  //  Delete Student using id supplied in url

  if (isset($_POST['confirm_delete']))  {
    
    $id = $_POST['id'];
    $enc = $_POST['enc'];

    if ($enc == sha1(sha1($id)))  {
      
      $result = update("students_tb", array("student_id" => $id), array("deleted" => 1));

      if ($result)  {

        echo "students?delete_status=success";
        return;
      }
      else {
        echo "students?delete_status=failed";
        return;
      }
    }
  }

  //  Confirm Blacklist

  if (isset($_POST['confirm_blacklist']))  {
    
    $id = $_POST['id'];
    $enc = $_POST['enc'];

    if ($enc == sha1(sha1($id)))  {
      
      $result = update("students_tb", array("student_id" => $id), array("blacklist" => 1));

      if ($result)  {

        echo "students?blacklist_status=success";
        return;
      }
      else {
        echo "students?blacklist_status=failed";
        return;
      }
    }
  }

  //  Collect form details and edit student profile

  if (isset($_POST['edit_student']))  {
    
    $student_id = mysql_prep($_POST['student_id']);

    $school_id = mysql_prep($_POST['school_id']);
    $first_name = mysql_prep($_POST['first_name']);
    $last_name = mysql_prep($_POST['last_name']);
    $middle_name = mysql_prep($_POST['middle_name']);
    $house = mysql_prep($_POST['house']);
    $class = mysql_prep($_POST['class']);
    $role = mysql_prep($_POST['role']);
    $profile_pic = uploadFile($_FILES['profile_pic'], "img/profile_pics/", array("jpg", "jpeg", "png", "gif"));
    $date_modified = mysql_prep(strtotime(date("Y-m-d h:i:sa")));

    $data = array(
      "school_id" => $school_id,
      "first_name" => $first_name,
      "middle_name" => $middle_name,
      "last_name" => $last_name,
      "fullname" => $first_name . " " . $middle_name . " " . $last_name,
      "house_id" => $house,
      "class_id" => $class,
      "house_role" => $role,
      "profile_pic" => $profile_pic,
      "date_modified" => $date_modified

    );

    if (!$profile_pic) {

      $data = array(
        "first_name" => $first_name,
        "middle_name" => $middle_name,
        "last_name" => $last_name,
        "fullname" => $first_name . " " . $middle_name . " " . $last_name,
        "house_id" => $house,
        "class_id" => $class,
        "house_role" => $role,
        "date_modified" => $date_modified

      );

    }

    $result = update("students_tb", array("student_id" => $student_id) ,$data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Student\'s record was updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Student\'s  record was not updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

  }

  //  Remove blacklist

  if (isset($_GET['remove_blacklist']))  {
    $id = $_GET['remove_blacklist'];
    $enc = $_GET['enc'];

    if ($enc == sha1(sha1($id)))  {
      $result = update("students_tb", array("student_id" => $id), array("blacklist" => 0));

      if ($result)  {

        redirect_to("students?remove_blacklist_status=success");
      }
      else {
        redirect_to("students?remove_blacklist_status=success");
      }
    }
  }

  //  Collect form details and add Student

  if (isset($_POST['add_student']))  {

    $school_id = mysql_prep($_POST['school_id']);
    $first_name = mysql_prep($_POST['first_name']);
    $mid_name = mysql_prep($_POST['mid_name']);
    $last_name = mysql_prep($_POST['last_name']);
    $house = mysql_prep($_POST['house']);
    $class = mysql_prep($_POST['class']);
    $role = mysql_prep($_POST['role']);
    $profile_pic = ($_FILES['profile_pic']);

    $data = array(
        "school_id" => $school_id,
        "first_name" => $first_name,
        "middle_name" => $mid_name,
        "last_name" => $last_name,
      );

    //  Check if student exists

    $check_duplicate = read_single("students_tb", $data);

    if (!$check_duplicate) {

      $profile_pic_dest = uploadFile($profile_pic, "img/profile_pics/", array("jpg", "jpeg", "png", "gif"));

      $data = array(

        "first_name" => $first_name,
        "middle_name" => $mid_name,
        "last_name" => $last_name,
        "fullname" => $first_name . " " . $mid_name . " " . $last_name,
        "school_id" => $school_id,
        "points" => POINT_INIT,
        "house_id" => $house,
        "class_id" => $class,
        "date_created" => strtotime(date("Y-m-d h:i:sa")),
        "house_role" => $role,
        "profile_pic" => $profile_pic_dest
      );

      $result = create("students_tb", $data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Student\'s record added to database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }

    }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Found duplicate record. Student\'s  record exists already in database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }


  }
  
?>

<?php

  $page_title = "Students";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Students</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddStudent"><i class="fas fa-plus fa-sm"></i> Add Student</a>

<!-----Add Student Modal ---->

<div class="modal fade" id="modalAddStudent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
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
    $result = read_all("houses_tb", array("deleted" => 0));

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
    $result = read_all("classes", array("deleted" => 0));

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

          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-12 col-md-12 mb-4">

              <?php 
                if (isset($alert_message))  {
                  echo $alert_message;
                }

              ?>

              <!-- DataTables Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table small table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Photo</th>
                      <th>Name</th>
                      <td>Points</td>
                      <th>House</th>
                      <th>Class</th>
                      <th>Role</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tfoot>
                    
                  </tfoot>
                  <tbody>
                    <?php
                      $sql = 'SELECT * FROM students_tb WHERE deleted = 0 ORDER BY date_created DESC';
                      $result = mysqli_query($connection, $sql);

                      $count = 0;

                      if ($result)  {
                        while ($row = mysqli_fetch_array($result))  { ?>

                          <tr>
                            <td><?php echo ++$count; ?></td>
                            <td><img width = "60px" height="60px" class="img-profile rounded-circle" src="<?php echo $row['profile_pic']; ?>"></td>
                            <td><?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?> <?php if ($row['blacklist'] == 1) { echo '<span class = "badge badge-secondary" data-toggle = "tooltip" title = "Student has been banned from getting points.">Blacklisted</span>'; } ?></td>
                            <td><?php echo $row['points']; ?></td>
                            <td><?php echo getNameByID("houses_tb", array("house_id" => $row['house_id']), "house_name"); ?></td>
                            <td><?php echo getNameByID("classes", array("class_id" => $row['class_id']), "class_name"); ?></td>
                            <td><?php echo getNameByID("house_role_tb", array("house_role_id" => $row['house_role']), "house_role_value"); ?></td>
                            <td>
                              <div class="dropdown no-arrow">
                                <button class="btn btn-info btn-sm btn-circle dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class = "fas fa-angle-down"></i></button>

                                <div class="dropdown-menu">
                                  <a class="dropdown-item" href="javascript:void(0)"><img width = "60px" height="60px" class="img-profile rounded-circle" src="<?php echo $row['profile_pic']; ?>"></a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="student_profile?qid=<?php echo $row['student_id']; ?>&qenc=<?php echo sha1(sha1($row['student_id'])); ?>"><span class = "text-primary"><i class = "fas fa-eye mr-3"></i></span>View Details</a>
                                  <a data-toggle="modal" data-target="#modalEditStudent<?php echo $row['student_id']; ?>" class="dropdown-item edit_student" data_id = "<?php echo $row['student_id']; ?>" href="javascript:void(0)"><span class = "text-warning"><i class = "fas fa-edit mr-3"></i></span>Edit</a>
                                  <div class="dropdown-divider"></div>
                                  <a data-toggle="modal" data-target="#deleteStudentModal<?php echo $row['student_id']; ?>" class="dropdown-item delete" href="javascript:void(0)"><span class = "text-danger"><i class = "fas fa-trash-alt mr-3"></i></span>Delete</a>
                                  <?php
                                    if ($row['blacklist'] == 0) { ?>
                                      <a data-toggle="modal" data-target="#blacklistStudentModal<?php echo $row['student_id']; ?>" class="dropdown-item" href="javascript:void(0)"><span class = "text-secondary"><i class = "fas fa-user-times mr-3"></i></span>Blacklist</a>
                                   <?php }
                                    else { ?>

                                       <a class="dropdown-item" href="students?remove_blacklist=<?php echo $row['student_id']; ?>&enc=<?php echo sha1(sha1($row['student_id'])); ?>"><span class = "text-success"><i class = "fas fa-user-check mr-3"></i></span>Remove Blacklist</a>

                                   <?php }

                                  ?>
                                 
                                </div>

                              </div>

                                      <!-- Delete Confirmation Modal -->
                              <div class="modal fade" id="deleteStudentModal<?php echo $row['student_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <button type="button" class="btn btn-warning confirm_delete_student" data_enc = "<?php echo sha1(sha1($row['student_id'])); ?>" data_id = "<?php echo $row['student_id']; ?>">Delete</button>
                                  </div>
                                  </div>
                                  </div>
                                </div>

                                <!------Delete Confirmation Modal--->

                                <!-- Blacklist Confirmation Modal -->
                              <div class="modal fade" id="blacklistStudentModal<?php echo $row['student_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Blacklist confirmation</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                  <div class="modal-body">

                                    <div>Sure to blacklist "<strong><?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?></strong>"? <img width = "60px" height="60px" class="float-right img-profile rounded-circle" src="<?php echo $row['profile_pic']; ?>"><br></br><em>This can be undone in the PointsVault</em>.</div>
                                    
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn-sm btn btn-info" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-secondary text-white btn-sm confirm_blacklist_student" data_enc = "<?php echo sha1(sha1($row['student_id'])); ?>" data_id = "<?php echo $row['student_id']; ?>">Blacklist</button>
                                  </div>
                                  </div>
                                  </div>
                                </div>

                                <!------Blacklist Confirmation Modal--->


                            </td>
                                <!-----Edit Student Modal ---->

<div class="modal fade" id="modalEditStudent<?php echo $row['student_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Edit Profile</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="student_id" value = "<?php echo $row['student_id']; ?>">
      <div class="modal-body mx-3">
        
        <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Student ID</span>
  </div>
  <input value = "<?php echo $row['school_id']; ?>" type="text" aria-label="First name" class="form-control" name = "school_id" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">First Name</span>
  </div>
  <input value = "<?php echo $row['first_name']; ?>" type="text" aria-label="First name" class="form-control" name = "first_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Middle Name</span>
  </div>
  <input value = "<?php echo $row['middle_name']; ?>" type="text" aria-label="First name" class="form-control" name = "middle_name" required>
  
</div>
    </div>

    <!-- Middle name -->
    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Last Name</span>
  </div>
  <input value = "<?php echo $row['last_name']; ?>" type="text" aria-label="First name" class="form-control" name = "last_name" required>
  
</div>
    </div>

    <!--House select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">House</span>
  </div>
  <select class="custom-select" name = "house" required>

    <?php
      $getHouses = read_all("houses_tb");

      if ($getHouses) {
        while ($rowHouses = mysqli_fetch_array($getHouses)) { ?>
          <option value = "<?php echo $rowHouses['house_id']; ?>" <?php if ($row['house_id'] == $rowHouses['house_id'])  { echo "selected"; } ?>><?php echo $rowHouses['house_name']; ?></option>
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
    <?php
      $getClasses = read_all("classes");

      if ($getClasses) {
        while ($rowClasses = mysqli_fetch_array($getClasses)) { ?>
          <option value = "<?php echo $rowClasses['class_id']; ?>" <?php if ($row['class_id'] == $rowClasses['class_id'])  { echo "selected"; } ?>><?php echo $rowClasses['class_name']; ?></option>
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
      
      <?php
      $getRoles = read_all("house_role_tb");

      if ($getRoles) {
        while ($rowRoles = mysqli_fetch_array($getRoles)) { ?>
          <option value = "<?php echo $rowRoles['house_role_id']; ?>" <?php if ($row['house_role'] == $rowRoles['house_role_id'])  { echo "selected"; } ?>><?php echo $rowRoles['house_role_value']; ?></option>
        <?php }
      }
    ?>

        </select>
</div>
    </div>

<!--/Role select-->

<!--Profile Pic select-->
<div class="form-row mb-4 row">

  <div class="col-sm-2">
    <img width = "60px" height="60px" class="image_upload_preview rounded-circle" src="<?php echo $row['profile_pic']; ?>">
  </div>

  <div class = "col-sm-10">
    <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroupFileAddon01">Change Picture</span>
  </div>
  <div class="custom-file">
    <input type="file" value = "<?php echo $row['profile_pic']; ?>" class="custom-file-input profile_pic_input" name="profile_pic"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>
</div>
        
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "edit_student">Update Details</button>
      </div>

    </form>

    </div>
  </div>
</div>


<!-----Edit Student Modal----->


                          </tr>

                        <?php }
                      }

                    ?>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
              
            </div>

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