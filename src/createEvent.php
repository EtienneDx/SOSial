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

if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['date']))
{
  $prep = $mysqli->prepare("INSERT INTO `events` (`author_id`, `name`, `description`, `latitude`, `longitude`, `date`) VALUES (?, ?, ?, ?, ?, ?)");
  $prep->bind_param("issdds", intval($user['id']), htmlspecialchars($_POST['name']), htmlspecialchars($_POST['description']), doubleval($_POST['latitude']), doubleval($_POST['longitude']), $_POST['date']);

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
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
       integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
       crossorigin=""/>
    <link rel="stylesheet" href="../css/main.css"/>
    <style>
    #latitude, #longitude /*Hidden fields, will get filled by js*/
    {
      display: none;
    }

    #map
    {
      width: 100%;
      height: 200px;
      overflow: hidden;
    }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-12 title">
          Créer un nouvel évènement
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-3 col-sm-12 alert alert-danger" style="display: none;"></div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-3 col-sm-12">
          <form target="_self" method="POST" id="createEvent">
            <div class="form-group">
              <label for="name">Nom : </label>
              <input type="text" name="name" id="name" class="form-control"></input>
            </div>
            <div class="form-group">
              <label for="description">Description : </label>
              <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <input type="hidden" name="longitude" id="longitude" class="form-control"></input>
            <input type="hidden" name="latitude" id="latitude" class="form-control"></input>
            <div id="map">

            </div>
            <div class="form-group">
              <label for="datePicker">Date : </label>
              <input size="16" type="text" value="<?php echo date("Y-m-d H:i", strtotime('+1 day')); ?>" readonly class="form_datetime" id="datePicker" name="date"></input>
            </div>
            <input type="submit" class="btn btn-primary"></input>
          </form>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <script
      src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
      integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
      crossorigin=""
    >
    </script>
    <script src="../js/createEvent.js"></script>
  </body>
</html>
