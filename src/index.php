<?php

include '../includes/database.php';
include '../includes/user.php';

session_start();

$sql = init_sql();

$user = get_user($sql);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>SOS'ial</title>

    <link rel="stylesheet" href="../css/main.css"/>
  </head>
  <body>
    <h1>SOS'ial</h1>
    <?php
      if($user["connected"]):// alternative syntax, easier inside html
    ?>
    <p>Bonjour <?php echo $user['name']; ?></p>
    <a href="disconnect.php">Me déconnecter</a>
    <?php
      else:
    ?>
    <a href="createAccount.php">Créer mon compte</a><br>
    <a href="connect.php">Me connecter</a><br>
    <?php
      endif;
    ?>
    <script src="../js/index.js"></script>
  </body>
</html>
