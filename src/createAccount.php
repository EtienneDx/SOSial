<?php

include '../includes/database.php';
include '../includes/user.php';

session_start();

$mysqli = init_sql();

$user = get_user($mysqli);

if($user["connected"])// already has an account
{
  header("Location: index.php");
  die('Redirect');
}
else if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) &&
  $_POST['password'] == $_POST['confirm_password'])
{
  $prep = $mysqli->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
  $prep->bind_param("ss", $_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT));
  $prep->execute();
  $prep->close();

  $_SESSION['username'] = $_POST['username'];
  $_SESSION['password'] = $_POST['password'];

  // we created the account so job's done
  header("Location: index.php");
  die('Redirect');
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
    <h1>Créer mon compte</h1>
    <form method="POST" target="_self">
      <label for="username">Nom d'utilisateur : <input type="text" name="username" id="username"></input></label><br>
      <label for="password">Mot de passe : <input type="password" name="password" id="password"></input></label><br>
      <label for="confirm_password">
        Confirmer le mot de passe : <input type="password" name="confirm_password" id="confirm_password"></input>
      </label></br>
      <input type="submit"></input>
    </form>
    <script src="../js/index.js"></script>
  </body>
</html>
