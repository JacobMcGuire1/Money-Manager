<?php /* Adds the user to the group the invite is from. */
  session_start();
  include ("database.php");
  $database = new Database();
  $database->__construct();
  
  $stmt = $database->prepare("SELECT group_id from invites WHERE id=:invite_id;");
  $stmt->bindValue(':invite_id', $_GET["id"], SQLITE3_INTEGER);
  $groupid = $stmt->execute()->fetchArray();
  
  $stmt = $database->prepare("SELECT user_id from invites WHERE id=:invite_id;");
  $stmt->bindValue(':invite_id', $_GET["id"], SQLITE3_INTEGER);
  $userid = $stmt->execute()->fetchArray();

  echo $_GET["id"];
  if (!empty($groupid) && $userid[0] == $_SESSION["id"]){
    $sql = "INSERT INTO groupage VALUES(NULL,:user_id,:group_id);";
    $stmt = $database->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION["id"], SQLITE3_INTEGER);
    $stmt->bindValue(':group_id', $groupid[0], SQLITE3_INTEGER);
    $stmt->execute();
    
    $stmt = $database->prepare("DELETE from invites WHERE user_id = :userid;");
    $stmt->bindValue(':userid', $_SESSION["id"], SQLITE3_INTEGER);
    $stmt->execute();
  }
  
  header("Location: index.php");
  die();
  
?>