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

if($info["admin"] == 1) {
  
  if(!isset($_GET['id'])) { die("Ugyldig medarbejder"); }
  
  $delete = $user->remove((int)$_GET['id']);
  
}

header("location: index");
    
?>