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
  <meta name="description" content="House of Code HR-Management System" />
  <meta name="keywords" content="House of Code, data, management, HRM, MUS">
  <meta name="author" content="House of Code" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="css/main.css?v=<?php print filemtime("css/main.css"); ?>" />
  <link rel="stylesheet" href="iconfont/material-icons.css">

  <!-- Icon -->

  <!--      -->


</head>
<body>

  <main class="login">
  
	<section class="left">
	  
	  <div class="centerbox">
	  
		<form class="form" action="<?php echo str_replace('.php','',$_SERVER['PHP_SELF']); ?>" method="post">
		  <input tabindex="1" name="identifier" class="top" type="email" placeholder="Email" required /> 
		  <input tabindex="2" name="password" class="bottom" type="password" placeholder="Password" required /> 
		  <input type="checkbox" name="remember" value="cookie">Remember me (cookie)
		  <input tabindex="3" name="login-push" type="submit" value="Login" />
		</form>
	  
	  </div>
	
	</section>
	
	<section class="right">
	
	  <a>#BeAwesome</a>
	  
	  <div class="one"></div>
	  <div class="two"></div>
	  <div class="three"></div>
	  <div class="four"></div>
	
	</section>
	
  </main>

</body>
</html>