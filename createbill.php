
<?php
  session_start();
  include ("security.php");
  include ("database.php");
  $database = new Database();
  $database->__construct();
  //Creates a bill for your group
  $stmt = $database->prepare("INSERT INTO bills VALUES(NULL,:name,:creator_id);");        
  $stmt->bindValue(':creator_id', $_SESSION["id"], SQLITE3_INTEGER);
  $stmt->bindValue(':name', $_POST['name'], SQLITE3_TEXT);
  $stmt->execute();
  
  $stmt = $database->prepare("SELECT last_insert_rowid() from bills;");
  $stmt->bindValue(':userid2', $_SESSION["id"], SQLITE3_INTEGER);
  $billid = $stmt->execute()->fetchArray();
  
  $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :userid2;");
  $stmt->bindValue(':userid2', $_SESSION["id"], SQLITE3_INTEGER);
  $group = $stmt->execute()->fetchArray();
  
  $stmt = $database->prepare("SELECT * from groupage WHERE group_id = :groupid;");
  $stmt->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
  $members = $stmt->execute();

  while(($row = $members->fetchArray())) {
    /*echo "Bill id: ". $billid[0];
    echo ", user id: ";
    echo $row['user_id'];
    echo ", cost: ";
    echo $_POST[$row['user_id']];*/
    $stmt = $database->prepare("INSERT INTO owage VALUES(NULL,:bill_id,:user_id,:cost,0);");         
    $stmt->bindValue(':bill_id', $billid[0], SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $row['user_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':cost', $_POST[$row['user_id']] * 100, SQLITE3_INTEGER);
    $stmt->execute();
    
    //mail($row['email'], "New bill added: ". $_POST['name'], "This will cost: £". $_POST[$row['user_id']] * 100);
  }
  //echo "row[8]: ". $_POST[$row[8]];
  //echo "row[9]: ". $_POST[$row[9]];
  header("Location: index.php");
  die();
?>