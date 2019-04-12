<?php  
  
require_once 'php/global.class.php';
$user = new User();
  
$login = $user->validate();
if($login !== FALSE && is_array($login)) {
  header("location: index"); die();
}
  
if(isset($_POST['login-push'])){ 
  
  $cookie = FALSE;
  if(isset($_POST['remember'])) { $cookie = TRUE; }
  
  $result = $user->login($_POST['identifier'], $_POST['password'], $cookie);
  
  if($result === TRUE) {
	header("location: index"); die();
  }
  
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

<?php if(isset($_POST['login-push'])){ ?>
<div id="errorbox" class="login-error"> <a><?php echo $result; ?></a> </div>
<?php } ?>

  <main class="login">
  
	<section class="left">
	  
	  <div class="centerbox">
	  
	    <div class="logo">
		  <img src="images/hoc-icon-color.svg" alt="House of Code" />
		</div>
	  
		<form class="form" action="<?php echo str_replace('.php','',$_SERVER['PHP_SELF']); ?>" method="post">
		  <input tabindex="1" name="identifier" class="top" type="email" placeholder="Email" required /> 
		  <input tabindex="2" name="password" class="bottom" type="password" placeholder="Kodeord" required /> 
		  <input id="cookie" type="checkbox" name="remember" value="cookie" checked /> <label for="cookie">Husk mig (cookie)</label>
		  <input tabindex="3" name="login-push" type="submit" value="Log ind" />
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
  
<?php if(isset($_POST['login-push'])){ ?>
<script type="text/javascript">
function pureFadeOut(elem){
	
  var el = document.getElementById(elem);
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .05) < 0) {
      el.style.display = "none";
    } else {
	  setTimeout(function() { fade(); }, 50);
    }
  })();
  
};

setTimeout(function() { pureFadeOut("errorbox"); }, 4000);
</script>
<?php } ?>

</body>
</html>