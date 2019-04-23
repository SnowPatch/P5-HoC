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


if(!isset($_GET["id"]) || !isset($_GET["type"])) { die("fejl"); }

if (!is_numeric($_GET['id'])) { die("Samtalens ID er i et ikke-genkendt format. Prøv igen"); }
if($_GET['type'] !== "employee" && $_GET['type'] !== "admin" && $_GET['type'] !== "colleague") { die("Samtalens type blev ikke genkendt. Prøv igen"); }
  

$panel = new Panel();
  
$mus_data = $panel->fetch_mus($_GET['id'], $_GET['type']);
if(!is_array($mus_data)) { die($mus_data); }
  
if($info['admin'] !== 1) {
	
  if($mus_data['type'] != $_GET['type'] || $mus_data['id_to'] != $info['id']) { die("Du har ikke adgang til denne samtale"); }
	
}

if(strlen($mus_data['answer']) > 3) {
  $answers = json_decode($mus_data['answer'], true);
} else {
  $answers = array();
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
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- Icon -->

  <!--      -->

</head>
<body>

<?php if(isset($_POST['mus-create-push'])){ ?>
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
		<a href="">MUS</a>
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

  <main class="mus"> 
	
	<div class="container">
	  
	  <form id="musForm">
		
		<input type="hidden" name="mid" value="<?php echo $_GET['id']; ?>" />
		<input type="hidden" name="mtype" value="<?php echo $_GET['type']; ?>" />
		
		<div class="top" id="top1">
		  <a id="topText">1.1 - Højt Drive</a>
		</div>
		
	    <section id="q1">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg arbejder med mit eget fagområde udenfor arbejdstiden</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a1">
			    <?php if(isset($answers["a1"])) { ?>
			    <option value="x" <?php if($answers["a1"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a1"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a1"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x1" placeholder="Uddyb gerne. Eksempel: Når jeg har fri arbejder jeg på mine egne projekter"><?php echo $answers["x1"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n1" placeholder="Administrator noter"><?php echo $answers["n1"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('2');" />
			</div>
		  </div>
	    </section>
		
		<section id="q2">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg opsøger ny viden inden for mit eget og andres fagområder i og uden for arbejdstiden</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a2">
			     <?php if(isset($answers["a2"])) { ?>
			    <option value="x" <?php if($answers["a2"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a2"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a2"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x2" placeholder="Uddyb gerne. Eksempel: Jeg læser fagbøger for at udvide min horisont"><?php echo $answers["x2"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n2" placeholder="Administrator noter"><?php echo $answers["n2"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('3');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('1');" />
			</div>
		  </div>
	    </section>
		
		<section id="q3">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg deler min nye tilegnet viden med kolleger og dokumenterer dette</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a3">
			    <?php if(isset($answers["a3"])) { ?>
			    <option value="x" <?php if($answers["a3"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a3"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a3"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x3" placeholder="Uddyb gerne. Eksempel: Best practice deles på Slack/Confluence"><?php echo $answers["x3"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n3" placeholder="Administrator noter"><?php echo $answers["n3"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('4');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('2');" />
			</div>
		  </div>
	    </section>
		
		<section id="q4">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg holder mig opdateret omkring apps, design og arbejdsmetoder</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a4">
			    <?php if(isset($answers["a4"])) { ?>
			    <option value="x" <?php if($answers["a4"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a4"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a4"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x4" placeholder="Uddyb gerne. Eksempel: Jeg læser bøger, artikler, følger med på LinkedIn"><?php echo $answers["x4"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n4" placeholder="Administrator noter"><?php echo $answers["n4"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('5');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('3');" />
			</div>
		  </div>
	    </section>
		
		<section id="q5">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg opsøger selv nye opgaver der skaber værdi for House of Code eller giver input til eksisterende opgaver der skaber værdi for House of Code eller vores samarbejdspartnere</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a5">
			    <?php if(isset($answers["a5"])) { ?>
			    <option value="x" <?php if($answers["a5"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a5"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a5"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x5" placeholder="Uddyb gerne. Eksempel: Når jeg er færdig med mine opgaver, spørger jeg om andre har brug for hjælp"><?php echo $answers["x5"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n5" placeholder="Administrator noter"><?php echo $answers["n5"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('6');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('4');" />
			</div>
		  </div>
	    </section>
		
		
		<div class="top" id="top2">
		  <a id="topText">1.2 - Et Stærkt Team</a>
		</div>
		
		<section id="q6">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg deler min nye tilegnet viden med kolleger og dokumenterer dette</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a6">
			    <?php if(isset($answers["a6"])) { ?>
			    <option value="x" <?php if($answers["a6"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a6"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a6"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x6" placeholder="Uddyb gerne. Eksempel: Jeg opsøger selv pair programming"><?php echo $answers["x6"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n6" placeholder="Administrator noter"><?php echo $answers["n6"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('7');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('5');" />
			</div>
		  </div>
	    </section>
		
		<section id="q7">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg omgås med mine kolleger i og uden for arbejdstiden</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a7">
			    <?php if(isset($answers["a7"])) { ?>
			    <option value="x" <?php if($answers["a7"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a7"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a7"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x7" placeholder="Uddyb gerne. Eksempel: Tager i biografen, walk n’ talk, træner sammen"><?php echo $answers["x7"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n7" placeholder="Administrator noter"><?php echo $answers["n7"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('8');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('6');" />
			</div>
		  </div>
	    </section>
		
		<section id="q8">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg søger input på mine opgaver fra mine kolleger</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a8">
			    <?php if(isset($answers["a8"])) { ?>
			    <option value="x" <?php if($answers["a8"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a8"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a8"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x8" placeholder="Uddyb gerne. Eksempel: Inden jeg afleverer en opgave, får jeg altid en kollegas øjne til at se på det"><?php echo $answers["x8"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n8" placeholder="Administrator noter"><?php echo $answers["n8"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('9');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('7');" />
			</div>
		  </div>
	    </section>
		
		<section id="q9">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg giver feedback til mine kolleger på deres adfærd. (konstruktiv såvel som positiv)</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a9">
			    <?php if(isset($answers["a9"])) { ?>
			    <option value="x" <?php if($answers["a9"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a9"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a9"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x9" placeholder="Uddyb gerne. Eksempel: Tak fordi du altid tager i mod opgaver med et smil (Siges til en kollega)"><?php echo $answers["x9"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n9" placeholder="Administrator noter"><?php echo $answers["n9"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('10');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('8');" />
			</div>
		  </div>
	    </section>
		
		<section id="q10">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg går også op i at være en god ambassadør for House of Code overfor de eksterne relationer</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a10">
			    <?php if(isset($answers["a10"])) { ?>
			    <option value="x" <?php if($answers["a10"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a10"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a10"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x10" placeholder="Uddyb gerne. Eksempel: Jeg omtaler aldrig personer eller virksomheden negativt i eksterne relationer"><?php echo $answers["x10"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n10" placeholder="Administrator noter"><?php echo $answers["n10"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('11');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('9');" />
			</div>
		  </div>
	    </section>
		
		<div class="top" id="top3">
		  <a id="topText">1.3 - God Karma</a>
		</div>
		
		<section id="q11">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg opsøger miner kolleger for at hjælpe dem</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a11">
			    <?php if(isset($answers["a11"])) { ?>
			    <option value="x" <?php if($answers["a11"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a11"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a11"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x11" placeholder="Uddyb gerne. Eksempel: Jeg spørger mine kollegaer, når jeg kan se de er gået død i en opgave, om jeg skal hjælpe"><?php echo $answers["x11"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n11" placeholder="Administrator noter"><?php echo $answers["n11"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('12');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('10');" />
			</div>
		  </div>
	    </section>
		
		<section id="q12">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg bidrager med en god og positiv stemning hos House of Code</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a12">
			    <?php if(isset($answers["a12"])) { ?>
			    <option value="x" <?php if($answers["a12"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a12"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a12"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x12" placeholder="Uddyb gerne. Eksempel: Siger godmorgen og farvel"><?php echo $answers["x12"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n12" placeholder="Administrator noter"><?php echo $answers["n12"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('13');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('11');" />
			</div>
		  </div>
	    </section>
		
		<section id="q13">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg gør noget godt for andre uden for House of Code</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a13">
			    <?php if(isset($answers["a13"])) { ?>
			    <option value="x" <?php if($answers["a13"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a13"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a13"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x13" placeholder="Uddyb gerne. Eksempel: Arbejder frivilligt - fx Røde Kors, Coding Pirates"><?php echo $answers["x13"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n13" placeholder="Administrator noter"><?php echo $answers["n13"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('14');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('12');" />
			</div>
		  </div>
	    </section>
		
		<section id="q14">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg møder mine kolleger individuelt for at opnå bedst muligt fællesskab</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a14">
			    <?php if(isset($answers["a14"])) { ?>
			    <option value="x" <?php if($answers["a14"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a14"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a14"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x14" placeholder="Uddyb gerne. Eksempel: Jeg tilpasser min kommunikation alt efter hvem jeg snakker med til trods for forskelligheder af meninger"><?php echo $answers["x14"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n14" placeholder="Administrator noter"><?php echo $answers["n14"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('15');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('13');" />
			</div>
		  </div>
	    </section>
		
		<section id="q15">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg gør en indsats i at arbejde med mig selv</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a15">
			    <?php if(isset($answers["a15"])) { ?>
			    <option value="x" <?php if($answers["a15"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a15"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a15"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x15" placeholder="Uddyb gerne. Eksempel: Jeg modtager feedback uden at sige andet end TAK"><?php echo $answers["x15"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n15" placeholder="Administrator noter"><?php echo $answers["n15"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('16');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('14');" />
			</div>
		  </div>
	    </section>
		
		<div class="top" id="top4">
		  <a id="topText">1.4 - Ha' Det Sjovt</a>
		</div>
		
		<section id="q16">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg skaber eller oplever glæde når jeg er på arbejde</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a16">
			    <?php if(isset($answers["a16"])) { ?>
			    <option value="x" <?php if($answers["a16"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a16"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a16"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x16" placeholder="Uddyb gerne. Eksempel: Jeg spørger en kollega om han/hun vil spille pool eller lignende"><?php echo $answers["x16"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n16" placeholder="Administrator noter"><?php echo $answers["n16"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('17');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('15');" />
			</div>
		  </div>
	    </section>
		
		<section id="q17">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg benytter mig af de muligheder, der stilles til rådighed af House of Code</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a17">
			    <?php if(isset($answers["a17"])) { ?>
			    <option value="x" <?php if($answers["a17"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a17"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a17"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x17" placeholder="Uddyb gerne. Eksempel: Badminton, personaleevents, “legerum”"><?php echo $answers["x17"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n17" placeholder="Administrator noter"><?php echo $answers["n17"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('18');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('16');" />
			</div>
		  </div>
	    </section>
		
		<section id="q18">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg skaber nye muligheder for at vi kan have det sjovt sammen</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a18">
			    <?php if(isset($answers["a18"])) { ?>
			    <option value="x" <?php if($answers["a18"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a18"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a18"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x18" placeholder="Uddyb gerne. Eksempel: Aktivitetsudvalg, foreslår noget vi lave sammen"><?php echo $answers["x18"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n18" placeholder="Administrator noter"><?php echo $answers["n18"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('19');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('17');" />
			</div>
		  </div>
	    </section>
		
		<section id="q19">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg når altid mine opgaver for ugen</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a19">
			    <?php if(isset($answers["a19"])) { ?>
			    <option value="x" <?php if($answers["a19"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a19"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a19"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x19" placeholder="Uddyb gerne. Eksempel: Holder mig inden for deadlines"><?php echo $answers["x19"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n19" placeholder="Administrator noter"><?php echo $answers["n19"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('20');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('18');" />
			</div>
		  </div>
	    </section>
		
		<section id="q20">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg bliver på arbejde til mine opgaver for dagen er udført</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a20">
			    <?php if(isset($answers["a20"])) { ?>
			    <option value="x" <?php if($answers["a20"] == "x") { echo "selected"; } ?>>Vælg svar</option>
				<option value="1" <?php if($answers["a20"] == "1") { echo "selected"; } ?>>Ja</option>
				<option value="0" <?php if($answers["a20"] == "0") { echo "selected"; } ?>>Nej</option>
				<?php } else { ?>
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
				<?php } ?>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x20" placeholder="Uddyb gerne"><?php echo $answers["x20"] ?? ''; ?></textarea>
			</div>
			<?php if($info['admin'] === 1) { ?>
			<div class="grid-xs-12"> <textarea name="n20" placeholder="Administrator noter"><?php echo $answers["n20"] ?? ''; ?></textarea> </div>
			<?php } ?>
			<div class="grid-xs-12">
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('19');" />
			</div>
		  </div>
	    </section>
	  
	  </form>
	  
	</div>
	
  </main>
  
  <div class="tooltip-container">
	<div class="box"> 
	  <a onclick="letsGo('1');">1.1</a> 
	  <span class="tooltip">Højt Drive</span> 
	</div>
	<div class="box"> 
	  <a onclick="letsGo('6');">1.2</a> 
	  <span class="tooltip">Et stærkt Team</span> 
	</div>
	<div class="box"> 
	  <a onclick="letsGo('11');">1.3</a> 
	  <span class="tooltip">God Karma</span> 
	</div>
	<div class="box"> 
	  <a onclick="letsGo('16');">1.4</a> 
	  <span class="tooltip">Ha' Det Sjovt</span> 
	</div>
  </div>
  
  <div class="save-container"> <a id="saveText">Data er gemt</a> </div>
  
<script>
window.addEventListener("load", function () {
  
  var targets = document.getElementsByClassName("box");
  var i;
  var tooltip;

  for (i = 0; i < targets.length; i++) {
  
    targets[i].addEventListener("mouseover", function () {
      tooltip = this.getElementsByClassName("tooltip")[0];
      tooltip.style.display = "inline-block";
    });
		
    targets[i].addEventListener("mouseout", function () {
      tooltip = this.getElementsByClassName("tooltip")[0];
      tooltip.style.display = "none";
    });
		
    }
	
});
</script>

<script>
function letsGo(id) {
	
  var target = "q" + id;
  document.getElementById(target).scrollIntoView({
    behavior: "smooth"
  });
  
  var step;
  var stepTarget;
  for (step = 0; step < 20; step++) {
	stepTarget = "q" + (parseInt(step)+1);
	document.getElementById(stepTarget).style.opacity = "0.3";
  }
  
  document.getElementById(target).style.opacity = "1";
  
}

window.onload = function() { document.getElementById("q1").style.opacity = "1"; }
</script>

<script>
$("#musForm input, #musForm textarea, #musForm select").on('change keyup paste', function() {
  document.getElementById("saveText").innerHTML = "Gemmer...";
});
</script>

<script>
var oldSave;
function autoSave(){
  if(oldSave != $('#musForm').serialize()) {
    $.ajax({
	  type: 'POST',
	  url: "php/save.php",
	  data: $('#musForm').serialize(),
	  success: function(data) {
	    if(data == "modtaget") {
		  document.getElementById("saveText").innerHTML = "Data er gemt";
	    }
	  }
    });
  }
  oldsave = $('#musForm').serialize();
}

setInterval(function(){ 
  autoSave(); 
}, 10000);
</script>

</body>
</html>
