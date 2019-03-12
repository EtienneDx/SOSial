<?php
function get_user($mysqli)
{
  if(!isset($_SESSION['username']) || !isset($_SESSION['password']))
    return array('connected' => false);

  $prep = $mysqli->prepare("SELECT * FROM users WHERE name = ?");
  $prep->bind_param("s", $_SESSION['username']);
  $prep->execute();

  $result = $prep->get_result();
  $prep->close();

  if($result->num_rows === 0)
    return array('connected' => false);

  $user = $result->fetch_assoc();
  if(password_verify($_SESSION['password'], $user['password']))// check if the hash matches
  {
    $user['connected'] = true;
    return $user;
  }
  return array('connected' => false);
}
