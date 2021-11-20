<?php
	class dbConn {
    	protected $mysqli;
    	
	    protected function connect(){
	        $this->db = new mysqli();
		    $this->mysqli = new mysqli('localhost', 'root', '', 'compressor');
			if ($this->mysqli->connect_error) {
			  die("Connection failed: " . $this->mysqli->connect_error);
			}
	    }
	}