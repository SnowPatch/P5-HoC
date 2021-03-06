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

<script>
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
</script>

<?php if(isset($_GET['pass-change'])) { ?>
<div id="successbox" class="submit-success"> <a>Succes! Log ind med den nye kode</a> </div>
<script>
setTimeout(function() { pureFadeOut("successbox"); }, 5000);
</script>
<?php } ?>

<?php if(isset($_POST['login-push'])){ ?>
<div id="errorbox" class="submit-error"> <a><?php echo $result; ?></a> </div>
<script>
setTimeout(function() { pureFadeOut("errorbox"); }, 5000);
</script>
<?php } ?>

  <main class="login">
  
	<section class="left">
	  
	  <div class="centerbox">
	  
	    <div class="logo">
		  <img src="images/hoc-icon-color.svg" alt="House of Code" />
		</div>
		
		<div class="intro">
		  <h2>Velkommen</h2>
		  <h3>Log ind for at fortsætte</h3>
		</div>
	  
		<form class="form" action="login" method="post">
		  <input tabindex="1" name="identifier" class="top" type="email" placeholder="Email" required /> 
		  <input tabindex="2" name="password" class="bottom" type="password" placeholder="Kodeord" required /> 
		  <input id="cookie" type="checkbox" name="remember" value="cookie" checked /> <label for="cookie">Husk mig (cookie)</label>
		  <input tabindex="3" name="login-push" type="submit" value="Log ind" />
		</form>
	  
	  </div>
	
	</section>
	
	<section class="right">
	
	  <a id="box"></a>
	  
	  <div class="one"></div>
	  <div class="two"></div>
	  <div class="three"></div>
	  <div class="four"></div>
	
	</section>
	
  </main>

<script>
var element = document.getElementById('box');
var quotes = ["#BeAwesome", "#BeCreative", "#BeHappy", "#BeHelpful", "#BePositive"];
var quoteNum = 0;
var speed = 60;

var i = 0;
var j = quotes[quoteNum].length;

function loop() {
  
  // TypeWrite effekt ind
  if (i < quotes[quoteNum].length) {
    element.innerHTML += quotes[quoteNum].charAt(i);
	i++;
	setTimeout(loop, speed); 
	return;
  }
  
  // Delay
  if (i == quotes[quoteNum].length) { 
    i++; 
	setTimeout(loop, 2800); 
	return;
  }
  
  // TypeWrite effekt ud
  if (j >= 0) {
    element.innerHTML = element.innerHTML.substring(0, j); 
    j--;
	setTimeout(loop, speed); 
	return;
  }
  
  // Reset
  if (j < 0) {
    quoteNum++;
	if(quoteNum == quotes.length) {
	  quoteNum = 0;
	}
    i = 0;
	j = quotes[quoteNum].length;
	setTimeout(loop, speed); 
	return;
  }
  
}

window.onload = loop();
</script>

</body>

</html>