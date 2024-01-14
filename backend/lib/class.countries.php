<?php

	class Countries extends Db
	{
		public function getCountriesIdByTitle( $moviesCountry )
		{
			/**
			 * a parameterkent kapott orszag adatbazis azonositoit adja vissza;
			 * ha az orszag meg nem letezik, akkor hozzaadja
			 **/
			
			if( empty( $moviesCountry ) ){
				
				return '';
				
			}
			
			$countriesIDs = array();
			
			if( strpos( $moviesCountry, ';' ) !== false ){
				
				// ha van benne pontosvesszo, akkor tobb orszagot is megadtunk
				
				foreach( explode( ';', $moviesCountry ) as $country ){
					
					$countriesQuery = $this->db->query( "SELECT * FROM countries WHERE countries_title = '".$country."'" );
					
					if( $countriesQuery->num_rows == 0 ){
						
						// nincs meg ilyen orszag a countries tablaban
						
						$this->db->query( "INSERT INTO countries ( countries_title ) VALUES ( '".$country."' )" );
						
						$newCountriesId = $this->db->insert_id;
						
						$countriesIDs[] = $newCountriesId;
						
					}
					else {
						
						$countriesRows = $countriesQuery->fetch_assoc();
						
						$countriesIDs[] = $countriesRows[ 'countries_id' ];
						
					}
					
				}
				
			}
			else {
				
				$countriesQuery = $this->db->query( "SELECT * FROM countries WHERE countries_title = '".$moviesCountry."'" );
				
				if( $countriesQuery->num_rows == 0 ){
					
					// nincs meg ilyen orszag a countries tablaban
					
					$this->db->query( "INSERT INTO countries ( countries_title ) VALUES ( '".$moviesCountry."' )" );
					
					$newCountriesId = $this->db->insert_id;
					
					$countriesIDs[] = $newCountriesId;
					
				}
				else {
					
					$countriesRows = $countriesQuery->fetch_assoc();
					
					$countriesIDs[] = $countriesRows[ 'countries_id' ];
					
				}
				
			}
			
			return $countriesIDs;
			
		}
		
	}

