<?php
	/**
	 * Action helpers
	 */
	class action extends dbConn
	{
		
		//--Db connection method 
		function __construct(){
			$this->connect();
		}
		

		//--Does this access code exist?
		public function save_to_db( $filename, $type ){

			$self 	= new static;
			$query = $self->mysqli->prepare( "INSERT INTO `compressed`(filename, type) VALUES( ?, ? )" );
			$query->bind_param('ss', $filename, $type );
        	return $query->execute();

		}

	}
	/**
	 * Instantiate the 'core' class
	 */
	$action = new action();