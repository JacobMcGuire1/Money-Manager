<?php
  session_start();
  include ("database.php");
  $database = new Database();
  $database->__construct();
  
  //Leaves the group and either passes on leadership to another member or deletes the group if there was only 1 person in it.
  
  $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :userid;");
  $stmt->bindValue(':userid', $_SESSION["id"], SQLITE3_INTEGER);
  $group = $stmt->execute()->fetchArray();

  $stmt = $database->prepare("SELECT creator_id from groups WHERE id = :groupid2;");
  $stmt->bindValue(':groupid2', $group[0], SQLITE3_INTEGER);
  $creator = $stmt->execute()->fetchArray();
  if ($creator[0] == $_SESSION["id"]){
    $stmt = $database->prepare("SELECT * from groupage WHERE group_id = :groupid;");
    $stmt->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
    $members = $stmt->execute();
    $membercount = 0;
    while(($row = $members->fetchArray())) {
      if ($row['user_id'] != $_SESSION["id"]){
        $update = $database->prepare("UPDATE groups SET creator_id=:userid WHERE id=:groupid;");
        $update->bindValue(':userid', $row['user_id'], SQLITE3_INTEGER);
        $update->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
			  $update->execute();
      }
      $membercount++;
    }
    if ($membercount == 1){
      $stmt = $database->prepare("DELETE from groups WHERE id=:groupid;");
      $stmt->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
      $stmt->execute();
      
      $stmt = $database->prepare("DELETE from invites WHERE group_id=:groupid;");
      $stmt->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
      $stmt->execute();
    }
  }
  $stmt = $database->prepare("DELETE from groupage WHERE user_id = :userid;");
  $stmt->bindValue(':userid', $_SESSION["id"], SQLITE3_INTEGER);
  $stmt->execute();
  
  header("Location: index.php");
  die();

?>