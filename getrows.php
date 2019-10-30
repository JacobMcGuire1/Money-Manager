<?php
  session_start();
  include ("database.php");
  $database = new Database();
  $database->__construct();
  
  //Gets the extra rows for the double click thing.
  
  $stmt = $database->prepare("SELECT * from owage WHERE bill_id = :id;");
  $stmt->bindValue(':id', $_POST['id'], SQLITE3_INTEGER);
  $peoplebills = $stmt->execute();
  
  while ($row = $peoplebills->fetchArray()){
    
    $stmt = $database->prepare("SELECT username from users WHERE id = :userid;");
    $stmt->bindValue(':userid', $row['user_id'], SQLITE3_INTEGER);
    $name = $stmt->execute()->fetchArray();
          
    if ($row['paid']){
      $paid = "Yes";
    }else{
      $paid = "No";
    }
    if ($row['user_id'] != $_SESSSION['id']){
      $html .= "</tr><tr class='moreinfo'>
              <td class='billsdata'>". $name[0] ." --&rarr;</td>
              <td class='billsdata'>&pound;". $row['cost']/100 ."</td>
              <td class='billsdata'>". $paid ."</td>
              <td class='billsdata'></td>
              <td class='billsdata'></td>
              <td class='billsdata'></td>
              <td class='billsdata'></td>
            </tr>";
    }
  }
  //echo $html;
  $sendback->name = $html;
  $myJSON = json_encode($sendback);
  echo $myJSON;
  //echo "doiwhndoi";  
?>