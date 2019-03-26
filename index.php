<?php  
  
require_once 'php/global.class.php';
$user = new User();

$result = $user->validate();

if($result === FALSE || !is_array($result)) {
  $result = $user->logout();
  header("location: login"); die();
}

echo $result['id'];

//$info = $user->fetch($result['id']);

//if($info === FALSE || !is_array($info)) {
//  die('Sorry, we ran into some technical difficulties');
//}
    
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
		  <li><a class="text"><?php echo $info['name']; ?></a></li>
		  <li><a class="button" href="contact">Log ud</a></li>
		  <li><a class="dropdown" onClick="drop();"> <div></div> <div></div> <div></div> </a></li>
		</ul>
	  </div>
	</div>
  </nav>

  <main>
  

	
  </main>

</body>
</html>
