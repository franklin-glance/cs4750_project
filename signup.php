<?php
require("connect-db.php");
require("user-db.php");
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['user_id']) && !empty($_POST['password']) && !empty($_POST['confirmPassword']))
  {
    if ($_POST['password'] == $_POST['confirmPassword'])
    {
        echo "<h2>Creating user</h2>";
      createUser($_POST['user_id'], $_POST['password']);
      header("Location: login.php");
    }
    else
    {
      echo "<h2>Passwords do not match</h2>";
    }
  }
}

require("header.php");
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  
  <title>Signup</title>       
</head>
<body>
  <div class="container">
    <h1>Sign Up for the Ultimate Book Collection</h1>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"> 
      user_id: <input type="text" name="user_id" class="form-control" autofocus required /> <br/>
      Password: <input type="password" name="password" class="form-control" required /> <br/>
      Confirm Password: <input type="password" name="confirmPassword" class="form-control" required /> <br/>
      <input type="submit" value="Sign Up" class="btn btn-light" />   
    </form>
  </div>
</body>
</html>
