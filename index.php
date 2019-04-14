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


if(isset($_POST['empl-create-push'])){ 
  
  $result = $user->create($_POST['identifier'], $_POST['name'], $_POST['role']);
  
  if($result === TRUE) {
	$err = FALSE;
  } else {
	$err = TRUE;
  }
  
}


$panel = new Panel();

$employees = $panel->fetch_employees();

if(is_array($employees)) { 

  $output = "";
  foreach($employees as $empl) {
	if($empl["admin"] != 1) { $role = "Medarbejder"; } else { $role = "Admin"; }
	$raw = strtotime($empl["created"]); $formatted = date('d-m-Y', $raw);
	$output .= '
	<div class="grid-xs-12 grid-sm-6 grid-md-4">
	  <div class="employee">
	    <div class="data">
		  <a class="name">'.$empl["name"].'</a>
		  <a class="ext">Oprettet: '.$formatted.'</a>
		  <a class="ext">Konto: '.$role.'</a>
		  <a class="delete" href="delete?id='.$empl["id"].'" onclick="return confirm(\'Er du sikker?\')">Slet konto</a>
		</div>
		<div class="action">
		  <a class="button-primary" href="#id'.$empl["id"].'">Opret MUS</a>
		  <a class="button-secondary" href="#id'.$empl["id"].'">Historik</a>
		</div>
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

<?php if(isset($_POST['empl-create-push']) && $err === TRUE){ ?>
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
		  <form class="form" action="<?php echo str_replace('.php','',$_SERVER['PHP_SELF']); ?>" method="post">
		  
		    <div class="grid-xs-6 grid-md-4">
			  <input tabindex="1" name="identifier" type="email" placeholder="Email" required /> 
			</div>
			<div class="grid-xs-6 grid-md-4">
			  <input tabindex="2" name="name" type="text" placeholder="Fulde navn" required /> 
			</div>
			<div class="grid-xs-6 grid-md-4">
			  <select id="role" name="role" required>
			    <option value="" disabled="" selected="">Konto type</option>
				<option value="0">Medarbejder</option>
				<option value="1">Admin</option>
			  </select>
			</div>
			
			<div class="grid-xs-12">
			  <input tabindex="4" name="empl-create-push" type="submit" value="Opret medarbejder" />
			</div>
			
		  </form>
		</div>
	  </section>
	
	</div>
	<?php } else { ?>
	<a>Ikke admin</a>
	<?php } ?>
	
  </main>

</body>
</html>
