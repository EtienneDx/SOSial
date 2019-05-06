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
else if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['role']))
{
  if(strlen($_POST['password']) < 4)
  {
    $error = "Un mot de passe doit contenir au minimum 4 charactères!";
  }
  else if($_POST['password'] != $_POST['confirm_password'])
  {
    $error = "Les mots de passe ne correspondent pas!";
  }
  else if(strlen($_POST['username']) == 0)
  {
    $error = "Un nom d'utilisateur ne peut être vide!";
  }
  else
  {
    $prep = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE name=?");
    $prep->bind_param("s", $_POST['username']);

    $prep->execute();

    $result = $prep->get_result();
    $prep->close();

    if($result->fetch_row()[0] > 0)// user already exists
    {
      $error = "Ce nom est déjà utilisé!";
    }
    else
    {
      $prep = $mysqli->prepare("INSERT INTO users (name, password, role) VALUES (?, ?, ?)");
      $uname = htmlspecialchars($_POST['username']);
      $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $prep->bind_param("ssi", $uname, $pass, $_POST['role']);
      if(!$prep->execute())
      {
        $error = $prep->error;
      }
      else
      {
        $prep->close();

        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];

        // error here??§?

        // we created the account so job's done
        header("Location: index.php");
        die('Redirect');
      }
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
    <div class="container">
      <div class="row">
        <div class="col-12 title">Créer mon compte</div>
      </div>
      <?php if(isset($error)): ?>
        <div class="alert alert-danger">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
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
            <div class="form-check">
              <input class="form-check-input" type="radio" name="role" id="sansAbris" value="0" checked>
              <label class="form-check-label" for="sansAbris">
                Je suis sans-abris
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="role" id="volontaire" value="1">
              <label class="form-check-label" for="volontaire">
                Je suis volontaire
              </label>
            </div>
            <button type="submit" class="btn btn-primary">Créer mon compte</button>
          </form>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
  </body>
</html>
