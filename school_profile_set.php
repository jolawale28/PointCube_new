<?php
  include "inc/functions.php";

?>

<?php

  $alert_message;
  
  if (isset($_POST['edit_school'])) {

    $school_name = mysql_prep($_POST['school_name']);
    $school_address = mysql_prep($_POST['school_address']);

    $result_name = update("settings_tb", array("settings_name" => "school_name"), array("settings_value" => $school_name));

    if ($result_name) {
      $result_address = update("settings_tb", array("settings_name" => "school_address"), array("settings_value" => $school_address));

      if ($result_address)  {
        $school_picture = uploadFile($_FILES['picture'], "img/schools_bg/", array("jpg", "jpeg", "png", "gif"));

        $result_picture = update("settings_tb", array("settings_name" => "school_bg"), array("settings_value" => $school_picture));

        if ($result_picture)  {

          $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. School details were successfully updated.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>';

        }
        else {
          $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span class = "bg-success btn-circle"><i class = "fas fa-check"></i></span> Operation success. Update not successfull.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>';
        }
      }
    }

  }

?>

<?php

$page_title = "School Profile";
  include "inc/header.php";

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">School Profile</h1>
            

          </div>

          <?php 
                if (isset($alert_message))  {
                  echo $alert_message;
                }

              ?>

          <div class="row" id = "cards_row">

                  <!-- Cards -->
            <div class="col-xl-12 col-md-6 mb-4" id = "">
              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Edit School Profile</h6>
                </div>
                <div class="card-body">
                  <div class = "row">
                    <div class = "col-md-5">
                      <form method = "post" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">

                        <?php
                          $school_name;
                          $school_address;
                          $school_bg;

                          if ($result = read_single("settings_tb", array("settings_name" => "school_name")))  {
                            $school_name = $result['settings_value'];
                          }

                          if ($result = read_single("settings_tb", array("settings_name" => "school_address")))  {
                            $school_address = $result['settings_value'];
                          }
                          if ($result = read_single("settings_tb", array("settings_name" => "school_bg")))  {
                            $school_bg = $result['settings_value'];
                          }
                        ?>
                    <div class="form-group">
                      <label for="exampleInputEmail1">School Name</label>
                      <input type="text" value = "<?php echo $school_name; ?>" class="form-control" name= "school_name" aria-describedby="emailHelp" placeholder="Enter School Name" required>
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Address</label>
                      <textarea class="form-control" name = "school_address" placeholder = "Enter address" required><?php echo $school_address; ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="file-input" class=" form-control-label">Picture</label>
                      <img src="<?php echo $school_bg; ?>" class = "rounded d-block mb-2" width=200px>
                      <input type="file" name="picture" class="form-control" required>
                    </div>
                    <button type="reset" class="btn btn-primary btn-sm">Reset</button>
                    <button type="submit" name = "edit_school" class="float-right btn btn-primary btn-sm"><i class="fas fa-check mr-2 fa-sm"></i>Update</button>
                  </form>
                      
                    </div>
                    
                  </div>

                  
                </div>
              </div>
            </div>

            </div>

        </div>
        <!-- /.container-fluid -->

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