<?php

	class Persons extends Db
	{
		public function getPersonIdByName( $name )
		{
			// nev alapjan visszaadja a szemely id-t, ha nincs ilyen szemely, akkor hozzaadja es annak az uj rekordnak adja vissza az id-jat
			
			$personsQuery = $this->db->query( "SELECT * FROM persons WHERE persons_name = '".$name."'" );
			
			if( $personsQuery->num_rows != 0 ){
				
				$personsRows = $personsQuery->fetch_assoc();
				
				$personsId = $personsRows[ 'persons_id' ];
				
			}
			else {
				
				$this->db->query( "INSERT INTO persons ( persons_name ) VALUES ( '".$name."' )" );
				
				$personsId = $this->db->insert_id;
				
			}
			
			return $personsId;
			
		}
		
		public function getPerson( $id, $getPersonArgs = array() )
		{
			// id alapjan visszaadja a szemely minden adatat
			
			$personsQuery = $this->db->query( "SELECT * FROM persons WHERE persons_id = " . $id );
			
			$person = $personsQuery->fetch_assoc();
			
			$person[ 'meta' ] = array();
			
			$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'persons' AND meta_table_id = " . $id );
			
			while( $meta = $metaQuery->fetch_assoc() ){
				
				$person[ 'meta' ][ $meta[ 'meta_key' ] ] = $meta[ 'meta_value' ];
				
			}
			
			if( isset( $person[ 'meta' ][ 'persons_country' ] ) && $person[ 'meta' ][ 'persons_country' ] != '' ){
				
				preg_match_all( '/\|([0-9]{1,})\|/msi', $person[ 'meta' ][ 'persons_country' ], $cIDs );
				
				$countries = array();
				
				foreach( $cIDs[ 1 ] as $countryId ){
					
					$countriesQuery = $this->db->query( "SELECT * FROM countries WHERE countries_id = " . $countryId );
					
					$countriesRows = $countriesQuery->fetch_assoc();
					
					$countries[] = $countriesRows[ 'countries_title' ];
					
				}
				
				// pontosvesszovel tagolt stringkent adjuk vissza az adminnak
				$person[ 'meta' ][ 'persons_country' ] = implode( ';', $countries );
				
			}
			
			// ha van foto file nev, akkor visszaadjuk a teljes URL-jet
			
			if( isset( $person[ 'meta' ][ 'photo_filename' ] ) && $person[ 'meta' ][ 'photo_filename' ] != '' ){
				
				$person[ 'meta' ][ 'photo_url' ] = API_URL . '/uploads/' . $person[ 'meta' ][ 'photo_filename' ];
				
			}
			else {
				
				$person[ 'meta' ][ 'photo_url' ] = 'https://fakeimg.pl/400x600';
				
			}
			
			// filmjei evszam szerint csokkeno sorrendben
			
			$movies = new Movies();
			
			$moviesPersonsQuery = $this->db->query( "
				
				SELECT 
					* 
				FROM 
					movies_persons 
				INNER JOIN movies ON movies.movies_id = movies_persons.movies_id 
				WHERE 
					movies_persons.persons_id = " . $id . "
				ORDER BY movies.movies_year DESC, movies.movies_title ASC 
			");
			
			$person[ 'movies_count' ] = $moviesPersonsQuery->num_rows;
			
			$person[ 'movies_list' ] = array();
			
			if( ! isset( $getPersonArgs[ 'movies_list' ] ) ){
				
				$getMovieArgs = array( 'actors_list' => false );
				
				while( $moviesPersons = $moviesPersonsQuery->fetch_assoc() ){
					
					$person[ 'movies_list' ][] = $movies->getMovie( $moviesPersons[ 'movies_id' ], $getMovieArgs );
					
				}
				
			}
			
			// "titulus" a frontenden kirhato formaban, pl. amerikai szinesz, 44 eves
			
			$person[ 'meta' ][ 'persons_title' ] = '';
			
			if( ! empty( $person[ 'meta' ][ 'persons_country' ] ) ){
				
				$person[ 'meta' ][ 'persons_title' ] .= str_replace( ';', '-', $person[ 'meta' ][ 'persons_country' ] );
				
			}
			
			$moviesPersonsDirectorQuery = $this->db->query( "SELECT * FROM movies_persons WHERE movies_persons_role = 'director' AND persons_id = " . $id );
			
			$moviesPersonsActorQuery = $this->db->query( "SELECT * FROM movies_persons WHERE movies_persons_role = 'actor' AND persons_id = " . $id );
			
			if( $moviesPersonsDirectorQuery->num_rows != 0 && $moviesPersonsActorQuery->num_rows != 0 ){
				
				$person[ 'meta' ][ 'persons_title' ] .= ' rendező-színész';
				
			}
			else if( $moviesPersonsDirectorQuery->num_rows != 0 ){
				
				$person[ 'meta' ][ 'persons_title' ] .= ' rendező';
				
			}
			else {
				
				$person[ 'meta' ][ 'persons_title' ] .= ' színész';
				
			}
			
			if( ! empty( $person[ 'meta' ][ 'persons_birth' ] ) ){
				
				$ex = explode( '-', $person[ 'meta' ][ 'persons_birth' ] );
				
				$person[ 'meta' ][ 'persons_title' ] .= ', ' . date( 'Y' ) - $ex[ 0 ] . ' éves';
				
			}
			
			$person[ 'meta' ] = (object)$person[ 'meta' ];
			
			return $person;
			
		}
		
	}

