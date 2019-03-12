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

?>
