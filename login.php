<?php 
require("connect-db.php");    
require("user-db.php");
session_start();
require("header.php"); 
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['user_id']) && !empty($_POST['password']))
  {
    $user = getUserByUserId($_POST['user_id']);
    if ($user && password_verify($_POST['password'], $user['password']))
    {
      session_start();
      $_SESSION['user_id'] = $user['user_id'];
      header("Location: index.php");
    }
    else
    {
      echo "<h2>Invalid user_id or password</h2>";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  
  <title>Login</title>       
</head>
<body>
  <div class="container">
    <h1>Login to the Ultimate Book Collection</h1>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"> 
      user_id: <input type="text" name="user_id" class="form-control" autofocus required /> <br/>
      Password: <input type="password" name="password" class="form-control" required /> <br/>
      <input type="submit" value="Sign in" class="btn btn-light" />   
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p> 
  </div>
</body>
</html>


<?php include('footer.html') ?> 