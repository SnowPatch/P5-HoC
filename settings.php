<?php  
  
require_once 'php/global.class.php';
$user = new User();

$login = $user->validate();
if($login === FALSE || !is_array($login)) {
  $login = $user->logout();
  header("location: login"); die();
}

$info = $user->fetch($login['uid']);

if($info === FALSE || !is_array($info)) {
  die($info);
}


if(isset($_POST['pass-update-push'])){ 
  
  $result = $user->change_pass($info['id'], $_POST['old'], $_POST['new'], $_POST['repeat']);
  
  if($result === TRUE) {
	$login = $user->logout();
	header("location: login"); die();
  } 
  
}
	
?>
<!doctype html>
<html lang="da">
<head>
  <meta charset="utf-8">

  <title>WebMUS - House of Code</title>
  <meta name="description" content="House of Code HR-Management System" />
  <meta name="keywords" content="House of Code, data, management, HRM, MUS">
  <meta name="author" content="House of Code" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="css/main.css?v=<?php print filemtime("css/main.css"); ?>" />
  <link rel="stylesheet" href="iconfont/material-icons.css">
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" async></script>

  <!-- Icon -->

  <!--      -->

</head>
<body>

<?php if(isset($_POST['pass-update-push'])){ ?>
<div id="errorbox" class="submit-error"> <a><?php echo $result; ?></a> </div>
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

setTimeout(function() { pureFadeOut("errorbox"); }, 5000);
</script>
<?php } ?>
  
  <nav>
	<div class="container">
	  <div class="left">
		<a class="logo" href="index"><img src="images/hoc-icon-color.svg" alt="House of Code" /></a>
	  </div>
	  <div class="right">
		<ul>
		  <li>
		    <button id="dropbtn" class="user" onclick="showDrop();"><?php echo $info['name']; ?></button>
			<div id="navdrop" class="drop">
			  <a href="history">Min historik</a>
			  <a href="settings">Indstillinger</a>
			  <a href="logout">Log ud</a>
			</div>
		  </li>
		</ul>
	  </div>
	</div>
  </nav>
  
  <div class="container">
    <div class="row">
	  <div class="grid-xs-12 breadcrumbs">
	    <a>WebMUS</a>
		<a href="index">Forside</a>
		<a href="settings">Settings</a>
	  </div>
    </div>
  </div>
  
<script>
const dropbtn = document.getElementById('dropbtn');
const navdrop = document.getElementById('navdrop');

window.addEventListener('click', function(e){ 
  if (!navdrop.contains(e.target) && !dropbtn.contains(e.target)){
	navdrop.classList.remove("active");
  }
});

function showDrop() { navdrop.classList.toggle("active"); }
</script>

  <main class="panel">
  
	<div class="container">
	  
	  <section>
	    <div class="row">
		  <div class="grid-xs-12"> <a class="title">Skift_kode();</a> </div>
		  <form class="form" action="<?php echo str_replace('.php','',$_SERVER['PHP_SELF']); ?>" method="post">
		  
		    <div class="grid-xs-6 grid-md-4">
			  <input tabindex="1" name="old" type="password" placeholder="Gamle kode" required /> 
			</div>
			<div class="grid-xs-6 grid-md-4">
			  <input tabindex="2" name="new" type="password" placeholder="Ny kode" required /> 
			</div>
			<div class="grid-xs-6 grid-md-4">
			  <input tabindex="3" name="repeat" type="password" placeholder="Gentag ny kode" required /> 
			</div>

			<div class="grid-xs-12">
			  <input tabindex="4" name="pass-update-push" type="submit" value="Gem ændring" />
			</div>
			
		  </form>
		</div>
	  </section>
	
	</div>
	
  </main>

</body>
</html>
