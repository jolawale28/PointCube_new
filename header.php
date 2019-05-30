<?php

  if (!(isset($_SESSION['teacher_id']) && isset($_SESSION['email']))) {
    // remove all session variables
    session_unset(); 

    // destroy the session 
    session_destroy();

    redirect_to("login.php?loggedout=true");
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

  <title>PointCube | <?php echo $page_title; ?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free-5.6.3-web/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- theme style -->
    <link rel="stylesheet" href="css/master_style.css">
    
    <!-- Fab Admin skins -->
    <link rel="stylesheet" href="css/skins/_all-skins.css">

      <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!----My custom CSS--->
  <!-- <link href="vendor/MDB-Free_4.8.1/css/mdb.css" rel="stylesheet"> -->

  <!----My custom CSS--->
  <link href="css/custom.css" rel="stylesheet">

  <script>
            $(document).ready(function () {
                $("select").selectr({
                    title: 'What would you like to drink?',
                    placeholder: 'Search beverages'
                });
            });
        </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="mb-0 p-0 sidebar-brand d-flex align-items-center justify-content-center" href="index">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-cube"></i>
        </div>
        <div class="sidebar-brand-text mx-2">PointCube</div>

      </a>

      <a data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="mb-2 text-center text-white" style = "cursor: pointer;font-size: 0.7rem">
        <?php
          $result = read_single("settings_tb", array("settings_name" => "school_name"));

          if ($result)  {
            echo $result['settings_value'];
          }

        ?> 
        <i class = "ml-2 fas fa-angle-down"></i>
      </a>

      <?php

        $school_background;

        $school_bg = read_single("settings_tb", array("settings_name" => "school_bg"));

        if ($school_bg) {
          $school_background = $school_bg['settings_value'];
        }

      ?>
      <div style = "border-radius: 5px; border: 0; background: url('<?php echo $school_background; ?>'); background-position: center; background-size: cover;" class="collapse m-2 small text-white" id="collapseExample">
  <div style = "background: rgba(10, 10, 10, 0.4); border: 0" class="text-center p-2 card card-body">
    <center class = "mb-2">
    <img width = "30px" height = "30px" class="float-center rounded-circle" src="<?php echo $_SESSION['profile_pic']; ?>">
  </center>
    
    <span class="text-white"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
     <span class="text-success small"><?php echo getNameByID("access_roles_tb", array("access_roles_id" => $_SESSION['access_role']), "access_role_name"); ?></span>

  </div>
</div>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php if ($page_title == "Home") { echo "active"; } ?>">
        <a class="nav-link" href="index">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Tasks
      </div>

       <!-- Nav Item - Students -->
      <li class="nav-item <?php if ($page_title == "Check Cards") { echo "active"; } ?>">
        <a class="nav-link" href="cards">
          <i class="fas fa-fw fa-file-signature"></i>
          <span>Cards</span> 
          <span class = "badge text-dark notif_cards" style = "background: yellow; font-size: 0.7rem">&nbsp;
            <?php
                    $cards_result = read_all("cards_tb", array("validated" => 0));

                    echo ($cards_result)? mysqli_num_rows($cards_result): "0";

                  ?>
          </span>
        </a>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item <?php if ($page_title == "Award Points") { echo "active"; } ?>">
        <a class="nav-link" href="award_point">
          <i class="fas fa-fw fa-plus-circle"></i>
          <span>Award Points</span></a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Award Points</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="buttons.html">Student</a>
            <a class="collapse-item" href="cards.html">Class</a>
            <a class="collapse-item" href="cards.html">House</a>
            <a class="collapse-item" href="utilities-color.html">Group</a>
          </div>
        </div>
      </li> -->

      <!-- Nav Item - Blacklist Collapse Menu -->
      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBlacklist" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-user-alt-slash"></i>
          <span>Blacklist</span>
        </a>
        <div id="collapseBlacklist" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Set restrictions:</h6>
            <a class="collapse-item" href="utilities-color.html">Student</a>
            <a class="collapse-item" href="utilities-color.html">Class</a>
            <a class="collapse-item" href="utilities-color.html">House</a>
            <a class="collapse-item" href="utilities-color.html">Group</a>
          </div>
        </div>
      </li> -->

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Entities
      </div>

      <!-- Nav Item - Students -->
      <li class="nav-item <?php if ($page_title == "Students") { echo "active"; } ?>">
        <a class="nav-link" href="students">
          <i class="fas fa-fw fa-user-friends"></i>
          <span>Students</span>
        </a>
      </li>

      <!-- Nav Item - Teachers -->
      <li class="nav-item <?php if ($page_title == "Teachers") { echo "active"; } ?>">
        <a class="nav-link" href="teachers">
          <i class="fas fa-fw fa-user-tie"></i>
          <span>Teachers</span></a>
      </li>

      <!-- Nav Item - Classes -->
      <li class="nav-item <?php if ($page_title == "Classes") { echo "active"; } ?>">
        <a class="nav-link" href="classes">
          <i class="fas fa-fw fa-chalkboard"></i>
          <span>Classes</span></a>
      </li>

      <!-- Nav Item - Houses -->
      <li class="nav-item <?php if ($page_title == "Houses") { echo "active"; } ?>">
        <a class="nav-link" href="houses">
          <i class="fas fa-fw fa-university"></i>
          <span>Houses</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Extras
      </div>

      <!-- Nav Item - Statistics -->
      <li class="nav-item <?php if ($page_title == "") { echo "active"; } ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePointVault" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-dice-d6"></i>
          <span>PointVault&trade;</span>
        </a>
          <div id="collapsePointVault" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Vault Maintenance: </h6>
            <a class="collapse-item" href="utilities-color.html">Statistics</a>
            <!-- <a class="collapse-item" href="utilities-color.html">Print Report</a> -->
            <!-- <a class="collapse-item" href="utilities-color.html">History</a> -->
            <a class="collapse-item" href="utilities-color.html">Configure</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item <?php if ($page_title == "School Profile" || $page_title == "Access Roles") { echo "active"; } ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Settings</span>
        </a>
        <div id="collapseSettings" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Change your settings:</h6>
            <a class="collapse-item" href="school_profile_set">School Profile</a>
            <a class="collapse-item" href="access_role_set">Access Roles</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Logout -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button id="sidebarToggle" class="push_sidebar ml-2 border-0 btn btn-circle btn-sm btn-primary"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fas fa-bars"></i>
          </button>

          <!-- Logout -->
                <!-- <a href="#" data-toggle="modal" data-target="#logoutModal" class="ml-2 btn btn-outline-secondary" type="button">
                  <i class="fas fa-power-off fa-sm"></i>
                </a> -->
                <button id="sidebarToggle" class="push_sidebar ml-2 border-0 btn btn-circle btn-sm btn-primary"><i class = "fas fa-angle-left"></i></button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <!-- <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i> -->
                <!-- Counter - Alerts -->
                <!-- <span class="badge badge-danger badge-counter">3+</span>
              </a> -->
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    $290.29 has been deposited into your account!
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Messages -->
                <span style = "font-size: 0.85rem" class="badge badge-danger badge-counter notif_counter">
                  
                  
                </span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  New Cards
                  <span style = "font-size: 0.7rem" class="ml-1 badge badge-danger badge-counter notif_counter">
                  
                  <?php
                    $result = read_all("cards_tb", array("notif_status" => 0));

                    echo ($result)? mysqli_num_rows($cards_result): "0";

                  ?>
                </span>

                </h6>

                <?php
                  $getCards = read_all("cards_tb", array("notif_status" => 0), '', 5);

                  if ($getCards)  {
                    while ($rows = mysqli_fetch_array($getCards)) { 
                        $house = read_single("houses_tb", array("house_id" => $rows['house_id']));
                        $student = read_single("students_tb", array("student_id" => $rows['student_id']));
                        $point_mark = ($rows['card_value'] < 0)? "badge-danger" : "badge-success" ;
                        $read_status = "";

                        if ($rows['notif_status'] == 0) {

                          $read_status = "font-weight-bold";
                        }
                        else {
                          $read_status = "";
                        }

                                                                  ?>

                      <a data-toggle="modal" data_id = "<?php echo $rows['card_id']; ?>" data-target="#modalRelatedContent" class="dropdown-item d-flex align-items-center show_cards" href="#">
                        
                        <div class="dropdown-list-image mr-3">
                          <img class="rounded-circle" src="<?php echo $student['profile_pic']; ?>" alt="">
                          <div class="status-indicator"  style = "background: <?php echo $house['house_color']; ?>"></div>
                        </div>
                        <div class="<?php echo $read_status; ?>">
                          <div class="text-truncate"><?php echo $rows['comment']; ?></div>
                          <div class="text-gray-500">

                            <?php echo $student['first_name']  . " " . $student['last_name']; ?> <span class = "badge-counter badge <?php echo $point_mark; ?>"><?php echo $rows['card_value']; ?></span> · 58m

                          </div>
                        </div>
                      </a>

                   <?php }
                  }

                ?>
                
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>

              <!-- Modal -->
<div class="modal modal-md fade" id="modalRelatedContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content pt-3 card_details">
      <div class="modal-body">
        <div class="col-xl-12 col-md-12 col-sm-12">

              <center>
                <i class="fas fa-spinner fa-spin"></i>
              </center>
              
        </div>

      </div>
      <div class="modal-footer">
        <a type="button" data-toggle="tooltip" title="Dismiss" class="btn btn-sm btn-circle" data-dismiss="modal"><i class="text-danger fas fa-times-circle"></i></a>
        <a type="button"  data-dismiss="modal" data-toggle="tooltip" title="Accept" class="btn btn-sm btn-circle"><i class="text-success fas fa-check-circle"></i></a>

      </div>
    </div>
  </div>
</div>

<!--Modal-->

            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
                <img class="img-profile rounded-circle" src="<?php echo $_SESSION['profile_pic']; ?>">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->