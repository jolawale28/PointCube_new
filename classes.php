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
      
      $result = update("classes", array("class_id" => $id), array("blacklist" => 1));

      if ($result)  {

        echo "classes?delete_status=success";
        return;
      }
      else {
        echo "classes?delete_status=failed";
        return;
      }
    }
  }

  //  Collect form details and edit student profile

  if (isset($_POST['edit_class']))  {
    
    $class_id = mysql_prep($_POST['class_id']);

    $class_name = mysql_prep($_POST['class_name']);
    $class_coord = mysql_prep($_POST['class_coordinator']);
    $class_bg = uploadFile($_FILES['class_bg'], "img/profile_pics/", array("jpg", "jpeg", "png", "gif"));
    $date_modified = mysql_prep(strtotime(date("Y-m-d h:i:sa")));

    $data = array(
      "class_name" => $class_name,
      "class_coordinator" => $class_coord,
      "class_bg" => $class_bg,
      "date_modified" => $date_modified

    );

    if (!$class_bg) {

      $data = array(
        "class_name" => $class_name,
        "class_coordinator" => $class_coord,
        "date_modified" => $date_modified

      );

    }

    $result = update("classes", array("class_id" => $class_id) ,$data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Class record was updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Operation failed. Class record was not updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }

  }

  //  Collect form details and add Student

  if (isset($_POST['add_class']))  {

    $class_name = mysql_prep($_POST['class_name']);
    $class_coord = mysql_prep($_POST['class_coord']);
    $class_bg = $_FILES['class_bg'];

    $data = array(
        "class_name" => $class_name,
        "blacklist" => 0

      );

    //  Check if student exists

    $check_duplicate = read_single("classes", $data);

    if (!$check_duplicate) {

      $class_bg = uploadFile($class_bg, "img/classes_pics/", array("jpg", "jpeg", "png", "gif"));

      $data = array(

        "class_name" => $class_name,
        "class_coordinator" => $class_coord,
        "date_created" => strtotime(date("Y-m-d h:i:sa")),
        "class_bg" => $class_bg

      );

      $result = create("classes", $data);

      if ($result)  {

         $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. ' . $class_name . ' class record added to database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';

      }

    }
    else {
      $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-warning btn-circle"><i class = "fas fa-times"></i></span> Found duplicate record. ' . $class_name . ' class record exists already in database.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }


  }
  
?>

<?php

  $page_title = "Classes";

  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Classes</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAddClass"><i class="fas fa-plus fa-sm"></i> Add Class</a>

<!-----Add Class Modal ---->

<div class="modal fade" id="modalAddClass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100">Add Class</h4>
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
  <input type="text" aria-label="First name" class="form-control" name = "class_name" required>
  
</div>
    </div>

    <!--Coordinator select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Coordinator</span>
  </div>
  <select class="custom-select" name = "class_coord" required>
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
    <input type="file" class="custom-file-input" name="class_bg"
      aria-describedby="inputGroupFileAddon01" required>
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "add_class">Submit Details</button>
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

              $resultClass = read_all("classes", array("blacklist" => 0, "deleted" => 0));

              if ($resultClass)  {
                while ($rowClass = mysqli_fetch_array($resultClass))  { ?>

                  <div class="col-xl-3 col-md-3 col-sm-6 mb-4">

              <!-- DataTables Example -->
          <div class="card shadow mb-1">
            <div class="card-body">
              <div style = "border-radius: 5px 5px 0px 0px; height: 120px; background: url('<?php echo $rowClass['class_bg']; ?>') no-repeat center; background-size: cover; margin: -20px -20px" class = "mb-4">
                
              </div>
            
              <p class = "small mb-2"><button class = "btn btn-sm btn-primary mr-3 btn-circle"><i class = "fas fa-university"></i></button><?php echo $rowClass['class_name']; ?></p>
              <p class = "mb-2 small"><button class = "btn btn-sm btn-warning mr-3 btn-circle"><i class = "fas fa-user"></i></button><?php echo getNameByID("teachers_tb", array("teacher_id" => $rowClass['class_coordinator']), "first_name") . " " . getNameByID("teachers_tb", array("teacher_id" => $rowClass['class_coordinator']), "last_name"); ?></p>
              
            </div>
            <div class="card-footer">
              <span class = "badge badge-primary">

              <?php
                $students_points = read_all("students_tb", array("class_id" => $rowClass['class_id'], "blacklist" => 0));

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
                      <a data-toggle="modal" data-target="#modalEditClass<?php echo $rowClass['class_id']; ?>" class="dropdown-item edit_student" data_id = "" href="javascript:void(0)"><span class = "text-warning"><i class = "fas fa-edit"></i></span>Edit</a>
                      <div class="dropdown-divider"></div>
                      <a data-toggle="modal" data-target="#deleteClassModal<?php echo $rowClass['class_id']; ?>" class="dropdown-item" href="javascript:void(0)" ><span class = "text-danger"><i class = "fas fa-trash-alt"></i></span>Delete</a>
                    </div>
                  </div>
            </div>


            <!-----Edit Class Modal ---->

<div class="modal fade" id="modalEditClass<?php echo $rowClass['class_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
        <input type="hidden" name="class_id" value = "<?php echo $rowClass['class_id']; ?>">
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
    <span class="input-group-text">Name</span>
  </div>
  <input value = "<?php echo $rowClass['class_name']; ?>" type="text" aria-label="First name" class="form-control" name = "class_name" required>
  
</div>
    </div>

    <!--Coordinator select-->
    <div class="form-row mb-4">
      <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Coordinator</span>
  </div>
  <select class="custom-select" name = "class_coordinator" required>
    <option value="">Select</option>
  <?php
    $result = read_all("teachers_tb");

    if ($result)  {
      while ($row = mysqli_fetch_array($result))  { ?>
        <option value="<?php echo $row['teacher_id']; ?>" <?php if ($rowClass['class_coordinator'] == $row['teacher_id']) { echo "selected"; } ?>><?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?></option>
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
    <img width = "60px" height="60px" class="image_upload_preview rounded-circle" src="<?php echo $rowClass['class_bg']; ?>">
  </div>

  <div class = "col-sm-10">
    <div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroupFileAddon01">Change Picture</span>
  </div>
  <div class="custom-file">
    <input type="file" value = "<?php echo $rowHouse['house_icon']; ?>" class="custom-file-input profile_pic_input" name="class_bg"
      aria-describedby="inputGroupFileAddon01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</div>
</div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type= "submit" class="btn btn-primary" name = "edit_class">Submit Details</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-----Edit Class Modal---->



            <!----Delete Class Confirmation Modal ----->

            <div class="modal fade" id="deleteClassModal<?php echo $rowClass['class_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <button type="button" class="btn btn-warning confirm_delete_class" data_enc = "<?php echo sha1(sha1($rowClass['class_id'])); ?>" data_id = "<?php echo $rowClass['class_id']; ?>">Delete</button>
        </div>
      </div>
      </div>
    </div>

<!------Delete Class Confirmation Modal--->
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