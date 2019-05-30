
<?php

  $page_title = "PointCube | Login";

  $message;
  $loggedin;

  include "inc/functions.php";

  if (isset($_GET['loggedout'])) {
    // remove all session variables
    session_unset(); 

    // destroy the session 
    session_destroy();

     $message = "<i class = 'fas fa-times'></i> Not logged in!";
  }
  else {

  }

  if (isset($_POST['login_btn'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (verify_user($email, $password)) {
      redirect_to("index.php");
    }
    else {
      $message = "<i class = 'fas fa-times'></i> Username or password not correct!";

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

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="mb-2 text-center text-black-50">
                    <h1 class="h4 mb-4">Login</h1>
                    <div class = "notif_alert text-danger mb-2" style = "font-size: 0.7rem">&nbsp; <?php if (isset($message)) { echo $message; } ?></div>
                  </div>
                  <form class="user" action = "<?php echo htmlspecialchars(($_SERVER['PHP_SELF'])); ?>" method = POST>
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" name = "email" aria-describedby="emailHelp" placeholder="Enter Email Address..." required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name = "password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                         <a class="mt-1 float-right" href="forgot-password.html">Forgot Password?</a>
                      </div>
                    </div>
                    <button type = "submit" name = "login_btn" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                    </form>
                    <hr>
                    <p class = "small text-center">Or Login with:</p>
                    <center>
                     <a href="#" class="btn btn-primary btn-icon-split">
                    <span class="icon">
                      <i class="fab fa-facebook-f"></i>
                    </span>
                    <span class="text">Facebook</span>
                  </a>
                     <a href="#" class="btn btn-danger btn-icon-split">
                    <span class="icon">
                      <i class="fab fa-google"></i>
                    </span>
                    <span class="text">Google</span>
                  </a>
                </center>
                  
                  <hr>
                  <div class="text-center">
                    <a class="small" href="register">Create an Account!</a>
                  </div>
                </div>
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

  <script type="text/javascript">
    setTimeout(function() {
      $(".notif_alert").html("&nbsp;");
    }, 5000);
  </script>

</body>

</html>
