
<?php
	
	include "functions.php";

  //  Reject Card

  if (isset($_POST['reject_card'])) {

    // header("Location: redirect.php");

    $check_for_cards = read_all("cards_tb", array("validated" => 0));
    $check_for_cards_rows = mysqli_num_rows($check_for_cards);

    if ($check_for_cards_rows == 0) {

      echo $check_for_cards_rows ."dgg";
      // return;
      redirect_to("../cards.php");

    }
    else {

      $card_id = $_POST['reject_card'];

      $result = update("cards_tb", array("card_id" => $card_id), array("validated" => 1));

      if ($result) {

        $num_cards = read_all("cards_tb", array("validated" => 0));

        if ($num_cards) {
          echo mysqli_num_rows($num_cards);
        }
        else {
          echo " &nbsp;0";
        }
      }

    }
    
  }

  //  Accept Card

  if (isset($_POST['accept_card'])) {

    $card_id = $_POST['accept_card'];

    $card_result = read_single("cards_tb", array("card_id" => $card_id, "validated" => 0));

    if ($card_result) {
      $student_point = getNameByID("students_tb", array("student_id" => $card_result['student_id']), "points");
      $card_point = $card_result['card_value'];

      $set_point = $student_point + $card_point;

      $result = update("students_tb", array("student_id" => $card_result['student_id']), array("points" => $set_point));

      if ($result)  {

        $validate_card = update("cards_tb", array("card_id" => $card_id), array("validated" => 1));

        if ($validate_card) {
          $num_cards = read_all("cards_tb", array("validated" => 0));

          if ($num_cards) {
            echo mysqli_num_rows($num_cards);
          }
          else {
           echo " 0";
          }
        } 

          
        }
      
    }

    // if ($result)  {

    //     $pointNum;

    //     $points = read_single("students_tb", array("student_id" => $student_id), "points");

    //     if ($points) {
    //      $pointNum = $points['points'] + $point;

    //      $data = array(
    //        "points" => $pointNum
    //      );

    //      $updatePoint = update("students_tb", array("student_id" => $student_id), $data);

    //     }
    //   }
    //   else {
        
    //   }

  }

  //  Validate Registration Code

  if (isset($_POST['reg_code_validate'])) {
    $reg_code = $_POST['reg_code_validate'];

    $result = read_single("reg_codes_tb", array("reg_code_value" => $reg_code));

    if ($result)  {
      echo 1;
    }
    else {
      echo 0;
    }
  }

	if (isset($_POST['getStudents'])) {

		$house_id = $_POST['house_id'];
		$student_name = $_POST['student_name'];

		if ($result)	{
			while ($row = mysqli_fetch_array($result))	{

				$profile_pic = $row['profile_pic'];
				$point = $row['points'];
				$student_name = $row['first_name'] . " " . $row['last_name'];

				echo '';

			}
		}


  	}


  	//  Set House

  if (isset($_POST['set_house']))  {
    
    $house_id = $_POST['house_id'];
    
    $result = read_single("houses_tb", array("house_id" => $house_id));

    if ($result)  {
      echo $result['house_name'] . "," . $result['house_icon'];
    }

    
  }

  	//  Get Student to list

  if (isset($_POST['getStudent']))  {
    
    $student_name = $_POST['getStudent'];

    $result = read_single("students_tb", array("fullname" => $student_name, "blacklist" => 0));

    if ($result)  {

    	$getHouse = read_single("houses_tb", array("house_id" => $result['house_id']));
    	
      echo "success" . "," . $getHouse['house_color'] . "," . $result['student_id'] . "," . $result['fullname'] . "," . $result['profile_pic'] . "," . $getHouse['house_icon'] . "," . $getHouse['house_id'];
    }
    else {
    	echo $student_name . " This record does not exist in the database.";
    }

    
  }

  //	Change card details to read

  if (isset($_POST['card_details_read']))	{
  	$card_id = $_POST['card_details_read'];

  	$result = update("cards_tb", array("card_id" => $card_id), array("notif_status" => 1));

  	if ($result)	{
  		$cards_result = read_all("cards_tb", array("notif_status" => 0));

  		echo ($cards_result)? mysqli_num_rows($cards_result): "0";

  	}
  }

  if (isset($_POST['award_group_points']))	{

  	$student_id = $_POST['award_group_points'];
  	$comment = $_POST['comment'];
  	$points = $_POST['points'];
  	$house_id = getNameByID("students_tb", array("student_id" => $student_id), "house_id");

  	$data = array(
  		"card_value" => $points,
  		"student_id" => $student_id,
  		"house_id" => $house_id,
  		"comment" => $comment,
  		"date_created" => strtotime(date("Y-m-d h:i:sa")),
      "teacher_id" => $_SESSION['teacher_id']
  	);

  	$result = create("cards_tb", $data);

    if ($result)  {

        // redirect_to("../award_point?message=success");

      }
      else {
        // redirect_to("../award_point?message=failed");
      }

  }

    //  Send card to database

  if (isset($_POST['award_point']))  {
    
    $house_id = mysql_prep($_POST['house_id']);
    $student_id = mysql_prep($_POST['student_id']);
    $point = mysql_prep($_POST['points']);
    $comment = mysql_prep($_POST['comment']);

    $data = array(

      "card_value" => $point,
      "student_id" => $student_id,
      "house_id" => $house_id,
      "comment" => $comment,
      "date_created" => strtotime(date("Y-m-d h:i:sa")),
      "teacher_id" => $_SESSION['teacher_id']

    );
      
      $result = create("cards_tb", $data);

      if ($result)  {

        redirect_to("../award_point?message=success");

      }
      else {
        redirect_to("../award_point?message=failed");
      }

  }


