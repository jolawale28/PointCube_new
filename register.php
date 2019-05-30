
<?php

  $page_title = "PointCube | Create Account";

  include "inc/functions.php";
?>

<?php

  $message;

  if (isset($_POST['create_account']))  {

    $first_name = mysql_prep($_POST['first_name']);
    $middle_name = mysql_prep($_POST['middle_name']);
    $last_name = mysql_prep($_POST['last_name']);
    $reg_code = mysql_prep($_POST['reg_code']);
    $email = mysql_prep($_POST['email']);
    $password = sha1(sha1(mysql_prep($_POST['password'])));

    $data = array(
      "email" => $email
    );

    $check_for_duplicate = read_all("teachers_tb", $data);

    if ($check_for_duplicate)  {
      $message = "<i class = 'fas fa-times'></i> Email has been taken!";

    }
    else {
      // $message = "<i class = 'text-success fas fa-check'></i> <span class = 'text-success'>Email is available!</span>";

      $data = array(
        "first_name" => $first_name,
        "middle_name" => $middle_name,
        "last_name" => $last_name,
        "reg_code" => $reg_code,
        "email" => $email,
        "password" => $password,
        "date_created" => mysql_prep(strtotime(date("Y-m-d h:i:sa")))
      );

      $result = create("teachers_tb", $data);


      if ($result)  {
        $result = delete("reg_codes_tb", array("reg_code_value" => $reg_code));
        verify_user($email, $password);
        redirect_to("index.php");
      }
    }

  }


?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $page_title; ?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free-5.6.3-web/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-2">Create an Account!</h1>
                <div class = "notif_alert text-danger mb-2" style = "font-size: 0.7rem">&nbsp; <?php if (isset($message)) { echo $message; } ?></div>
              </div>
              <form class="user" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method=post enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" class="text-center form-control form-control-user reg_code" name = "reg_code" placeholder="Registration Code" required>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="text-center form-control form-control-user" name = "first_name" placeholder="First Name" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="text-center form-control form-control-user" name = "middle_name" placeholder="Middle Name" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12">
                    <input type="text" class="text-center form-control form-control-user" name = "last_name" placeholder="Last Name" required>
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" class="text-center form-control form-control-user" name = "email" placeholder="Email Address" required>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="text-center form-control form-control-user" name = "password" placeholder="Password" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="text-center form-control form-control-user" name = "confirm_password" placeholder="Confirm Password" required>
                  </div>
                </div>
                <button type = "submit" name = "create_account" class="btn btn-primary btn-user btn-block">
                  Register Account
                </button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="login">Already have an account? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script src="js/custom.js"></script>

  <script type="text/javascript">
    setTimeout(function() {
      $(".notif_alert").html("&nbsp;");
    }, 5000)
  </script>

</body>

</html>
