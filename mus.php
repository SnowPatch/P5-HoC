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


if(isset($_GET['id']) && isset($_GET['type'])) {
	
  if (!is_numeric($_GET['id'])) { die("Samtalens ID er i et ikke-genkendt format. Prøv igen"); }
  if($_GET['type'] !== "employee" && $_GET['type'] !== "admin" && $_GET['type'] !== "colleague") { die("Samtalens type blev ikke genkendt. Prøv igen"); }
  
  $panel = new Panel();
  
  $mus_data = $panel->fetch_mus($_GET['id'], $_GET['type']);
  
  if($info['admin'] !== 1) {
	
	if($mus_data['type'] != $_GET['type'] || $mus_data['id_to'] != $info['id']) { die("Du har ikke adgang til denne samtale"); }
	
  }
  
} else { 

  die("Samtalens ID og type kunne ikke genkendes. Prøv igen");

}

echo "<pre>"; var_dump($mus_data); echo "</pre>";
	
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

  <main class="mus"> <button onClick="autoSave();">Gem</button>
	
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x1" placeholder="Uddyb gerne. Eksempel: Når jeg har fri arbejder jeg på mine egne projekter"></textarea>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x2" placeholder="Uddyb gerne. Eksempel: Jeg læser fagbøger for at udvide min horisont"></textarea>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x3" placeholder="Uddyb gerne. Eksempel: Best practice deles på Slack/Confluence"></textarea>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x4" placeholder="Uddyb gerne. Eksempel: Jeg læser bøger, artikler, følger med på LinkedIn"></textarea>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x5" placeholder="Uddyb gerne. Eksempel: Når jeg er færdig med mine opgaver, spørger jeg om andre har brug for hjælp"></textarea>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x6" placeholder="Uddyb gerne. Eksempel: Jeg opsøger selv pair programming"></textarea>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x7" placeholder="Uddyb gerne. Eksempel: Tager i biografen, walk n’ talk, træner sammen"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x8" placeholder="Uddyb gerne. Eksempel: Inden jeg afleverer en opgave, får jeg altid en kollegas øjne til at se på det"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x9" placeholder="Uddyb gerne. Eksempel: Tak fordi du altid tager i mod opgaver med et smil (Siges til en kollega)"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x10" placeholder="Uddyb gerne. Eksempel: Jeg omtaler aldrig personer eller virksomheden negativt i eksterne relationer"></textarea>
			</div>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('11');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('9');" />
			</div>
		  </div>
	    </section>
		
		<div class="top" id="top3">
		  <a id="topText">Del 3 - God Karma</a>
		</div>
		
		<section id="q11">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg opsøger miner kolleger for at hjælpe dem</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a11">
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x11" placeholder="Uddyb gerne. Eksempel: Jeg spørger mine kollegaer, når jeg kan se de er gået død i en opgave, om jeg skal hjælpe"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x12" placeholder="Uddyb gerne. Eksempel: Siger godmorgen og farvel"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x13" placeholder="Uddyb gerne. Eksempel: Arbejder frivilligt - fx Røde Kors, Coding Pirates"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x14" placeholder="Uddyb gerne. Eksempel: Jeg tilpasser min kommunikation alt efter hvem jeg snakker med til trods for forskelligheder af meninger"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x15" placeholder="Uddyb gerne. Eksempel: Jeg modtager feedback uden at sige andet end TAK"></textarea>
			</div>
			<div class="grid-xs-12">
			  <input type="button" class="button-primary" value="Videre" onclick="letsGo('16');" />
			  <input type="button" class="button-secondary" value="Tilbage" onclick="letsGo('14');" />
			</div>
		  </div>
	    </section>
		
		<div class="top" id="top4">
		  <a id="topText">Del 4 - Ha' Det Sjovt</a>
		</div>
		
		<section id="q16">
	      <div class="row">
		    <div class="grid-xs-12"> <a class="title">Jeg skaber eller oplever glæde når jeg er på arbejde</a> </div>
			<div class="grid-xs-6 grid-md-4">
			  <select name="a16">
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x16" placeholder="Uddyb gerne. Eksempel: Jeg spørger en kollega om han/hun vil spille pool eller lignende"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x17" placeholder="Uddyb gerne. Eksempel: Badminton, personaleevents, “legerum”"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x18" placeholder="Uddyb gerne. Eksempel: Aktivitetsudvalg, foreslår noget vi lave sammen"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x19" placeholder="Uddyb gerne. Eksempel: Holder mig inden for deadlines"></textarea>
			</div>
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
			    <option value="x" selected>Vælg svar</option>
				<option value="1">Ja</option>
				<option value="0">Nej</option>
			  </select>
			</div>
			<div class="grid-xs-12">
			  <textarea name="x20" placeholder="Uddyb gerne"></textarea>
			</div>
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
function autoSave(){
  alert($('#musForm').serialize());
  $.ajax({
	type: 'POST',
	url: "php/save.php",
	data: $('#musForm').serialize(),
	success: function(data) {
	  alert(data);
	}
  });
}
</script>

</body>
</html>
