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

$prep = $mysqli->prepare("SELECT
	e.id,
  e.name,
  e.description,
  e.longitude,
  e.latitude,
  e.date,
  u.Name as author_name,
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
  ) AS organisers_count
FROM events AS e
INNER JOIN users AS u
	ON u.id = e.author_id
WHERE e.date >= CURDATE()");// get all future events
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
    <link rel="stylesheet" href="../css/eventList.css"/>
  </head>
  <body>
    <div class="container">
      <div class="row title-container">
        <div class="col-12 title">Liste des évènements</div>
      </div>
      <div class="row" style="margin-bottom: 5px;">
        <a class="offset-1 col-3 btn btn-danger" href="index.php">Retour</a>
        <a class="offset-4 col-3 btn btn-info" href="map.php">Carte</a>
      </div>
      <ul class="list-group">
        <?php
        foreach ($events as $key => $event):
        ?>
          <li class="list-group-item">
            <div class="row">
              <div class="col-8 container">
                <div class="row">
                  <div class="col-12 event-name">
                    <?php echo $event['name']; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 event-description">
                    <?php echo $event['description']; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    Créé par : <?php echo $event['author_name']; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    Participants : <?php echo $event['participants_count']; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    Organisateurs : <?php echo $event['organisers_count']; ?>
                  </div>
                </div>
              </div>
              <div class="col-3 offset-1">
                <a class="btn btn-info detail-btn" href="./eventDetails.php?eventId=<?php echo $event['id']; ?>">Details</a>
              </div>
            </div>
          </li>
        <?php
        endforeach;
        ?>
      </ul>
    </div>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
  </body>
</html>
