<?php
  session_start();
  echo "<script src='js/jquery-3.3.1.min.js'></script>";
  include ("security.php");
  if (isset($_SESSION['id'])) { 
      //if logged in, displays the links and group information/invites.
      $stmt = $database->prepare("SELECT username from users WHERE id = :userid;");
      $stmt->bindValue(':userid', $_SESSION["id"], SQLITE3_INTEGER);
      $name = $stmt->execute()->fetchArray();

      echo "<li class='list'>Logged in as: "; h($name[0]); echo "</li>";
      echo "<li class='list'><a href='index.php'>Home</a></li>";
      echo "<li class='list'><a href='logout.php'>Log Out</a></li>";
      

      $stmt = $database->prepare("SELECT group_id from groupage WHERE user_id = :userid2;");
      $stmt->bindValue(':userid2', $_SESSION["id"], SQLITE3_INTEGER);
      $group = $stmt->execute()->fetchArray();

      if (empty($group)) {
        echo "<li class='list'>You are not in a group.</li>";
        echo "<li class='list'><a href='creategroup.php'>Create a Group</a></li>";
        
        $stmt = $database->prepare("SELECT * from invites WHERE user_id = :userid;");
        $stmt->bindValue(':userid', $_SESSION["id"], SQLITE3_INTEGER);
        $invites = $stmt->execute();
        
        echo "<li class='list'>";echo "Invites:"; echo "</li>";
        while ($row = $invites->fetchArray()){
          $stmt = $database->prepare("SELECT name from groups WHERE id = :groupid;");
          $stmt->bindValue(':groupid', $row["group_id"], SQLITE3_INTEGER);
          $groupname = $stmt->execute()->fetchArray();
          echo "<li class='list'>";echo $groupname[0]; echo "<a href='acceptinvite.php?id="; echo $row['id']; echo "'> Accept</a>"; echo "</li>";
        }
      }else{
        $stmt = $database->prepare("SELECT name from groups WHERE id = :groupid2;");
        $stmt->bindValue(':groupid2', $group[0], SQLITE3_INTEGER);
        $name = $stmt->execute()->fetchArray();
        
        echo "<li class='list'>Your Group: ". $name[0] ."</li>";
        
        $stmt = $database->prepare("SELECT * from groupage WHERE group_id = :groupid;");
        $stmt->bindValue(':groupid', $group[0], SQLITE3_INTEGER);
        $members = $stmt->execute();
        
        $stmt = $database->prepare("SELECT creator_id from groups WHERE id = :groupid2;");
        $stmt->bindValue(':groupid2', $group[0], SQLITE3_INTEGER);
        $creator = $stmt->execute()->fetchArray();
        
        while(($row = $members->fetchArray())) { 
          
          $stmt = $database->prepare("SELECT username from users WHERE id = :userid;");
          $stmt->bindValue(':userid', $row['user_id'], SQLITE3_INTEGER);
          $name = $stmt->execute()->fetchArray();
          if ($row['user_id'] == $creator[0]){
            $leader = "<br>(Leader)";
          }else{
            $leader = "";
          }
          echo "<li class='list'>";h($name[0]); echo $leader; echo "</li>";
        }
        if ($_SESSION["id"] == $creator[0]){
          echo "<li class='list'>
                  <form action='inviteperson.php' method='post'>
         	          <label>Invite:</label>
                    <br>
              		  <input type='text' name='name'>
              		  <br>
              		  <input type='submit' name='submit' value = 'Submit'>
 	                </form>
                </li>";
          
        }
        echo "<li class='list'><a href='leavegroup.php'>Leave Group</a></li>";
      
      }

  }else{
      echo "<li class='list'>You are not logged in</li>";
      echo "<li class='list'><a href='index.php'>Home</a></li>
            <li class='list'><a href='login.php'>Log in</a></li>
            <li class='list'><a href='register.php'>Register</a></li>";
  }
?>

