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
			  <a href="#">Historik</a>
			  <a href="#">Indstillinger</a>
			  <a href="#">Log ud</a>
			</div>
		  </li>
		</ul>
	  </div>
	</div>
  </nav>
  
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
  
	<?php if($info['admin'] == 1) { ?>
	<div class="container">
	
	  <section>
	    <div class="row">
		  <div class="grid-xs-12"> <a class="title">Medarbejdere</a> </div>
		  
		  <div class="grid-xs-6 grid-sm-4 grid-sm-3">
		    <div class="employee">
			  <a class="name">Sven Bachmann</a>
			  <a class="role">Admin</a>
			  <a class="button-primary" href="#">Opret MUS</a>
			  <a class="button-secondary" href="#">Alle samtaler</a>
			</div>
		  </div>
		  
		  <div class="grid-xs-6 grid-sm-4 grid-sm-3">
		    <div class="employee">
			  <a class="name">Joachim Hviid</a>
			  <a class="role">Medarbejder</a>
			  <a class="button-primary">Opret MUS</a>
			  <a class="button-secondary">Alle samtaler</a>
			</div>
		  </div>
		  
		  <div class="grid-xs-6 grid-sm-4 grid-sm-3">
		    <div class="employee">
			  <a class="name">Laura Damsgaard</a>
			  <a class="role">Medarbejder</a>
			  <a class="button-primary">Opret MUS</a>
			  <a class="button-secondary">Alle samtaler</a>
			</div>
		  </div>
		  
		</div>
	  </section>
	  
	  <section>
	    <div class="row">
		  <div class="grid-xs-12"> <a class="title">Opret ny</a> </div>
		</div>
	  </section>
	
	</div>
	<?php } else { ?>
	<a>Ikke admin</a>
	<?php } ?>
	
  </main>

</body>
</html>
