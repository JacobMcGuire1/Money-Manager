<!DOCTYPE html>
<?php /* The root of the website */
            session_start();
            include ("database.php");
            include ("security.php");
            $database = new Database();
            $database->__construct();
?>
<html>
  <head>
    <script src='js/jquery-3.3.1.js'></script>
    <script src='js/index.js'></script>
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
        </ul>
      </div>
      <div id="main">
        <?php include 'bills.php' ?>
      </div>
    </div>
  </body>
</html> 
