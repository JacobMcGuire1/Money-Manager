<!DOCTYPE html>
<?php
            session_start();
            include ("database.php");
            $database = new Database();
            $database->__construct();
            
            if ( isset( $_POST['submit'] ) ) { 
                                   
              $name = $_POST['name'];
             
              //$sql = "INSERT INTO groups VALUES(NULL,:name,:user_id);";
              $stmt = $database->prepare("INSERT INTO groups VALUES(NULL,:name,:user_id);");
              
              $stmt->bindValue(':user_id', $_SESSION["id"], SQLITE3_INTEGER);
              $stmt->bindValue(':name', $name, SQLITE3_TEXT);
              $stmt->execute();
               
              $stmt = $database->prepare("SELECT id from groups WHERE creator_id = :userid;");
              $stmt->bindValue(':userid', $_SESSION["id"], SQLITE3_INTEGER);
              $group = $stmt->execute()->fetchArray();
              
              $sql = "INSERT INTO groupage VALUES(NULL,:user_id,:group_id);";
              $stmt = $database->prepare($sql);
              $stmt->bindValue(':user_id', $_SESSION["id"], SQLITE3_INTEGER);
              $stmt->bindValue(':group_id', $group[0], SQLITE3_INTEGER);
              $stmt->execute();
              
              header("Location: index.php");
              die();
            }   
?>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
    <title>Home</title>
  </head>
  <body>
    <div id="all">
      <div id="menu">
        <img src="Logo.png" alt="LOGO">
        <h1 id = "menupagetitle">Home</h1>
        <ul id="menulist">
          <?php include 'menu.php' ?>
          <li class='list'>
            <form action='creategroup.php' method='post'>
    	        <label>Group Name:</label>
        		  <input type="text" name="name">
        		  <br>
        		  <input type='submit' name='submit' value = 'Submit'>
  	        </form>
          </li>
        </ul>
      </div>
      <div id="main">
        <?php include 'bills.php' ?>
      </div>
    </div>
  </body>
</html> 
