<?php  
  
require_once 'global.class.php';
$user = new User();

$login = $user->validate();
if($login === FALSE || !is_array($login)) {
  $login = $user->logout();
  header("location: login"); die();
}

if(isset($_POST["a1"])) {
	
  die("modtaget");
  
} else {
  die("fejl");
}
	
?>