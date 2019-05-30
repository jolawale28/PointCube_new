<?php 

// Base function for sql query
 
 /**
 * put your comment there...
 *    
 * @param mixed $db_table
 * @param array $where
 * @param array $query_data
 * @param string $flag
 * @param int $primary_key
 * @returns string query
 */
 function query_builder($db_table,$where = false, $query_data = false, $flag = false, $primary_key = 0, $order_by = '', $limit = 0, $offset = 0){
     $buffer = '';
        if($flag == 'C'){ // Create query
            $cnt = 0;
            $buffer = "INSERT INTO {$db_table}(";
             foreach( $query_data as $key => $value ) {
                 $cnt++;
                 $buffer .= "{$key}";
                  if($cnt < count($query_data)){
                    $buffer .= ', ';
                  }
              } 
             $buffer .= ")";
             $buffer .= " VALUES(";
              $cnt = 0;
             foreach( $query_data as $value ) {
                 $cnt++;
                 $buffer .= "'{$value}'";
                  if($cnt < count($query_data)){
                    $buffer .= ', ';
                  }
              } 
             $buffer .= ")"; 
        }
        // 
        if($flag == 'R'){ // Read query
            if(!$where){
                if($query_data){
                    $cnt = 0;
                   $buffer = "SELECT ";
                    foreach( $query_data as $value ) {
                      $cnt++;
                      $buffer .= "{$value}";
                      if($cnt < count($query_data)){
                        $buffer .= ', ';
                      }
                   }  
                    $buffer .= " FROM {$db_table}"; 
                     if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key}  {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                }else{
                    $buffer = "SELECT * FROM {$db_table}";
                     if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                }
                 
            }else{
                    if($query_data){                       
                        $buffer = "SELECT "; 
                        $cnt = 0;
                              foreach( $query_data as $value ) {
                                  $cnt++;
                                  $buffer .= "{$value}";
                                  if($cnt < count($query_data)){
                                    $buffer .= ', ';
                                  }
                               }
                         $buffer .= " FROM {$db_table} WHERE ";                  
                        $cnt = 0;
                        foreach( $where as $key => $value ) {
                            $cnt++;
                            $buffer .= "{$key} = '{$value}'";
                            if($cnt < count($where)){
                            $buffer .= ' AND ';
                      }
                  }
                       if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                  
                    } else{                                  
                        $buffer = "SELECT * FROM {$db_table} WHERE ";                  
                        $cnt = 0;
                         foreach( $where as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} = '{$value}'";
                         if($cnt < count($where)){
                            $buffer .= ' AND ';
                      }
                  }
                    if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
            }        
        }
        }
        
           if($flag == 'N'){ // Read unique query
            if(!$where){
                if($query_data){
                    $cnt = 0;
                   $buffer = "SELECT DISTINCT(";
                    foreach( $query_data as $value ) {
                      $cnt++;
                      $buffer .= "{$value}";
                      if($cnt < count($query_data)){
                        $buffer .= ', ';
                      }
                   }  
                    $buffer .= ") FROM {$db_table}"; 
                     if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                }
                 
            }else{
                    if($query_data){                       
                        $buffer = "SELECT DISTINCT("; 
                        $cnt = 0;
                              foreach( $query_data as $value ) {
                                  $cnt++;
                                  $buffer .= "{$value}";
                                  if($cnt < count($query_data)){
                                    $buffer .= ', ';
                                  }
                               }
                         $buffer .= ") FROM {$db_table} WHERE ";                  
                        $cnt = 0;
                        foreach( $where as $key => $value ) {
                            $cnt++;
                            $buffer .= "{$key} = '{$value}'";
                            if($cnt < count($where)){
                            $buffer .= ' AND ';
                      }
                  }
                   if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key}  {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                    }
                 
            }        
        }
        
        
           if($flag == 'I'){ // Read IN query

                    if($query_data){                       
                        $buffer = "SELECT "; 
                        $cnt = 0;
                              foreach( $query_data as $value ) {
                                  $cnt++;
                                  $buffer .= "{$value}";
                                  if($cnt < count($query_data)){
                                    $buffer .= ', ';
                                  }
                               }
                         $buffer .= " FROM {$db_table} WHERE {$primary_key} IN(";                  
                        $cnt = 0;
                        foreach( $query_data as $key => $value ) {
                            $cnt++;
                            $buffer .= "{$value}";
                            if($cnt < count($query_data)){
                            $buffer .= ', ';
                      }
                        }
                         $buffer .= ') ';
                         if($where){
                              $buffer .= ' AND ';
                            $cnt = 0;
                         foreach( $where as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} = '{$value}'";
                         if($cnt < count($where)){
                            $buffer .= ' AND ';
                           } 
                         }
                          if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                         }
                        
                    } else{                                  
                        $buffer = "SELECT * FROM {$db_table} WHERE {$primary_key} IN(";                  
                          $cnt = 0;
                        foreach( $query_data as $key => $value ) {
                            $cnt++;
                            $buffer .= "{$value}";
                            if($cnt < count($query_data)){
                            $buffer .= ', ';
                      }
                        }
                         $buffer .= ') ';
                         if($where){
                              $buffer .= ' AND ';
                            $cnt = 0;
                         foreach( $where as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} = '{$value}'";
                         if($cnt < count($where)){
                            $buffer .= ' AND ';
                           } 
                         }
                          if(is_array($order_by)){                       
                        $cnt = 0;
                        $buffer .= ' ORDER BY ';
                         foreach( $order_by as $key => $value ) {
                         $cnt++;
                         $buffer .= "{$key} {$value}";
                         if($cnt < count($order_by)){
                            $buffer .= ', ';
                      }
                    }
                    }
                    if($limit){
                       $buffer .= " LIMIT {$offset}, {$limit}"; 
                    }
                 
                         } 
                    }
                 
                   
        }
        
           if($flag == 'U'){ // Update query
            if(!$where){
                if($query_data){
                    $cnt = 0;
                   $buffer = "UPDATE {$db_table} SET ";
                    foreach( $query_data as $key => $value ) {
                      $cnt++;
                       $buffer .= "{$key} = '{$value}'";
                      if($cnt < count($query_data)){
                        $buffer .= ', ';
                      }
                   }   
                }
                 
            }else{
                    if($query_data){                       
                        $buffer = "UPDATE {$db_table} SET ";
                        $cnt = 0;
                              foreach( $query_data as $key => $value ) {
                                  $cnt++;
                                  $buffer .= "{$key} = '{$value}'";
                                  if($cnt < count($query_data)){
                                    $buffer .= ', ';
                                  }
                               }
                         $buffer .= " WHERE ";                  
                        $cnt = 0;
                        foreach( $where as $key => $value ) {
                            $cnt++;
                            $buffer .= "{$key} = '{$value}'";
                            if($cnt < count($where)){
                            $buffer .= ' AND ';
                      }
                  }
                    }
                 
            }        
        }
        
        
                   if($flag == 'D'){ // Update query
            if(!$where){
                    $cnt = 0;
                   $buffer = "DELETE  FROM {$db_table}";
                 
            }else{                     
                        $buffer = "DELETE FROM {$db_table}";
                         $buffer .= " WHERE ";                  
                        $cnt = 0;
                        foreach( $where as $key => $value ) {
                            $cnt++;
                            $buffer .= "{$key} = '{$value}'";
                            if($cnt < count($where)){
                            $buffer .= ' AND ';
                      }
                  } 
            }        
        }
        
        $buffer .= ";";
        return $buffer;
 }
  /**
  * Flat sql query function
  * 
  * @param string $sql_query
  * @return query;
  */
 function query_prep($sql_query){
    return $sql_query; 
 }

 
 
?>

