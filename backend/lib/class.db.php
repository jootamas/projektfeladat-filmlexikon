<?php
	
	// adatbazis kapcsolodas a config.php -ben megadott adatok alapjan
	
	class Db {
		
		public $db;
		
		public function __construct(){
			
			try {
				
				$conn = new mysqli( DB_HOST, DB_USER, DB_PW, DB_DB );
				
				if( $conn->connect_error ){
					
					throw new Exception( $conn->connect_error );
					
				}
				
			}
			catch ( Exception $e ){
				
				echo json_encode( array( 'status' => 'no', 'msg' => 'Hiba: ' . $e->getMessage() ) );
				
				exit;
				
			}
			
			$this->db = $conn;
			
		}

		public function getConn(){

			return $this->db;

		}

	}
	
?>
