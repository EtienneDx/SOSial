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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/main.css"/>
  </head>
  <body>
    <?php
    if($error !== null):
    ?>
    <p>Erreur : <?php echo $error; ?>
    <?php endif; ?>
    <div class="container">
      <div class="row">
        <div class="col-12 title">Me connecter</div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-3 col-sm-12">
          <form method="POST" target="_self">
            <div class="form-group">
              <label for="username">Nom d'utilisateur : </label>
              <input type="text" class="form-control" name="username" id="username" placeholder="Username"></input>
            </div>
            <div class="form-group">
              <label for="password">Mot de passe : </label>
              <input type="password" class="form-control" name="password" id="password"></input>
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
