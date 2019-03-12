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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/main.css"/>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-12 title">SOS'ial</div>
      </div>
      <div class="row">
        <?php
          if($user["connected"]):// alternative syntax, easier inside html
        ?>
        <div class="container">
          <p>Bonjour <?php echo $user['name']; ?></p>
          <a href="disconnect.php" class="btn btn-alert">Me déconnecter</a>
        </div>
        <?php
          else:
        ?>
        <div class="container">
          <div class="row"><a href="createAccount.php" class="btn btn-primary col-sm-4 offset-sm-4">Créer mon compte</a></div>
          <div class="row"><a href="connect.php" class="btn btn-success col-sm-4 offset-sm-4">Me connecter</a></div>
        </div>
        <?php
          endif;
        ?>
      </div>
    </div>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
  </body>
</html>
