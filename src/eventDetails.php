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
if(!isset($_GET['eventId']))
{
  header("Location: eventList.php");
  die('Redirect');
}

$prep = $mysqli->prepare(
  "SELECT
  	 e.*,
      u.name AS author_name,
      (
          SELECT COUNT(*)
          FROM participants
          INNER JOIN users as pa ON pa.id = participants.user_id
          WHERE participants.event_id = e.id AND pa.role = 0
      ) AS participants_count,
      (
          SELECT COUNT(*)
          FROM participants
          INNER JOIN users as pa ON pa.id = participants.user_id
          WHERE participants.event_id = e.id AND pa.role = 1
      ) AS organisers_count,
      (
          SELECT COUNT(1)
          FROM participants p
          WHERE p.user_id = ? AND p.event_id = e.id
      ) AS isParticipant
  FROM events AS e
  INNER JOIN users AS u
    	ON u.id = e.author_id
  WHERE e.id=?");// get event, author, participants and organisers count
$prep->bind_param("ii", $user['id'], $_GET['eventId']);
$prep->execute();

$result = $prep->get_result();
$prep->close();

if($result->num_rows == 0)// event does not exist
{
  header("Location: eventList.php");
  die('Redirect');
}

$event = $result->fetch_assoc();

$prep = $mysqli->prepare(
  "SELECT u.id, u.name
  FROM participants p
  INNER JOIN
  	users u
    ON u.id = p.user_id AND u.role = 1
  WHERE p.event_id=?");// get organisers (we don't display the participants for privacy reasons)
$prep->bind_param("i", $_GET['eventId']);
$prep->execute();

$result = $prep->get_result();
$prep->close();

$organisers = array();

if($result->num_rows >= 0)
{
  while($data = $result->fetch_assoc())
  {
    array_push($organisers, $data);
  }
}

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
    <?php
    if(isset($_error)):
    ?>
    <p>Erreur : <?php echo $error; ?>
    <?php endif; ?>
    <div class="container">
      <div class="row">
        <div class="col-12 title">
          Detail de l'évènement <span class="event-name"><?php echo $event['name']; ?></span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
          <?php echo $event['description']; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
          Cet évènement a été créé par <span class="event-author"><?php echo $event['author_name']; ?></span>.
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
          Il y a actuellement <span class="event-author"><?php echo $event['participants_count']; ?></span> participants.
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
          Il y a actuellement <span class="event-author"><?php echo $event['organisers_count']; ?></span> organisateurs.
        </div>
      </div>
      <div class="row">
        <a class="btn btn-warning col-4 offset-4" href="./eventList.php">
          Retour à la liste
        </a>
      </div>
      <div class="row">
        <a class="btn btn-info col-4 offset-4" href="./map.php?eventId=<?php echo $event['id']; ?>">
          Voir sur la carte
        </a>
      </div>
      <?php if($event['isParticipant'] == 0): ?>
        <div class="row">
          <a class="btn btn-success col-4 offset-4" href="./participate.php?eventId=<?php echo $event['id']; ?>">
            Participer a l'évènement
          </a>
        </div>
      <?php endif; ?>
      <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
          Les organisateurs sont :
          <ul class="list-group">
            <?php foreach ($organisers as $organiser): ?>
              <li class="list-group-item"><?php echo $organiser['name']; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
