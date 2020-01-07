
<?php /* Updates the bill based on changes a user has made on the front end. */
  $output = "";
  session_start();
  include ("database.php");
  include ("security.php");
  $database = new Database();
  $database->__construct();
  if (isset($_SESSION['id'])) {
    $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :userid2;");
    $stmt->bindValue(':userid2', $_SESSION["id"], SQLITE3_INTEGER);
    $group = $stmt->execute()->fetchArray();
    if (!empty($group)){
      $stmt = $database->prepare("SELECT * from owage WHERE user_id = :id;");
      $stmt->bindValue(':id', $_SESSION['id'], SQLITE3_INTEGER);
      $bills = $stmt->execute();
      $html .= "
        <table id='billstable'>
          <tr id='billstoprow'>
            <th id='billsheading'>Bill Name</th>
            <th id='billsheading'>Cost</th>
            <th id='billsheading'>Paid?</th>
            <th id='billsheading'>Money paid so far</th>
            <th id='billsheading'>Cost (Total)</th>
            <th id='billsheading'>Bill Creator</th>
            <th'></th>
          </tr>
        ";
      $count = 0;
      while(($row = $bills->fetchArray())) {
        $count++;
        $stmt = $database->prepare("SELECT * from bills WHERE id = :id;");
        $stmt->bindValue(':id', $row['bill_id'], SQLITE3_INTEGER);
        $names = $stmt->execute()->fetchArray();
        
        $stmt = $database->prepare("SELECT * from owage WHERE bill_id = :id;");
        $stmt->bindValue(':id', $row['bill_id'], SQLITE3_INTEGER);
        $others = $stmt->execute();
        
        $totalpaid = 0.0;
        $totalcost = 0.0;
        while(($owagerow = $others->fetchArray())) {
          if ($owagerow['paid']){
            $totalpaid += $owagerow['cost'];
          }
          $totalcost += $owagerow['cost'];
        }
        
        if ($row['paid']){
          $paid = "Yes";
        }else{
          $paid = "No";
        }
        
        $stmt = $database->prepare("SELECT creator_id from groups WHERE id = :id;");
        $stmt->bindValue(':id', $group[0], SQLITE3_INTEGER);
        $groupcreatorid = $stmt->execute()->fetchArray();
        
        $stmt = $database->prepare("SELECT username from users WHERE id = :id;");
        $stmt->bindValue(':id', $names['creator_id'], SQLITE3_INTEGER);
        $billcreatorname = $stmt->execute()->fetchArray();
        
        if ($names['creator_id'] == $_SESSION['id'] || $groupcreatorid[0] == $_SESSION['id']){
          $deletion = "<a class = 'delete' id='". $row['bill_id'] ."'>Delete</a>";
        }else{
          $deletion = "";
        }
        $html .= "<tr  class='bill' id='". $row['bill_id'] ."'>
                <td class='billsdata'>". $names['name'] ."</td>
                <td class='billsdata'>&pound;". $row['cost']/100 ."</td>
                <td class='billsdata'><a class = 'paid' id='". $row['bill_id'] ."'>". $paid ."</a></td>
                <td class='billsdata'>&pound". $totalpaid/100 ."</td>
                <td class='billsdata'>&pound". $totalcost/100 ."</td>
                <td class='billsdata'>". $billcreatorname[0] ."</td>
                <td class='billsdata'>". $deletion ."</td>
              </tr>";
      }
      $html .= "</table>";
      
      //Create bill
      //Get group
      $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :userid2;");
      $stmt->bindValue(':userid2', $_SESSION["id"], SQLITE3_INTEGER);
      $group = $stmt->execute()->fetchArray();
      
      $stmt = $database->prepare("SELECT * from groupage WHERE group_id = :groupid;");
      $stmt->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
      $members = $stmt->execute();
      $html .= "
        <br>
        <div id='newbill'>
        <h2>Create a new bill</h2>
        
        <form action='createbill.php' method='post'>
          <label>Name:</label>
    		  <input type='text' name='name'>
          <br>
          <br>
          <label>Money paid by each member:</label>
          <br>
          <table>
          ";
      while(($row = $members->fetchArray())) { 
        $stmt = $database->prepare("SELECT username from users WHERE id = :userid;");
        $stmt->bindValue(':userid', $row['user_id'], SQLITE3_INTEGER);
        $name = $stmt->execute()->fetchArray();
        
        $html .= "<tr><td><label class='memberlabel'>". $name[0] ."</label></td>";
        $html .= "<td><input class='memberbox' id='". $row['user_id'] ."' type='text' value='0.00' name='". $row['user_id'] ."'></td></tr>";
      }
      
      $html .= "<tr><td><label class='memberlabel'>Total</label></td>";
      $html .= "<td><input type='text' value='0.00' id='total'></td></tr>"; 
      $html .= "</table>
            <button type='button' id='divide'>Divide Total Evenly</button> 
            <br>
    		    <input id='submitbill' type='submit' name='submit' value = 'Submit'>
          </form></div>
        ";
    }else{
      $html .= "
            <br>
            <p>Please Create a group to start creating bills.
            ";
    }
  }else{
    $html .= "
      <br>
      <p>This is a web app designed to help you manage bills. Please log in or register to start.
      ";
  }
  $sendback->name = $html;
  $myJSON = json_encode($sendback);
  echo $myJSON;
?>