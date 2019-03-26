<?php  
  
require_once 'php/global.class.php';
$user = new User();

$result = $user->logout();
if($result === TRUE) {
header("location: login"); die();
}
    
?>