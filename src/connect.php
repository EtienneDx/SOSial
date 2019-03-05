<?php

include '../includes/database.php';
include '../includes/user.php';

session_start();

$mysqli = init_sql();

$user = get_user($mysqli);

$error = null;

if($user["connected"])// already has an account
{
  header("Location: index.php");
  die('Redirect');
}
else if(isset($_POST['username']) && isset($_POST['password']))
{
  $prep = $mysqli->prepare("SELECT id, name, password FROM users WHERE name = ?");
  $prep->bind_param("s", $_POST['username']);
  $prep->execute();

  $result = $prep->get_result();
  $prep->close();

  if($result->num_rows === 0)
  {
    $error = "Nom d'utilisateur incorrect";
  }
  else
  {
    $user = $result->fetch_assoc();
    if(password_verify($_POST['password'], $user['password']))// check if the hash matches
    {
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['password'] = $_POST['password'];

      header("Location: index.php");
      die('Redirect');
    }
    else
    {
      $error = "Mot de passe incorrect";
    }
  }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>SOS'ial</title>

    <link rel="stylesheet" href="../css/main.css"/>
  </head>
  <body>
    <h1>Me connecter</h1>
    <?php
    if($error !== null):
    ?>
    <p>Erreur : <?php echo $error; ?>
    <?php endif; ?>
    <form method="POST" target="_self">
      <label for="username">Nom d'utilisateur : <input type="text" name="username" id="username"></input></label><br>
      <label for="password">Mot de passe : <input type="password" name="password" id="password"></input></label><br>
      <input type="submit"></input>
    </form>
    <script src="../js/index.js"></script>
  </body>
</html>
