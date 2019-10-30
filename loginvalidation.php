<?php
  
  require ("database.php");
  $database = new Database();
  $database->__construct();
  
  $html = "";
  
  $stmt = $database->prepare("SELECT id from users WHERE email = :email;");
  $stmt->bindValue(':username', $_POST['email'], SQLITE3_TEXT);
  $id = $stmt->execute()->fetchArray();
                             
  $email = $_POST['email'];
  $password = $_POST['user_password'];
  $encrypted_password = sha1($salt."--".$password);

  //$stmt = $db->prepare("SELECT * FROM users WHERE(username = ':username');");
  //$stmt->bindValue(':username', $username);
  //$row = $stmt->execute();
  
  $row = $database->querySingle("SELECT * FROM users WHERE(email = '$email');");

  if(sha1($row['salt']."--".$password) == $row['passwordhash']) {
    session_start();
    $_SESSION['id'] = $row['id'];
    //header('Location:index.php');
  } else {
    if (!isempty($id)){
    //header('Location:login.php');
      $html .= "Wrong email and password";
    }else{
      $html .= "Wrong password";
    }
  }
  $sendback->name = $html;
  $myJSON = json_encode($sendback);
  echo $myJSON;
?>