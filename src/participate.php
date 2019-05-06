<?php

include '../includes/database.php';
include '../includes/user.php';

if(!isset($_GET['eventId']))
{
  header("Location: eventsList.php");
  die('Redirect');
}

session_start();

$mysqli = init_sql();

$user = get_user($mysqli);

if(!$user["connected"])// redirect unconnected
{
  header("Location: index.php");
  die('Redirect');
}

$prep = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE id=?");
$prep->bind_param("i", $_GET['eventId']);

$prep->execute();

$result = $prep->get_result();
$prep->close();

if($result->fetch_row()[0] == 0)// event does not exist
{
  header("Location: eventsList.php");
  die('Redirect');
}

$prep = $mysqli->prepare("SELECT COUNT(*) FROM participants WHERE event_id=? AND user_id=?");
$prep->bind_param("ii", $_GET['eventId'], $user['id']);

$prep->execute();

$result = $prep->get_result();
$prep->close();

if($result->fetch_row()[0] == 0)// not participant yet
{
  $prep = $mysqli->prepare("INSERT INTO participants (event_id, user_id) VALUES (?, ?)");
  $prep->bind_param("ii", $_GET['eventId'], $user['id']);

  $prep->execute();
  $prep->close();
}

header("Location: eventDetails.php?eventId=" . $_GET['eventId']);
die('Redirect');
