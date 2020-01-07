<?php /* Marks the user's contribution to the bill as paid. */
  session_start();
  include ("database.php");
  $database = new Database();
  $database->__construct();
  
  //Checks for permissions then flips the paid boolean.
  
  $stmt = $database->prepare("SELECT user_id from owage WHERE bill_id = :bill_id;");
  $stmt->bindValue(':bill_id', $_GET["id"], SQLITE3_INTEGER);
  $billower = $stmt->execute();
  $isallowed = False;
  
  while(($row = $billower->fetchArray())) {
    if ($row[0] == $_SESSION['id']){
      $isallowed = True;
    }
  }
  
  if ($isallowed){
    $stmt = $database->prepare("SELECT * from owage WHERE user_id = :userid AND bill_id = :billid;");
		$stmt->bindValue(':userid', $_SESSION['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':billid', $_GET['id'], SQLITE3_INTEGER);
		$paid = $stmt->execute()->fetchArray();
    if ($paid['paid']){
      $update = $database->prepare("UPDATE owage SET paid=0 WHERE id=:id;");
    }else{
      $update = $database->prepare("UPDATE owage SET paid=1 WHERE id=:id;");
    }
    $update->bindValue(':id', $paid['id']);
    $update->execute();
    header("Location: index.php");
    die();
  }else{
    echo "This is not your bill. Go away.";
		die();
  }
?>