?>

<?php

	if (isset($_POST['get_card']))	{ 

		$card_id = $_POST['get_card'];

		$result = read_single("cards_tb", array("card_id" => $card_id));

		$profile_pic = getNameByID("students_tb", array("student_id" => $result['student_id'], "blacklist" => 0), "profile_pic");
		$house_icon = getNameByID("houses_tb", array("house_id" => $result['house_id']), "house_icon");
		$teacher_name_1 = getNameByID("teachers_tb", array("teacher_id" => $result['teacher_id']), "first_name");
		$teacher_name_2 = getNameByID("teachers_tb", array("teacher_id" => $result['teacher_id']), "middle_name");
		$teacher_name_3 = getNameByID("teachers_tb", array("teacher_id" => $result['teacher_id']), "last_name");


		?>

		<div class="modal-body">
        <div class="col-xl-12 col-md-12 col-sm-12">

              <!-- DataTables Example -->
          
              <div style = "border-radius: 5px 5px 0px 0px; height: 150px; position: relative; background: url('<?php echo $profile_pic; ?>'); background-size: 100% 100%; margin: -20px -20px" class = "">
                <p style = "position: absolute; bottom: -16px; width: 100%; display: block; background: rgba(0, 0, 0, 0.8)" class = "p-2">

                  <span style = "font-size: 0.9rem" class="text-white"><?php echo getNameByID("students_tb", array("student_id" => $result['student_id']), "fullname"); ?></span>

                  <img style = "" data-toggle="tooltip" title="Eagles" src="<?php echo $house_icon; ?>" class = "float-right rounded-circle" width=30px height=30px>

                <span style = "font-size: 0.8rem" class="float-right mr-3 mt-1 badge badge-success"><?php echo $result['card_value']; ?></span>

                </p>
                
              </div>

              <p style = "font-size: 0.8rem" class = "mt-5 text-center text-wrap">
                <?php echo $teacher_name_1. " " . $teacher_name_2 . " " . $teacher_name_3; ?>
              </p>

                <div style = "font-size: 0.7rem" data-toggle="popover" title="<?php echo $result['comment']; ?>" class = "">
                	<center>
                     <i class = "fas fa-quote-left"></i> <?php echo $result['comment']; ?> <i class = "fas fa-quote-right"></i>
                 </center>
                </div>
              
        </div>

      </div>
      <div class="modal-footer">
        <a type="button" data-toggle="tooltip" title="Dismiss" class="btn btn-sm btn-circle" data-dismiss="modal"><i class="text-danger fas fa-times-circle"></i></a>
        <a type="button" data-toggle="tooltip" title="Accept"  data-dismiss="modal" class="validate_card btn btn-sm btn-circle"><i class="text-success fas fa-check-circle"></i></a>

      </div>

	<?php }

?>