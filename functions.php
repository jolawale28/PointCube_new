<?php
session_start();
// set default_time_zone
date_default_timezone_set("Africa/Lagos") ;
//include connection.php
include 'connection.php'; 
//include db-core.php
include 'db-core.php';     

// filter input data to prevent sql injection queries
function mysql_prep($value){
    $mysql_magic_gpc_quote = get_magic_quotes_gpc();
    $new_enough = function_exists("mysqli_real_escapse_string");
     if($new_enough){   
         if($mysql_magic_gpc_quote){
           $value = stripcslashes($value);  
         }
         $value = mysqli_real_escape_string($value);
     }else{  
         if(!$mysql_magic_gpc_quote){
           $value = addslashes($value);  
         }
     }
     return $value;
}
// custom redirect function
function redirect_to($location = NULL){ 
     if($location != NULL){ 
    header("location: {$location}");
         exit;
     }
 }
 // confirm sql queries
 function confirm_query($result_set){
     global $connection;
        if(!$result_set){
            die("Database query failed: " . mysqli_error($connection));
        }
 }
 // check if user is logged in else redirect to login
 function is_user_logged_in(){
      if(!isset($_SESSION['username'])){
           redirect_to("login");
     }else{
         return true;
     }
     
 }
 // verifies user login credentials if success logs user in
 function verify_user($email,$password){
       global $connection;
       $password = sha1(sha1($password));
       $query = "SELECT * FROM teachers_tb WHERE email = '{$email}' AND password = '{$password}' LIMIT 1";
       $result = mysqli_query($connection,$query);
       confirm_query($result);
       if(mysqli_num_rows($result)>0){
          while($user = mysqli_fetch_array($result)){

              $_SESSION['email'] = $user['email'];
              $_SESSION['first_name'] = $user['first_name'];
              $_SESSION['middle_name'] = $user['middle_name'];
              $_SESSION['last_name'] = $user['last_name'];
              $_SESSION['teacher_id'] = $user['teacher_id'];
              $_SESSION['access_role'] = $user['access_role'];
              $_SESSION['house_id'] = $user['house_id'];
              $_SESSION['profile_pic'] = $user['profile_pic'];

          }         
            return true;
       }
       else{
           return false;
       }
 }
 /**
 * Create a single record
 * 
 * @param string $db_table
 * @param array $data
 * @returns insert row id
 */
 function create($db_table, $data){
    global $connection; 
    $query = query_builder($db_table,'',$data,'C');
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_affected_rows($connection) > 0 ){
         return mysqli_insert_id($connection);
     }else{
         return false;
     }
 }
 /**
 * Read a single record
 * 
 * @param string $db_table
 * @param array $where
 * @return single array of records 
 */
 function read_single($db_table, $where){
    global $connection; 
    $query = query_builder($db_table,$where,false,'R');
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_num_rows($result) > 0 ){
         return mysqli_fetch_array($result);
     }else{
         return false;
     }
 }
 /**
 * put your comment there...
 * 
 * @param string $db_table
 * @param array $where
 * @param array $data
 * @return unique records
 */
 function read_unique($db_table, $where, $data, $order_by = '', $limit = 0, $offset = 0){
    global $connection; 
    $query = query_builder($db_table,$where,$data,'N',false,$order_by, $limit, $offset);
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_num_rows($result) > 0 ){
         return $result;
     }else{
         return false;
     }
 }
 
 /**
 * Read all records
 * 
 * @param string $db_table
 */
  function read_all($db_table,$where = false, $order_by = '', $limit = 0, $offset = 0){
    global $connection; 
    $query = query_builder($db_table,$where,false,'R',false,$order_by,$limit,$offset);
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_num_rows($result) > 0 ){
         return $result;
     }else{
         return false;
     }
 
 }
  
  /**
  * Reads record with selected ids
  * 
  * @param string $db_table
  * @param array $where
  * @param array $data
  * @param int $primary_key
  * 
  * @return records
  */
  function read_in($db_table, $where, $data, $primary_key, $order_by = '', $limit = 0, $offset = 0){
    global $connection; 
    $query = query_builder($db_table,$where,$data,'I',$primary_key,$order_by,$limit,$offset);
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_num_rows($result) > 0 ){
         return $result;
     }else{
         return false;
     }
 }
 /**
 * Update records
 * 
 * @param string $db_table
 * @param array $where
 * @param array $data
 */
 function update($db_table, $where = false, $data){
    global $connection; 
    $query = query_builder($db_table,$where,$data,'U');
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_affected_rows($connection) > 0 ){
         return true;
     }else{
         return false;
     }
 }
  /**
 * delete records
 * 
 * @param string $db_table
 * @param array $where
 */
 function delete($db_table, $where = false){
    global $connection; 
    $query = query_builder($db_table,$where,false,'D');
    $result = mysqli_query($connection,$query);
    confirm_query($result); 
     if( mysqli_affected_rows($connection) > 0 ){
         return true;
     }else{
         return false;
     }
 }

  function validate_form($config_list){
      $error_list = [];
    foreach($config_list as $config){
            $input_value = '';
            $label = '';
            $rules = ''; 
        foreach($config as $key => $value){          
            if($key == 'name'){
                if($_POST){
                  $input_value = $_POST[$value];  
                }else{
                   $input_value = $_GET[$value];     
                }                
            }
            if($key == 'label'){
                $label = ucfirst($value);             
            }
            
            if($key == 'rules'){
               $rules = $value;  
            }
        }
                
            $rules_data = @explode('|',$rules);
             for($a = 0; $a < count($rules_data); $a++){
                 $db_table = '';
                 $rule = $rules_data[$a];
                 if(strpos($rule,'[') !== false){
                   $rule = substr($rules_data[$a],0, strpos($rules_data[$a],'['));
                 } 
                                   
                    $rule_value = substr($rules_data[$a],strpos($rules_data[$a],'[')+1, (strlen($rules_data[$a]) - 1) - (strpos($rules_data[$a],'[') + 1));
                    if(strpos($rule_value,'.') !== false){
                      $db_table = substr($rule_value,0, strpos($rule_value,'.'));  
                    }
                                    
                      if(strtolower($rule) == 'max_length'){ 
                      if(strlen($input_value) > $rule_value && $input_value != ''){
                         $error_list[] = "The {$label} field must be at most {$rule_value} characters in length.";
                        }
                      } 
                      if(strtolower($rule) == 'min_length'){  
                      if(strlen($input_value) < $rule_value && $input_value != ''){
                         $error_list[] = "The {$label} field must be at lest {$rule_value} characters in length.";
                        } 
                      }  
                       if(strtolower($rule) == 'trim'){  
                         trim($input_value);
                       } 
                         
                       if(strtolower($rule) == 'required'){ 
                       if($input_value == ''){
                           $error_list[] = "The {$label} field is required.";
                       }
                       }
                       if(strtolower($rule) == 'integer'){  
                       if(!is_numeric($input_value) && $input_value != ''){
                           $error_list[] = "The {$label} field must be an integer value.";
                         } 
                       }
                        if(strtolower($rule) == 'float'){ 
                       if(!is_float($input_value) && $input_value != ''){
                           $error_list[] = "The {$label} field must be a float value.";
                       } 
                       } 
                        if(strtolower($rule) == 'double'){ 
                       if(!is_double($input_value) && $input_value != ''){
                           $error_list[] = "The {$label} field must be a float value.";
                       }  
                       }
                       if(strtolower($rule) == 'email'){ 
                           $val = filter_var($input_value, FILTER_SANITIZE_EMAIL);
                           if (!filter_var($val, FILTER_VALIDATE_EMAIL) && $input_value != '') {
                              $error_list[] = "The {$label} field is not a valid email address";  
                            }
                       }
                        if(strtolower($rule) == 'is_unique'){ 
                        if(read_single($db_table,array("{$rule_value}" => $input_value)) && $input_value != ''){
                             $error_list[] = "The {$label} field must contain a unique value."; 
                         }
                        }
                          if(strtolower($rule) == 'match'){ 
                              $match_input = '';
                             if($_POST){
                                  $match_input = $_POST[$rule_value];  
                             }else{
                                   $match_input = $_GET[$rule_value];    
                             }
                             
                             if(strtolower($match_input) != $input_value) {
                                $error_list[] = "The {$label} field didn't match.";  
                             }     
                       }                                       
                 
             }
    } 
    if(count($error_list) > 0){
       return $error_list; 
    } else{
        return false;
    }
      
  }
  
  
  /**
  * Checks if user has permission
  * 
  * @param int $user_id
  * @param char $permission_key
  * @return boolean
  */
  function has_permission($user_id, $permission_key = 'v'){
     $db_table_column_name  = '';
     
        if(strtoupper($permission_key) == 'A'){
           $db_table_column_name = 'can_add'; 
        }elseif(strtoupper($permission_key) == 'E'){
         $db_table_column_name = 'can_edit';   
        }elseif(strtoupper($permission_key) == 'D'){
            $db_table_column_name = 'can_delete';
        }else{
          $db_table_column_name = 'can_view';  
        } 
      $where = array(
        $db_table_column_name => 1,
        'post_id' => $_SESSION['post_id'],
        'branch_id' => $_SESSION['branch_id']
      );
      
      $result = read_single('roles',$where);
      if($result){
          return true;
      } else{
          return false;
      }
  }
  /**
  * Function to repopulate form data
  * 
  * @param string $name
  * @param string $type
  */
  function set_value($name,$type,$default = '') {
      $input_value = '';
      $flag = false;
      if($_POST){
        $input_value = @mysql_prep($_POST[$name]); 
        $flag = true;    
      }
      if($_GET){
        $input_value = @mysql_prep($_GET[$name]);
        $flag = true;   
      }
      if($flag){
          if($type == 'checkbox'){
       if(strtolower($default) == $input_value){
           $input_value = " checked";
       }  
     }  
      if($type == 'select'){
       if(strtolower($default) == $input_value){
           $input_value = " selected";
       }  
     }  
      }
     echo $input_value; 
  }

   /**
  * Takes in ID and returns corresponding Name
  * 
  * @param string $db_table
  * @param int $id
  * 
  */
   function getNameByID($db_table, $where, $return_col) {
    
    $result = read_single($db_table, $where);

    if ($result)  {
      return $result[$return_col];
    }
    else {
      return false;
    }
   }
   
    function getReference(){
        $default_random_data = time();
        $enc = '';
        $max_multiplier = 4;
        $multiplier_end_point = 35;
        $mixed_data = array(
        'A','B','C','D','E','F','G','H','I','J',
        'K','L','M','N','O','P','Q','R','S','T','U','V',
        'W','X','Y','Z','0','1','2','3','4','5','6','7',
        '8','9'
        );  
        for($a = 0; $a < strlen($default_random_data); $a++){
            $random_data =  intval(substr($default_random_data,$a,1)) * $max_multiplier;
            if($random_data > $multiplier_end_point){
               $random_data = $multiplier_end_point; 
            }
          $enc = $enc."".$mixed_data[$random_data];  
        }        
        return str_shuffle($enc);
    }

    //@param $file: 
    //@param $destination: Destination
    //@param $fileTypes: array
    function uploadFile($file, $destination, $allowed = array(), $sizeLimit = 5000000)  {

      $fileName = $file['name'];
      $fileExt = explode(".", $file['name']);
      $fileActualExt = strtolower(end($fileExt));
      $fileSize = $file['size'];

      if ($file['error'] === 0) { // Check if there was no error during upload

        // Check file type
        if (in_array($fileActualExt, $allowed)) {

          if ($fileSize < $sizeLimit) {

            $fileNameNew = uniqid("", true) . "." . $fileActualExt;
            $fileDestination = $destination . "" . $fileNameNew;

            move_uploaded_file($file['tmp_name'], $fileDestination);
            
            return $fileDestination;
          }
          else {
            return false;
          }
        }
        else {

          return false;
        }
        
      } else { // If error, return error message

        return false;

      }

    }

 ?>