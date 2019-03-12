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
    <meta charset="utf-8">
    <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
    <link
      rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
      integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
      crossorigin=""
    />

		<style type="text/css">
			#map
      { /* la carte DOIT avoir une hauteur sinon elle n'apparaît pas */
				height:400px;
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
			var events = <?php echo json_encode($events); ?>;
		</script>
    <script type="text/javascript" src="../js/map.js"></script>
	</body>
</html>
