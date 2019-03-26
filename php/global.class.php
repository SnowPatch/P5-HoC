<?php 

session_start();
date_default_timezone_set("Europe/Berlin");

require_once 'db.inc.php';

// -- Include DB-con without extending classes -- 
//
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
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#&%@.+'; //!?$£-:;_
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
	$stmt->execute();
	
	$result = $stmt->get_result();
	
	if($result->num_rows != 1) { return 'That email could not be recognized'; }
	
	$row = $result->fetch_assoc();
	
	$stored_pass = $row['pass'];
	$stored_id = $row['id'];
	
	if(strlen($pass) < 8) { return 'Invalid password'; }
	
	if(!password_verify($pass, $stored_pass)) { return 'You\'ve enetered a wrong password'; }
	
	$netkey = $this->token(512);
	
	$sql = "INSERT INTO " . DB_PREFIX . "sessions (netkey, logged) VALUES (?, NOW())";
	if(!($stmt = $this->db->prepare($sql))) { return('Sorry, we ran into some technical difficulties'); }
	$stmt->bind_param("s", $netkey);
	$stmt->execute();
	
	if($cookie == TRUE) {
	  
	  // 5 Days cookie duration
	  $timespan = time() + 5 * 24 * 60 * 60; 
	  setcookie('netkey', $netkey, $timespan);
	  
	  return TRUE;
	  
	} else {
	  
	  $_SESSION['netkey'] = $netkey;
	  
	  return TRUE;
	  
	}
	
	$stmt->close();
	
  } 
  
  
  function logout() { 
	
	if(isset($_COOKIE['netkey'])) {
	  unset($_COOKIE['netkey']);
	}
	session_destroy();
	
    return TRUE; 
	
  } 
  
	
  function fetch($uid) { 
	
    return "test"; 
	
  } 
  
  
  function validate($key) { 
	
	
	
    return "test"; 
	
  } 
  
	
}

?>