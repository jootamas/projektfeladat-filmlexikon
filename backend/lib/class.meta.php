<?php

	class Meta extends Db
	{
		
		public function updateMeta( $table, $tableId, $key, $value )
		{
			// frissites a meta tablaban, ha mar letezik az adott meta, akkor update, ha nem, akkor insert
			
			$query = $this->db->query( "SELECT * FROM meta WHERE meta_table = '".$table."' AND meta_table_id = ".$tableId." AND meta_key = '".$key."'" );
			
			if( $query->num_rows != 0 ){
				
				// van mar ilyen meta, tehat update
				
				$this->db->query( "UPDATE meta SET meta_value = '".addslashes( $value )."' WHERE meta_table = '".$table."' AND meta_table_id = ".$tableId." AND meta_key = '".$key."'" );
				
			}
			else {
				
				// nincs meg ilyen meta, tehat insert
				
				$this->db->query( "INSERT INTO meta ( meta_table, meta_table_id, meta_key, meta_value ) VALUES ( '".$table."', ".$tableId.", '".$key."', '".addslashes( $value )."' )" );
				
			}
			
		}
		
	}

