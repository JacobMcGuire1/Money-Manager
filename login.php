<!DOCTYPE html>
<?php 
          
          if ( isset( $_POST['submit'] ) ) { 
            session_start();
            require ("database.php");
    			  $db = new Database();
    			  $db->__construct();
                                       
            $email = $_POST['email'];
            $password = $_POST['user_password'];
            $encrypted_password = sha1($salt."--".$password);

            //$stmt = $db->prepare("SELECT * FROM users WHEREusername = ':username');");
            //$stmt->bindValue(':username', $username);
            //$row = $stmt->execute();
            
            $row = $db->querySingle("SELECT * FROM users WHERE(email = '$email');");

            if(sha1($row['salt']."--".$password) == $row['passwordhash']) {
              $_SESSION['id'] = $row['id'];
              header('Location:index.php');
            } else {
              header('Location:login.php');
            }
          }   
?>
<html>
  <head>
    <script src='js/jquery-3.3.1.js'></script>
    <script src='js/index.js'></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
    <title>Login</title>
  </head>
  <body>
    <div id="all">
       <div id="menu">
        <img src="Logo.png" alt="LOGO">
        <h1 id = "menupagetitle">Login</h1>
        <ul id="menulist">
          <?php include 'menu.php' ?>
        </ul>
       </div>
       <div id="main">
        <br>
        
       	<form action='login.php' onsubmit='return validateForm()'  method='post' name='loginform'>
	        <label>Email Address:</label>
    		  <input id='loginemail' type="text" name="email">
    		  <br>
    		  <label>Password:</label>
    		  <input id='loginpwd' type="password" name="user_password">
    		  <br>
    		  <br>
    		  <input id='loginsubmit' type='submit' name='submit' value = 'Submit'>
	      </form>
       <div id='errormsg'></div>
       </div>
     </div>
  </body>
</html> 
