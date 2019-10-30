<?php
  session_start();
  include ("database.php");
  $database = new Database();
  $database->__construct();
  
  $stmt = $database->prepare("SELECT creator_id from bills WHERE id = :billid;");
  $stmt->bindValue(':billid', $_GET["id"], SQLITE3_INTEGER);
  $billcreator = $stmt->execute()->fetchArray();

  $stmt = $database->prepare("SELECT user_id from owage WHERE bill_id = :billid;");
  $stmt->bindValue(':billid', $_GET["id"], SQLITE3_INTEGER);
  $billtargets = $stmt->execute();

  //get user's group
  $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :user;");
  $stmt->bindValue(':user', $_SESSION["id"], SQLITE3_INTEGER);
  $groupid = $stmt->execute()->fetchArray();

  //Get creator of that group
  $stmt = $database->prepare("SELECT creator_id from groups WHERE id = :groupid;");
  $stmt->bindValue(':groupid', $groupid[0], SQLITE3_INTEGER);
  $groupcreator = $stmt->execute()->fetchArray();

  $isgroupandbillowner = True;
  
  //Checks if the user has permission to delete this bill (either the creator of the bill or the leader of the groupo it belongs to)
  if ($groupcreator[0] == $_SESSION["id"]){
    while(($row = $billtargets->fetchArray())) {
      $stmt = $database->prepare("SELECT * from groupage WHERE group_id = :groupid AND user_id = :userid;");
      $stmt->bindValue(':groupid', $groupid[0], SQLITE3_INTEGER);
      $stmt->bindValue(':userid', $row[0], SQLITE3_INTEGER);
      $ingroup = $stmt->execute()->fetchArray();
      
      if (empty($ingroup)){
        $isgroupandbillowner = False;
      }
    }
    
  }else{ 
    $isgroupandbillowner = False;
  }

  if ($billcreator[0] == $_SESSION['id'] || $isgroupandbillowner){  //i am group creator and all people who owe are members of this group){
    $stmt = $database->prepare("DELETE from bills WHERE id = :billid;");
    $stmt->bindValue(':billid', $_GET['id'], SQLITE3_INTEGER);
    $stmt->execute();
    
    $stmt = $database->prepare("DELETE from owage WHERE bill_id = :billid;");
    $stmt->bindValue(':billid', $_GET['id'], SQLITE3_INTEGER);
    $stmt->execute();
    
    header("Location: index.php");
    die();
  }else{
    echo "This is not your bill to delete.";
    die();
  }
?>