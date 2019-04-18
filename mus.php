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
	  
		<div class="top" id="top1">
		  <a id="topText">Del 1 - Højt Drive</a>
		</div>
		
	    <section id="q1">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg arbejder med mit eget fagområde udenfor arbejdstiden</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a1">
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x1" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Når jeg har fri arbejder jeg på mine egne projekter"></textarea>
			</div>
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
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x2" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Jeg læser fagbøger for at udvide min horisont"></textarea>
			</div>
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
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x3" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Best practice deles på Slack/Confluence"></textarea>
			</div>
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
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x4" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Jeg læser bøger, artikler, følger med på LinkedIn"></textarea>
			</div>
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
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x5" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Når jeg er færdig med mine opgaver, spørger jeg om andre har brug for hjælp"></textarea>
			</div>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('6');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('4');" />
			</div>
		  </div>
	    </section>
		
		
		<div class="top" id="top2">
		  <a id="topText">Del 2 - Et Stærkt Team</a>
		</div>
		
		<section id="q6">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg deler min nye tilegnet viden med kolleger og dokumenterer dette</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a6">
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x6" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Jeg opsøger selv pair programming"></textarea>
			</div>
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
			    <option value="" disabled selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x7" placeholder="Uddyb hvis svaret er ja &#10&#10Eksempel: &#10Tager i biografen, walk n’ talk, træner sammen"></textarea>
			</div>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('8');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('6');" />
			</div>
		  </div>
	    </section>
	  
	  </form>
	  
	</div>
	
  </main>
  
  <div class="tooltip-container">
  
    <div class="box"> 
	  <a onclick="letsGo('1');">1</a> 
	  <span class="tooltip">Højt Drive</span> 
	</div>
	<div class="box"> 
	  <a onclick="letsGo('6');">2</a> 
	  <span class="tooltip">Et stærkt Team</span> 
	</div>
	<div class="box"> 
	  <a onclick="letsGo('11');">3</a> 
	  <span class="tooltip">God Karma</span> 
	</div>
	<div class="box"> 
	  <a onclick="letsGo('16');">4</a> 
	  <span class="tooltip">Ha' Det Sjovt</span> 
	</div>
	
  </div>
  
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
  document.getElementById(target).style.opacity = "1";
  if(id != 1) {
	target = "q" + (parseInt(id)-1);
	document.getElementById(target).style.opacity = "0.3";
  }
  if(id != 20) {
	target = "q" + (parseInt(id)+1);
	document.getElementById(target).style.opacity = "0.3";
  }
}
window.onload = function() { document.getElementById("q1").style.opacity = "1"; }
</script>

</body>
</html>
