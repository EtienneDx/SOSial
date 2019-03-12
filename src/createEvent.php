<?php

include '../includes/database.php';
include '../includes/user.php';

session_start();

$mysqli = init_sql();

$user = get_user($mysqli);

if(!$user["connected"] || $user['role'] !== 1)// redirect unconnected
{
  header("Location: index.php");
  die('Redirect');
}

if(isset($_GET['name']) && isset($_GET['description']) && isset($_GET['latitude']) && isset($_GET['longitude']))
{
  $prep = $mysqli->prepare("INSERT INTO `events` (`author_id`, `name`, `description`, `latitude`, `longitude`) VALUES (?, ?, ?, ?, ?)");
  $prep->bind_param("issdd", intval($user['id']), $_GET['name'], $_GET['description'], intval($_GET['latitude']), intval($_GET['longitude']));

  $prep->execute();

  $id = $prep->insert_id;
  $prep->close();

  $prep = $mysqli->prepare("INSERT INTO `participants` (`event_id`, `user_id`) VALUES (?, ?)");
  $prep->bind_param("ii", $id, $user['id']);

  $prep->execute();

  header("Location: eventList.php");
  die('Redirect');
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
    <form target="_self" method="GET">
      <label for="name">Nom : <input type="text" name="name" id="name"></input></label><br>
      <label for="description">Description : <textarea name="description" id="description"></textarea></label><br>
      <label for="longitude">Longitude : <input type="number" name="longitude" id="longitude"></input></label><br>
      <label for="latitude">Latitude : <input type="number" name="latitude" id="latitude"></input></label><br>
      <input type="submit"></input>
    </form>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
  </body>
</html>
