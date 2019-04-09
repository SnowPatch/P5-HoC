<?php 

// Define DB prefix, example: nc_ in front of every table
define("DB_PREFIX", ""); 

define("DB_SERVER", "localhost"); 
define("DB_USER", "root"); 
define("DB_PASS", ""); 
define("DB_NAME", "netclear"); 

class Database {
  
  protected $db;
   
  function __construct() {
	$this->db = $this->connect();
  }

  function connect() {
    
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	if ($db->connect_errno) { die("Der kunne ikke oprettes forbindelse til vores database. Prøv igen senere"); }
	
	$db->set_charset("utf8");

    return $db;
  }
  
}

?>