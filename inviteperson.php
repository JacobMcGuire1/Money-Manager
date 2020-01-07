
<?php /* Invites the specified user to the group, if they exist. */
  session_start();
  include ("database.php");
  $database = new Database();
  $database->__construct();
  
  //Sends an invite
  
  $stmt = $database->prepare("SELECT id from users WHERE username = :username;");
  $stmt->bindValue(':username', $_POST['name'], SQLITE3_TEXT);
  $id = $stmt->execute()->fetchArray();
  
  if (!empty($id)){ 
    $stmt = $database->prepare("SELECT id from groupage WHERE user_id = :userid;");
    $stmt->bindValue(':userid', $id[0], SQLITE3_INTEGER);
    $usergroup = $stmt->execute()->fetchArray();
    
    $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :userid;");
    $stmt->bindValue(':userid', $_SESSION['id'], SQLITE3_INTEGER);
    $sendergroup = $stmt->execute()->fetchArray();
    
    if (empty($usergroup)){
      echo "odwj";
      $sql = "INSERT INTO invites VALUES(NULL,:user_id,:group_id);";
      $stmt = $database->prepare($sql);
      $stmt->bindValue(':user_id', $id[0], SQLITE3_INTEGER);
      $stmt->bindValue(':group_id', $sendergroup[0], SQLITE3_INTEGER);
      $stmt->execute();
    }
  }
  
  header("Location: index.php");
  die();
?>