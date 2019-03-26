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
	
	if(!filter_var($identifier, FILTER_VALIDATE_EMAIL)) { return 'Invalid email address'; }
	
	$sql = "SELECT * FROM " . DB_PREFIX . "employees WHERE email = ? LIMIT 1";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("s", $identifier);
	if (!$stmt->execute()) { return('Sorry, we ran into some technical difficulties'); } // $stmt->error
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'That email could not be recognized'; }
	
	$row = $result->fetch_assoc();
	
	$stored_pass = $row['pass'];
	$stored_id = $row['id'];
	
	if(strlen($pass) < 8) { return 'Invalid password'; }
	
	if(!password_verify($pass, $stored_pass)) { return 'You\'ve enetered a wrong password'; }
	
	$netkey = $this->token(512);
	
	$sql = "INSERT INTO " . DB_PREFIX . "sessions (netkey, uid, last_seen, logged) VALUES (?, ?, NOW(), NOW())";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("s", $netkey, $stored_id);
	if (!$stmt->execute()) { return('Sorry, we ran into some technical difficulties'); }
	
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
	
    return "test"; 
	
  } 
  
  
  function clear_sessions($days) { 
	
	$sql = "DELETE FROM " . DB_PREFIX . "sessions WHERE last_seen < DATE_SUB(NOW(), INTERVAL ? DAY)";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("i", $days);
	if (!$stmt->execute()) { return('Sorry, we ran into some technical difficulties'); }
	
    return TRUE; 
	
  } 
  
  
  function validate() { 
	
	if(!isset($_COOKIE['netkey']) && !isset($_SESSION['netkey'])) { 
	  return 'No login-token available';
	}
	
	if(isset($_COOKIE['netkey'])) {
	  $key = $_COOKIE['netkey'];
	  $cookie = TRUE;
	} else {
	  $key = $_SESSION['netkey'];
	  $cookie = FALSE;
	}
	
	if(strlen($key) != 512) { return 'Invalid login-token'; }
	
	// Clear sessions older than x days
	$clear = $this->clear_sessions(30);
	
	$sql = "SELECT * FROM " . DB_PREFIX . "sessions WHERE netkey = ? LIMIT 1";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("s", $key);
	if (!$stmt->execute()) { return('Sorry, we ran into some technical difficulties'); }
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'You do not appear to be logged in'; }
	
	$row = $result->fetch_assoc();
	
	$new_key = $this->token(512);
	
	$sql = "UPDATE " . DB_PREFIX . "sessions SET netkey = ?, last_seen = NOW() WHERE netkey = ?";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("ss", $new_key, $key);
	if (!$stmt->execute()) { return('Sorry, we ran into some technical difficulties'); }
	
	$destroy = $this->logout();
	
	if($cookie === TRUE) {
	  $timespan = time() + 5 * 24 * 60 * 60; 
	  setcookie('netkey', $new_key, $timespan, '/');
	} else {
	  $_SESSION['netkey'] = $new_key;
	}
	
	return $row;
	
	$stmt->close();
	
  } 
  
	
}

?>