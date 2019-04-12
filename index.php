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

$panel = new Panel();

$employees = $panel->fetch_employees();

if(is_array($employees)) { 

  $output = "";
  foreach($employees as $empl) {
	if($empl["admin"] != 1) { $role = "Medarbejder"; } else { $role = "Admin"; }
	$output .= '
	<div class="grid-xs-6 grid-sm-4 grid-sm-3">
	  <div class="employee">
		<div class="name"> 
		  <a>'.$empl["name"].'</a> 
		  <a href="delete?id='.$empl["id"].'" onclick="return confirm(\'Er du sikker?\')"><sup>slet</sup></a> 
		</div>
		<a class="role">'.$role.'</a>
		<a class="button-primary" href="#id'.$empl["id"].'">Opret MUS</a>
		<a class="button-secondary" href="#id'.$empl["id"].'">Alle samtaler</a>
	  </div>
	</div>
	';
  }
  
} else { $output = '<div class="grid-xs-12"><a class="errortext">' . $employees . '</a></div>'; }
    
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
			  <a href="history">Min historik</a>
			  <a href="settings">Indstillinger</a>
			  <a href="logout">Log ud</a>
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
		  <div class="grid-xs-12"> <a class="title">Medarbejdere();</a> </div>
		  
		  <?php echo $output; ?>
		  
		</div>
	  </section>
	  
	  <section>
	    <div class="row">
		  <div class="grid-xs-12"> <a class="title">Opret_ny();</a> </div>
		</div>
	  </section>
	
	</div>
	<?php } else { ?>
	<a>Ikke admin</a>
	<?php } ?>
	
  </main>

</body>
</html>
