<?php  
  
require_once 'global.class.php';
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


if(!isset($_POST["mid"]) || !isset($_POST["mtype"])) { die("fejl"); }

if (!is_numeric($_POST['mid'])) { die("Samtalens ID er i et ikke-genkendt format. Prøv igen"); }
if($_POST['mtype'] !== "employee" && $_POST['mtype'] !== "admin" && $_POST['mtype'] !== "colleague") { die("Samtalens type blev ikke genkendt. Prøv igen"); }
  

$panel = new Panel();
  
$mus_data = $panel->fetch_mus($_POST['mid'], $_POST['mtype']);
if(!is_array($mus_data)) { die($mus_data); }
  
if($info['admin'] !== 1) {
	
  if($mus_data['type'] != $_POST['mtype'] || $mus_data['id_to'] != $info['id']) { die("Du har ikke adgang til denne samtale"); }
	
}

  $answers = array(
  
	"a1" => $_POST["a1"] ?? '',
	"x1" => $_POST["x1"] ?? '',
	"n1" => $_POST["n1"] ?? '', 
  
	"a2" => $_POST["a2"] ?? '',
	"x2" => $_POST["x2"] ?? '',
	"n2" => $_POST["n2"] ?? '', 
  
	"a3" => $_POST["a3"] ?? '',
	"x3" => $_POST["x3"] ?? '',
	"n3" => $_POST["n3"] ?? '', 
  
	"a4" => $_POST["a4"] ?? '',
	"x4" => $_POST["x4"] ?? '',
	"n4" => $_POST["n4"] ?? '', 
  
	"a5" => $_POST["a5"] ?? '',
	"x5" => $_POST["x5"] ?? '',
	"n5" => $_POST["n5"] ?? '', 
  
	"a6" => $_POST["a6"] ?? '',
	"x6" => $_POST["x6"] ?? '',
	"n6" => $_POST["n6"] ?? '', 
  
	"a7" => $_POST["a7"] ?? '',
	"x7" => $_POST["x7"] ?? '',
	"n7" => $_POST["n7"] ?? '', 
  
	"a8" => $_POST["a8"] ?? '',
	"x8" => $_POST["x8"] ?? '',
	"n8" => $_POST["n8"] ?? '', 
  
	"a9" => $_POST["a9"] ?? '',
	"x9" => $_POST["x9"] ?? '',
	"n9" => $_POST["n9"] ?? '', 
  
	"a10" => $_POST["a10"] ?? '',
	"x10" => $_POST["x10"] ?? '',
	"n10" => $_POST["n10"] ?? '', 
  
	"a11" => $_POST["a11"] ?? '',
	"x11" => $_POST["x11"] ?? '',
	"n11" => $_POST["n11"] ?? '', 
  
	"a12" => $_POST["a12"] ?? '',
	"x12" => $_POST["x12"] ?? '',
	"n12" => $_POST["n12"] ?? '', 
  
	"a13" => $_POST["a13"] ?? '',
	"x13" => $_POST["x13"] ?? '',
	"n13" => $_POST["n13"] ?? '', 
  
	"a14" => $_POST["a14"] ?? '',
	"x14" => $_POST["x14"] ?? '',
	"n14" => $_POST["n14"] ?? '', 
  
	"a15" => $_POST["a15"] ?? '',
	"x15" => $_POST["x15"] ?? '',
	"n15" => $_POST["n15"] ?? '', 
  
	"a16" => $_POST["a16"] ?? '',
	"x16" => $_POST["x16"] ?? '',
	"n16" => $_POST["n16"] ?? '', 
  
	"a17" => $_POST["a17"] ?? '',
	"x17" => $_POST["x17"] ?? '',
	"n17" => $_POST["n17"] ?? '', 
  
	"a18" => $_POST["a18"] ?? '',
	"x18" => $_POST["x18"] ?? '',
	"n18" => $_POST["n18"] ?? '', 
  
	"a19" => $_POST["a19"] ?? '',
	"x19" => $_POST["x19"] ?? '',
	"n19" => $_POST["n19"] ?? '', 
  
	"a20" => $_POST["a20"] ?? '',
	"x20" => $_POST["x20"] ?? '',
	"n20" => $_POST["n20"] ?? '', 
	
  );
  
  $result = $panel->update_mus($_POST['mid'], $answers);
  
  if($result === TRUE) {
	die("modtaget");
  } 
	
?>