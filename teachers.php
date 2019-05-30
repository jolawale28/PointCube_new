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
      $alert_message = '<div class="alert-sm alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Class record deleted from database.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
    else if ($_GET['delete_status'] == "failed") {
      $alert_message = '<div class="alert-sm alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Could not delete class record from database.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>';
    }
  }

  //  Delete House using id supplied in url

  if (isset($_POST['confirm_delete']))  {
    
    $id = $_POST['id'];
    $enc = $_POST['enc'];

    if ($enc == sha1(sha1($id)))  {
      
      $result = update("teachers_tb", array("teacher_id" => $id), array("blacklist" => 1));

      if ($result)  {

        echo "teachers?delete_status=success";
        return;
      }
      else {
        echo "teachers?delete_status=failed";
        return;
      }
    }
  }

  //  Collect form details and edit student profile

  if (isset($_POST['edit_teacher']))  {
    
    $teacher_id = mysql_prep($_POST['teacher_id']);

    $first_name = mysql_prep($_POST['first_name']);
    $last_name = mysql_prep($_POST['last_name']);
    $middle_name = mysql_prep($_POST['middle_name']);
    $house_id = mysql_prep($_POST['house']);
    $profile_pic = uploadFile($_FILES['profile_pic'], "img/profile_pics/", array("jpg", "jpeg", "png", "gif"));
    $date_modified = mysql_prep(strtotime(date("Y-m-d h:i:sa")));

    $data = array(
      "first_name" => $first_name,
      "last_name" => $last_name,
      "middle_name" => $middle_name,
      "house_id" => $house_id,
      "profile_pic" => $profile_pic,
      "date_modified" => $date_modified

    );

    if (!$profile_pic) {

      $data = array(
        "first_name" => $first_name,
      "last_name" => $last_name,
      "middle_name" => $middle_name,
      "house_id" => $house_id,
      "date_modified" => $date_modified

      );

    }

    $result = update("teachers_tb", array("teacher_id" => $teacher_id) ,$data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. ' . $first_name . " " . $last_name .  ' record was updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Update record was not success.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

  }

  //  Collect form details and add Student

  if (isset($_POST['add_teacher']))  {

    $first_name = mysql_prep($_POST['first_name']);
    $middle_name = mysql_prep($_POST['middle_name']);
    $last_name = mysql_prep($_POST['last_name']);
    $reg_code = mysql_prep($_POST['reg_code']);
    $house_id = mysql_prep($_POST['house']);
    $email = mysql_prep($_POST['email']);
    $access_role = mysql_prep($_POST['access_role']);
    $profile_pic = $_FILES['profile_pic'];

    $data = array(
        "first_name" => $first_name,
        "middle_name" => $middle_name,
        "last_name" => $last_name,
        "email" => $email

      );

    //  Check if student exists

    $check_duplicate = read_single("teachers_tb", $data);

    if (!$check_duplicate) {

      $profile_pic = uploadFile($profile_pic, "img/profile_pics/", array("jpg", "jpeg", "png", "gif"));

      $data = array(

        "first_name" => $first_name,
        "middle_name" => $middle_name,
        "last_name" => $last_name,
        "reg_code" => $reg_code,
        "house_id" => $house_id,
        "email" => $email,
        "access_role" => $access_role,
        "date_created" => strtotime(date("Y-m-d h:i:sa")),
        "profile_pic" => $profile_pic,
        "password" => sha1(sha1("pointcube")),


      );

      if (!$profile_pic) {

        $data = array(
          "first_name" => $first_name,
          "middle_name" => $middle_name,
          "last_name" => $last_name,
          "reg_code" => $reg_code,
          "house_id" => $house_id,
          "email" => $email,
          "access_role" => $access_role,
          "date_created" => strtotime(date("Y-m-d h:i:sa")),
          "profile_pic" => "img/user.png",
          "password" => sha1(sha1("pointcube")),

        );

    }

      $result = create("teachers_tb", $data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. ' . $first_name . " " . $last_name . ' record added to database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }

    }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Found duplicate record. ' . $first_name . " " . $last_name . ' record exists already in database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }


  }
  
?>

<?php

  $page_title = "Teachers";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Teachers</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddClass"><i class="fas fa-plus fa-sm"></i> Add Teacher</a>

<!-----Add Class Modal ---->

<div class="modal fade" id="modalAddClass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Add Teacher</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body mx-3">

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">First Name</span>
  </div>
  <input type="text" aria-label="First name" placeholder = "Adeyemi" class="form-control" name = "first_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Middle Name</span>
  </div>
  <input type="text" aria-label="First name" placeholder = "Arinze" class="form-control" name = "middle_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Last Name</span>
  </div>
  <input type="text" aria-label="First name" placeholder = "Abubakar" class="form-control" name = "last_name" required>
  
</div>
    </div>

  <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Email</span>
  </div>
  <input type="email" aria-label="First name" placeholder = "john@doe.com" class="form-control" name = "email">
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Registration Code</span>
  </div>
  <input type="text" aria-label="First name" value = "<?php echo getReference(); ?>" class="form-control" name = "reg_code" readonly>
  
