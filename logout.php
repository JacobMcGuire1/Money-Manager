<?php /* End's the user's session */
      session_start();
      session_destroy();
      header("Location: index.php");
      die();
?>