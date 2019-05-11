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

function getEvent($events, $id)
{
  foreach ($events as $event)
  {
    if($event['id'] == $id)
      return $event;
  }
  return null;
}

?>
<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
    <link
      rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
      integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
      crossorigin=""
    />

		<style type="text/css">
			html, body, #map
      { /* la carte DOIT avoir une hauteur sinon elle n'apparaît pas */
				width:100%;
				height:100%;
        margin: 0;
        padding: 0;
			}
		</style>
		<title>Carte</title>
	</head>
	<body>
		<div id="map">
			<!--Map is created here-->
		</div>


    <script
      src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
      integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
      crossorigin=""
    >
    </script>
		<script>
      var lat = <?php echo (isset($_GET['eventId']) && !is_null(getEvent($events, $_GET['eventId']))) ? getEvent($events, $_GET['eventId'])['latitude'] : '48.852969'; ?>;
      var lon = <?php echo (isset($_GET['eventId']) && !is_null(getEvent($events, $_GET['eventId']))) ? getEvent($events, $_GET['eventId'])['longitude'] : '2.349903'; ?>;
			var events = <?php echo json_encode($events); ?>;
		</script>
    <script type="text/javascript" src="../js/map.js"></script>
	</body>
</html>