</div>
    </div>

    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Access Role</span>
  </div>
  <select class="custom-select" name = "access_role" required>
    <option value="">Select</option>
  <?php
    $result = read_all("access_roles_tb", array("can_reset" => 0));

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['access_role_id']; ?>"><?php echo $row['access_role_name']; ?></option>
      <?php }
    }

  ?>
   </select>
</div>
        
    </div>

    <!--Coordinator select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">House</span>
  </div>
  <select class="custom-select" name = "house" required>
    <option value="">Select</option>
  <?php
    $result = read_all("houses_tb");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['house_id']; ?>"><?php echo $row['house_name']; ?></option>
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
    <input type="file" class="custom-file-input" name="profile_pic"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "add_teacher">Submit Details</button>
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
                      $sql_teachers = 'SELECT * FROM teachers_tb WHERE deleted = 0 ORDER BY date_created DESC';
                      $result_teachers = mysqli_query($connection, $sql_teachers);

                      if ($result_teachers) {
                        while ($rowTeachers = mysqli_fetch_array($result_teachers)) { ?>

                          <div class="col-xl-3 col-md-4 col-sm-6 mb-3">

                             <!-- DataTables Example -->
          <div class="card shadow mb-1">
            <div class="card-body p-0">
              <div style = "border-radius: 5px 5px 0px 0px; height: 120px; background: url('<?php echo $rowTeachers['profile_pic']; ?>') no-repeat center; background-size: cover; margin: 0px 0px" class = "mb-2">
                
              </div>
              <img src="" class = "mb-4 img-fluid">

              <h6 class="text-center font-weight-bold mb-4"><?php echo $rowTeachers['first_name'] . " " . $rowTeachers['last_name']; ?></h6>
              
              
            </div>
            <div class="card-footer">
              <div class="text-center">
                <a href="" class = "text-white btn btn-circle mr-1 bg-primary btn-sm"><i class = "fas fa-info"></i></a>
                <a data-toggle="modal" data-target="#modalEditTeacher<?php echo $rowTeachers['teacher_id']; ?>" href="javascript:void(0)" class = "text-white btn btn-circle mr-1 bg-warning btn-sm"><i class = "fas fa-pencil-alt"></i></a>
                <a data-toggle="modal" data-target="#deleteTeacherModal<?php echo $rowTeachers['teacher_id']; ?>" href="javascript:void(0)" class = "text-white btn btn-circle bg-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
              </div>
            </div>

            <!----Delete Class Confirmation Modal ----->

            <div class="modal fade" id="deleteTeacherModal<?php echo $rowTeachers['teacher_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <button type="button" class="btn btn-warning confirm_delete_teacher" data_enc = "<?php echo sha1(sha1($rowTeachers['teacher_id'])); ?>" data_id = "<?php echo $rowTeachers['teacher_id']; ?>">Delete</button>
        </div>
      </div>
      </div>
    </div>

<!------Delete Class Confirmation Modal--->

            <!-----Edit Teacher Modal ---->

<div class="modal fade" id="modalEditTeacher<?php echo $rowTeachers['teacher_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
        <input type="hidden" name="teacher_id" value = "<?php echo $rowTeachers['teacher_id']; ?>">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Edit Class</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body mx-3">

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">First Name</span>
  </div>
  <input type="text" value = "<?php echo $rowTeachers['first_name']; ?>" aria-label="First name" class="form-control" name = "first_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Middle Name</span>
  </div>
  <input type="text" value = "<?php echo $rowTeachers['middle_name']; ?>" aria-label="First name" class="form-control" name = "middle_name" required>
  
</div>
    </div>

    <div class="form-row mb-4">
        <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Last Name</span>
  </div>
  <input type="text" value = "<?php echo $rowTeachers['last_name']; ?>" aria-label="First name" class="form-control" name = "last_name" required>
  
</div>
    </div>

    <!--Coordinator select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">House</span>
  </div>
  <select class="custom-select" name = "house" required>
    <option value="">Select</option>
  <?php
    $result = read_all("houses_tb");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['house_id']; ?>" <?php if($rowTeachers['house_id'] == $row['house_id']) { echo "selected"; } ?>><?php echo $row['house_name']; ?></option>
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
    <img width = "60px" height="60px" class="image_upload_preview rounded-circle" src="<?php echo $rowTeachers['profile_pic']; ?>">
  </div>

  <div class = "col-sm-10">
    <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroupFileAddon01">Change Picture</span>
  </div>
  <div class="custom-file">
    <input type="file" value = "<?php echo $rowHouse['house_icon']; ?>" class="custom-file-input profile_pic_input" name="profile_pic"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "edit_teacher">Submit Details</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-----Edit Class Modal---->

          </div>
        </div>

                       <?php }
                      }
                      else { ?>

                        <div class="container">
  <div class="jumbotron">
    <h4 class = "mb-4">Oops. Sorry we could not find any Teacher in the database!</h4> 
    <p><a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddClass"><i class="fas fa-plus fa-sm"></i> Add Teacher</a></p> 
  </div>
</div>

                     <?php }
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