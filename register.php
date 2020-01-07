<!DOCTYPE html>
<?php /* Registers a new user account */
          if ( isset( $_POST['submit'] ) ) { 
            session_start();
            require ("database.php");
    			  $db = new Database();
    			  $db->__construct();
                                       
            $username = $_POST['username'];
            $password = $_POST['user_password'];
            $email = $_POST['email'];         
            $salt = sha1(time());
            $encrypted_password = sha1($salt."--".$password);
            $sql = "INSERT INTO users VALUES(NULL,:username,:email,:password,:salt);";
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->bindValue(':email', $email, SQLITE3_TEXT);
            $stmt->bindValue(':password', $encrypted_password, SQLITE3_TEXT);
            $stmt->bindValue(':salt', $salt, SQLITE3_TEXT);
            $stmt->execute();
            
            $row = $db->querySingle("SELECT * FROM users WHERE(username = '$username');");
            /*var_dump($row);*/
            $_SESSION['id'] = $row['id'];
            
            header("Location: index.php");
            die();
          }   
?>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
    <title>Register</title>
  </head>
  <body>
    <div id="all">
       <div id="menu">
         <img src="Logo.png" alt="LOGO">
        <h1 id = "menupagetitle">Register</h1>
        <ul id="menulist">
          <?php include 'menu.php' ?>
        </ul>
       </div>
       <div id="main">
        <br>
        
     	<form action='register.php' method='post' name='registerform' >
        <label>Username:</label>
  		  <input type="text" name="username">
  		  <br>
        <label>Email Address:</label>
  		  <input type="text" name="email">
  		  <br>
  		  <label>Password:</label>
  		  <input type="password" name="user_password">
  		  <br>
  		  <br>
  		  <input type='submit' name='submit' value = 'Submit'>
	    </form>
       </div>
     </div>
  </body>
</html> 
