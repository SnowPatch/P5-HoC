<?php  
  
if(isset($_POST['login-push'])){ 
  
  require_once 'php/global.class.php';
  $user = new User();
  
  $cookie = FALSE;
  if(isset($_POST['remember'])) { $cookie = TRUE; }
  
  $result = $user->login($_POST['identifier'], $_POST['password'], $cookie);
  
  if($result === TRUE) {
	header("location: index"); die();
  }
  
  echo $result;
  
}
    
?>
<!doctype html>
<html lang="da">
<head>
  <meta charset="utf-8">

  <title>Login - House of Code</title>
  <meta name="author" content="House of Code" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>
<body>

  <main>
  
	<form class="form" action="<?php echo str_replace('.php','',$_SERVER['PHP_SELF']); ?>" method="post" style="padding: 100px;">
	  <input tabindex="1" name="identifier" class="top" type="email" placeholder="Email" required /> <br><br>
	  <input tabindex="2" name="password" class="bottom" type="password" placeholder="Password" required /> <br><br>
	  <input type="checkbox" name="remember" value="cookie">Remember me (cookie) <br><br>
	  <br>
	  <input tabindex="3" name="login-push" type="submit" value="Login" />
	</form>
	
  </main>

</body>
</html>