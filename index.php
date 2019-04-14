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
  header("location: login"); die();
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


if(isset($_POST['mus-invite-push'])){ 
  
  $mus_result = $panel->invite_mus($_POST['mus_id'], $_POST['empl'], $_POST['mus_from']);
  
  if($mus_result === TRUE) {
	header("location: index"); die();
  } 
  
}


if($info['admin'] == 1) {

	$employees = $panel->fetch_employees();

	if(is_array($employees)) { 

	  $output = "";
	  foreach($employees as $item) {
		if($item["admin"] != 1) { $role = "Medarbejder"; } else { $role = "Admin"; }
		$raw = strtotime($item["created"]); $formatted = date('d-m-Y', $raw);
		$output .= '
		<div class="grid-xs-12 grid-sm-6 grid-md-4">
		  <div class="employee">
			<div class="data">
			  <a class="name">'.$item["name"].'</a>
			  <a class="ext">Oprettet: '.$formatted.'</a>
			  <a class="ext">Konto: '.$role.'</a>
			  <a class="delete" href="delete_user?id='.$item["id"].'" onclick="return confirm(\'Er du sikker?\')">Slet konto</a>
			</div>
			<div class="action">
			  <a class="button-primary" href="new?id='.$item["id"].'">Opret MUS</a>
			  <a class="button-secondary" href="history?id='.$item["id"].'">Historik</a>
			</div>
		  </div>
		</div>
		';
	  }
	  
	} else { $output = '<div class="grid-xs-12"><a class="errortext">' . $employees . '</a></div>'; }

} else {

	$history = $panel->fetch_history($info['id'], 1);

	if(is_array($history)) { 

	  $output = "";
	  foreach($history as $item) {
		  
		$action = str_replace('.php','',$_SERVER['PHP_SELF']);
		$raw = strtotime($item["deadline"]); $formatted = date('d-m-Y', $raw);
		
		$invite = "";
		if($item["invites"] > 0) {
		  if(!isset($item["child"]) || ((count($item["child"]) - 1) < $item["invites"])) {
			
			$employees = $panel->fetch_employees();

			if(is_array($employees)) { 

			  $employee_list = "";
			  foreach($employees as $empl) {
				$employee_list .= '<option value="'.$empl['id'].'">'.$empl['name'].'</option>';
			  }
			  
			} else { $employee_list = ""; }
			
			  $invite = '
				<a class="title">Inviter kollega ('.(count($item["child"]) - 1).'/'.$item["invites"].')</a>
				<form action="'.$action.'" method="post">
				  <select tabindex="1" id="empl" name="empl" required>
					<option value="" disabled="" selected="">Kollega</option>
					'.$employee_list.'
				  </select>
				  <input type="hidden" name="mus_id" value="'.$item["id"].'" />
				  <input type="hidden" name="mus_from" value="'.$info['id'].'" />
				  <input tabindex="2" name="mus-invite-push" type="submit" value="Inviter" />
				</form>
			  ';
		  }
		}
		  
		  $ext_type = "employee";
		  if(isset($item["invited_by"])) {
			$invite = '<a class="title">Inviteret af: '.$item["invited_by"].'</a><br>'; 
			$ext_type = "colleague";
		  }
		  
		  $output .= '
		  <div class="grid-xs-12">
			<div class="historik">
			  <div class="row">

				<div class="grid-xs-12 grid-sm-4 grid-md-3 grid-lg-2 data">
				  <a class="id">#'.$item["id"].'</a>
				  <a class="info">Deadline:<br>'.$formatted.'</a>
				  <a class="delete" href="" onclick="return confirm(\'Er du sikker?\')">Slet samtale</a>
				</div>
			
				<div class="grid-xs-12 grid-sm-8 grid-md-9 grid-lg-10 action">
				  '.$invite.'
				  <div class="answers">
					<a class="title">Besvarelser</a>
					<a class="button-primary" href="mus?id='.$item["id"].'&type=employee">Mit svar</a>
				  </div>
				</div>
			
			  </div>
			</div>
		  </div>
		  ';
		
	  }
	  
	} else { $output = '<div class="grid-xs-12"><a class="errortext">' . $history . '</a></div>'; }
	
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

<?php if(isset($_POST['mus-invite-push']) && $err === TRUE){ ?>
<div id="errorbox" class="submit-error"> <a><?php echo $mus_result; ?></a> </div>
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
			  <a href="index">Forside</a>
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
	<div class="container">
	  
	  <section>
	    <div class="row">
		  <div class="grid-xs-12"> <a class="title">Aktive_samtaler();</a> </div>
		  
			<?php echo $output; ?>
	
		</div>
	  </section>
	
	</div>
	<?php } ?>
	
  </main>

</body>
</html>
