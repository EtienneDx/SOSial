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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/main.css"/>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-12 title">Créer mon compte</div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-3 col-sm-12">
          <form method="POST" target="_self">
            <div class="form-group">
              <label for="username">Nom d'utilisateur : </label>
              <input type="text" class="form-control" name="username" id="username"></input>
            </div>
            <div class="form-group">
              <label for="password">Mot de passe : </label>
              <input type="password" class="form-control" name="password" id="password"></input>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirmer le mot de passe : </label>
              <input type="password" class="form-control" name="confirm_password" id="confirm_password"></input>
            </div>
            <input type="submit" class="btn btn-primary"></input>
          </form>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
  </body>
</html>
