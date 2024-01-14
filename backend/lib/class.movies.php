<?php

	class Movies extends Db
	{
		public function getMovie( $moviesId, $getMovieArgs = array() )
		{
			// adott film adatainak lekerese id alapjan
			
			$moviesQuery = $this->db->query( "SELECT * FROM movies WHERE movies_id = " . $moviesId );
			
			$movie = $moviesQuery->fetch_assoc();
			
			$movie[ 'meta' ] = array();
			
			$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'movies' AND meta_table_id = " . $moviesId );
			
			while( $meta = $metaQuery->fetch_assoc() ){
				
				$movie[ 'meta' ][ $meta[ 'meta_key' ] ] = $meta[ 'meta_value' ];
				
			}
			
			// orszag + kategoria a frontenden valo kiirashoz
			
			$movie[ 'meta' ][ 'countries_and_categories_string' ] = '';
			
			// orszagok
			
			if( isset( $movie[ 'meta' ][ 'countries' ] ) && $movie[ 'meta' ][ 'countries' ] != '' ){
				
				preg_match_all( '/\|([0-9]{1,})\|/msi', $movie[ 'meta' ][ 'countries' ], $cIDs );
				
				$countries = array();
				
				foreach( $cIDs[ 1 ] as $countryId ){
					
					$countriesQuery = $this->db->query( "SELECT * FROM countries WHERE countries_id = " . $countryId );
					
					$countriesRows = $countriesQuery->fetch_assoc();
					
					$countries[] = $countriesRows[ 'countries_title' ];
					
				}
				
				// pontosvesszovel tagolt stringkent adjuk vissza az adminnak
				$movie[ 'meta' ][ 'countries' ] = implode( ';', $countries );
				
				$movie[ 'meta' ][ 'countries_and_categories_string' ] = implode( '-', $countries );
				
			}
			
			// kategoriak
			
			if( isset( $movie[ 'meta' ][ 'categories' ] ) && $movie[ 'meta' ][ 'categories' ] != '' ){
				
				preg_match_all( '/\|([0-9]{1,})\|/msi', $movie[ 'meta' ][ 'categories' ], $cIDs );
				
				$categories = array();
				
				foreach( $cIDs[ 1 ] as $categoryId ){
					
					$categoriesQuery = $this->db->query( "SELECT * FROM categories WHERE categories_id = " . $categoryId );
					
					$categoriesRows = $categoriesQuery->fetch_assoc();
					
					$categories[] = $categoriesRows[ 'categories_title' ];
					
				}
				
				// pontosvesszovel tagolt stringkent adjuk vissza az adminnak
				$movie[ 'meta' ][ 'categories' ] = implode( ';', $categories );
				
				if( $movie[ 'meta' ][ 'countries_and_categories_string' ] != '' ){
					
					// ha fentebb mar hozzaadtuk az orszagokat, akkor plusz egy szokoz
					
					$movie[ 'meta' ][ 'countries_and_categories_string' ] .= ' ';
					
				}
				
				$movie[ 'meta' ][ 'countries_and_categories_string' ] .= implode( ', ', $categories );
				
			}
			
			// ha van plakat file nev, akkor visszaadjuk a teljes URL-jet
			
			if( isset( $movie[ 'meta' ][ 'poster_filename' ] ) && $movie[ 'meta' ][ 'poster_filename' ] != '' ){
				
				$movie[ 'meta' ][ 'poster_url' ] = API_URL . '/uploads/' . $movie[ 'meta' ][ 'poster_filename' ];
				
			}
			else {
				
				$movie[ 'meta' ][ 'poster_url' ] = 'https://fakeimg.pl/400x600';
				
			}
			
			// rendezo
			
			$moviesPersonsQuery = $this->db->query( "SELECT * FROM movies_persons WHERE movies_id = ".$moviesId." AND movies_persons_role = 'director'" );
			
			$moviesPersonsRows = $moviesPersonsQuery->fetch_assoc();
			
			$personsQuery = $this->db->query( "SELECT * FROM persons WHERE persons_id = " . $moviesPersonsRows[ 'persons_id' ] );
			
			$personsRows = $personsQuery->fetch_assoc();
			
			$movie[ 'meta' ][ 'director' ] = json_encode( array( 'persons_id' => $personsRows[ 'persons_id' ], 'persons_name' => $personsRows[ 'persons_name' ] ) );
			
			// szereplok
			
			// string, a szereplok szovegesen az admin feluletre
			$actors = '';
			
			// szereplok listaja tomb, a frontendre
			$movie[ 'actors_list' ] = array();
			
			$persons = new Persons();
			
			$moviesPersonsQuery = $this->db->query( "SELECT * FROM movies_persons WHERE movies_id = ".$moviesId." AND movies_persons_role = 'actor'" );
			
			$getPersonArgs = array( 'movies_list' => false );
			
			while( $moviesPerson = $moviesPersonsQuery->fetch_assoc() ){
				
				$personsQuery = $this->db->query( "SELECT * FROM persons WHERE persons_id = " . $moviesPerson[ 'persons_id' ] );
				
				$personsRows = $personsQuery->fetch_assoc();
				
				$actors .= $personsRows[ 'persons_name' ] . ';' . $moviesPerson[ 'movies_persons_character' ] . "\n";
				
				if( ! isset( $getMovieArgs[ 'actors_list' ] ) ){
					
					$movie[ 'actors_list' ][] = array(
						'person' => $persons->getPerson( $moviesPerson[ 'persons_id' ], $getPersonArgs ),
						'character' => $moviesPerson[ 'movies_persons_character' ]
					);
					
				}
				
			}
			
			// az utolso sortores nem kell
			$movie[ 'meta' ][ 'actors' ] = trim( $actors );
			
			return $movie;
			
		}
		
	}

