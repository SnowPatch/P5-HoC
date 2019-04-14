<?php 

session_start();
date_default_timezone_set("Europe/Berlin");

require_once 'db.inc.php';

// Include DB-con without extending classes

//class User {
//
//  private $db;
//   
//  function __construct() {
//	$db = new Database();
//	$this->db = $db->connect();
//  }


class User extends Database {
	
  function token($length = 256) {
	
	$randStr = '';
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#&%@.+'; // !?$£-:;_
    $charsLen = strlen($chars); 
    
    for ($i = 0; $i < $length; $i++) { 
	  $randStr .= $chars[rand(0, $charsLen - 1)]; 
	} 
	
	return $randStr; 
	
  }
  
	
  function login($identifier, $pass, $cookie = FALSE) { 
	
	if(!filter_var($identifier, FILTER_VALIDATE_EMAIL)) { return 'Ugyldig email adresse'; }
	
	$sql = "SELECT id, pass FROM " . DB_PREFIX . "employees WHERE email = ? AND deleted = 0";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("s", $identifier);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); } // $stmt->error
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'En bruger med denne email kunne ikke findes'; }
	
	$row = $result->fetch_assoc();
	
	$stored_pass = $row['pass'];
	$stored_id = (int)$row['id'];
	
	if(strlen($pass) < 8) { return 'Ugyldig adgangskode'; }
	
	if(!password_verify($pass, $stored_pass)) { return 'Den indtastede adgangskode er forkert'; }
	
	$netkey = $this->token(512);
	
	$sql = "INSERT INTO " . DB_PREFIX . "sessions (netkey, uid, last_seen, logged) VALUES (?, ?, NOW(), NOW())";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("si", $netkey, $stored_id);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	if($cookie == TRUE) {
	  
	  // 5 Day cookie duration
	  $timespan = time() + 5 * 24 * 60 * 60; 
	  setcookie('netkey', $netkey, $timespan, '/');
	  
	} else {
	  
	  $_SESSION['netkey'] = $netkey;
	  
	}
	
	return TRUE;
	
	$stmt->close();
	
  } 
  
  
  function logout() { 
	
	if(isset($_COOKIE['netkey'])) {
	  setcookie('netkey', FALSE, -1, '/');
	}
	session_destroy();
	
    return TRUE; 
	
  } 
  
	
  function fetch($uid) { 
	
	$sql = "SELECT id, email, name, permissions, created, admin FROM " . DB_PREFIX . "employees WHERE id = ? AND deleted = 0";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("i", $uid);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'Vi kunne desværre ikke indhente information om kontoen'; }
	
	return $result->fetch_assoc();
	
	$stmt->close();
	
  } 
  
  
  function clear_sessions($days) { 
	
	$sql = "DELETE FROM " . DB_PREFIX . "sessions WHERE last_seen < DATE_SUB(NOW(), INTERVAL ? DAY)";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("i", $days);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
    return TRUE; 
	
	$stmt->close();
	
  } 
  
  
  function validate() { 
	
	if(!isset($_COOKIE['netkey']) && !isset($_SESSION['netkey'])) { 
	  return 'Ingen login-nøgle fundet';
	}
	
	if(isset($_COOKIE['netkey'])) {
	  $key = $_COOKIE['netkey'];
	  $cookie = TRUE;
	} else {
	  $key = $_SESSION['netkey'];
	  $cookie = FALSE;
	}
	
	if(strlen($key) != 512) { return 'Ugyldig login-nøgle'; }
	
	// Clear sessions older than x days
	$clear = $this->clear_sessions(30);
	
	$sql = "SELECT uid, last_seen FROM " . DB_PREFIX . "sessions WHERE netkey = ?";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("s", $key);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'Det ser desværre ikke ud til at du er logget ind'; }
	
	$row = $result->fetch_assoc();
	
	$new_key = $this->token(512);
	
	$sql = "UPDATE " . DB_PREFIX . "sessions SET netkey = ?, last_seen = NOW() WHERE netkey = ?";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("ss", $new_key, $key);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	if($cookie === TRUE) {
		
	  setcookie('netkey', FALSE, -1, '/');
	  $timespan = time() + 5 * 24 * 60 * 60; 
	  setcookie('netkey', $new_key, $timespan, '/');
	  
	} else {
		
	  $_SESSION['netkey'] = $new_key;
	  
	}
	
	return $row;
	
	$stmt->close();
	
  } 
  
  
  function remove($uid) { 
	
	$sql = "UPDATE " . DB_PREFIX . "employees SET deleted = 1 WHERE id = ?";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("i", $uid);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	return TRUE;
	
	$stmt->close();
	
  } 
  
  
  function create($identifier, $name, $role) { 
	
	if(!filter_var($identifier, FILTER_VALIDATE_EMAIL)) { return 'Ugyldig email adresse'; }
	
	if(strlen($name) < 4) { return 'Indtast et navn på over 3 tegn'; }
	
	if($role != 0 && $role != 1) { return 'Vælg en gyldig konto type'; }
	
	$sql = "SELECT id FROM " . DB_PREFIX . "employees WHERE email = ? AND deleted = 0";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("s", $identifier);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	$result = $stmt->get_result();
	
	if($result->num_rows > 0) { return 'Der findes allerede en bruger med denne email'; }
	
	$pass_gen = $this->token(16);
	$pass_crypt = password_hash($pass_gen, PASSWORD_DEFAULT);
	
	$sql = "INSERT INTO " . DB_PREFIX . "employees (email,name,pass,created,admin) VALUES (?,?,?,NOW(),?)";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("sssi", $identifier, $name, $pass_crypt, $role);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	// Notify by email
	require_once('smtp/class.phpmailer.php');
	require_once('smtp/class.smtp.php');

	$mail = new PHPMailer;

	$mail->isSMTP();
	$mail->Host = 'send.one.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'test@swinther.com';
	$mail->Password = 'test12345';
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;

	$mail->setFrom('test@swinther.com', 'Swinther Testing');
	$mail->addAddress($identifier); 
	$mail->addReplyTo('test@swinther.com', 'Swinther Testing');

	$mail->isHTML(true); 

	$mail->Subject = 'Din kode til HoC WebMUS';
	$mail->Body    = '
	<br>Din konto til WebMUS er nu oprettet. 
	<br>Du kan logge ind med din email samt følgende kodeord: <b>'.$pass_gen.'</b>
	<br>
	<br>Husk dog, at koden helst skal skiftes hurtigst muligt.';
	$mail->AltBody = 'Din konto til WebMUS er oprettet. Din kode er '.$pass_gen;

	if(!$mail->send()) {
		return('Kontoen er oprettet, men koden kunne ikke sendes. Brug denne kode for at logge ind: '.$pass_gen);
	}
	
	return TRUE;
	
	$stmt->close();
	
  } 
  
  
  function change_pass($uid, $old, $new, $repeat) { 
	
	$sql = "SELECT pass FROM " . DB_PREFIX . "employees WHERE id = ? AND deleted = 0";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("i", $uid);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); } // $stmt->error
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'Ugyldig bruger'; }
	
	$row = $result->fetch_assoc();
	
	$stored_pass = $row['pass'];
	
	if(strlen($new) < 8) { return 'Ugyldig adgangskode'; }
	
	if(!password_verify($old, $stored_pass)) { return 'Den indtastede gamle adgangskode er forkert'; }
	
	if($new !== $repeat) { return 'De nye kodeord matcher ikke'; }
	
	$pass_crypt = password_hash($new, PASSWORD_DEFAULT);
	
	$sql = "UPDATE " . DB_PREFIX . "employees SET pass = ? WHERE id = ?";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	$stmt->bind_param("si", $pass_crypt, $uid);
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	return TRUE;
	
	$stmt->close();
	
  } 
  
}


class Panel extends Database {
	
  function fetch_employees() { 
	
	$sql = "SELECT id, email, name, permissions, created, admin FROM " . DB_PREFIX . "employees WHERE deleted = 0 ORDER BY id ASC";
	if(!($stmt = $this->db->prepare($sql))) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	if (!$stmt->execute()) { return('Beklager, vi oplever desværre nogle tekniske problemer'); }
	
	$result = $stmt->get_result();
	
	if($result->num_rows == 0) { return 'Vi kunne desværre ikke finde '; }
	
	$array = [];
	while ($row = $result->fetch_assoc()) {
	  array_push($array, $row);
	}
	
	return $array;
	
	$stmt->close();
	
  } 
  
}

?>