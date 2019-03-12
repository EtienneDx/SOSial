<?php

include '../includes/database.php';
include '../includes/user.php';

session_start();

$mysqli = init_sql();

$user = get_user($mysqli);

if(!$user["connected"])// redirect unconnected
{
  header("Location: index.php");
  die('Redirect');
}

$prep = $mysqli->prepare("CALL select_events()");// use stored procedure to get events
$prep->execute();

$result = $prep->get_result();
$prep->close();

$events = array();

if($result->num_rows >= 0)
{
  while($data = $result->fetch_assoc())
  {
    array_push($events, $data);
  }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>Voir les evenements</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/main.css"/>
  </head>
  <body>
    <div class="container">
      <?php
      foreach ($events as $key => $event):
      ?>
        <div class="row">
          <div class="container">
            <div class="row"><?php echo $event['name']; ?> - <?php echo $event['description']; ?></div>
            <div class="row">
              createur de l'evenement : <?php echo $event['author_name']; ?>,
              actuellement <?php echo $event['participants_count']; ?> participants et
              <?php echo $event['organisers_count'] ?> organisateurs.
            </div>
          </div>
        </div>
      <?php
      endforeach;
      ?>
    </div>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
  </body>
</html>
