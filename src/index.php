<?php

include '../includes/database.php';
include '../includes/user.php';

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
    <!--A user is connected-->
    <?php
      else:
    ?>
    <!--No connected user-->
    <?php
      endif;
    ?>
    <script src="../js/index.js"></script>
  </body>
</html>
