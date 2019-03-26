<?php  
  
if(isset($_POST['login-push'])){ 
  
  require_once 'php/global.class.php';
  
  $cookie = FALSE;
  if(isset($_POST['remember'])) { $cookie = TRUE; }
  
  $obj = new User();
  $result = $obj->login($_POST['identifier'], $_POST['password'], $cookie);
  
  if($result === TRUE) {
	die("Du er logget ind");
  }
  
  echo $result;
  
}
    
?>
<!doctype html>
<html lang="da">
<head>
  <meta charset="utf-8">

  <title>Log ind - Netclear</title>
  <meta name="author" content="Netclear" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>
<body>

  <main>
  
	<form class="form" action="<?php echo str_replace('.php','',$_SERVER['PHP_SELF']); ?>" method="post" style="padding: 100px;">
	  <input tabindex="1" name="identifier" class="top" type="email" placeholder="Email adresse" required /> <br><br>
	  <input tabindex="2" name="password" class="bottom" type="password" placeholder="Kodeord" required /> <br><br>
	  <input type="checkbox" name="remember" value="cookie"> Husk mig (cookie) <br><br>
	  <br>
	  <input tabindex="3" name="login-push" type="submit" value="Log ind" />
	</form>
	
  </main>

</body>
</html>