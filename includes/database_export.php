<?php

function init_sql()
{
  $servername = "*****";
  $username = "****";
  $password = "";
  $dbname = "****";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if($conn->connect_error)
  {
    die("Connection to dbb failed: " . $conn->connect_error);
  }
  return $conn;
}

?>
