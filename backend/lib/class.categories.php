<?php

	class Categories extends Db
	{
		public function getCategoriesIdByTitle( $moviesCategory )
		{
			/**
			 * a parameterkent kapott kategoria adatbazis azonositoit adja vissza;
			 * ha a kategoria meg nem letezik, akkor hozzaadja
			 **/
			
			$categoriesIDs = array();
			
			if( strpos( $moviesCategory, ';' ) !== false ){
				
				// ha van benne pontosvesszo, akkor tobb orszagot is megadtunk
				
				foreach( explode( ';', $moviesCategory ) as $category ){
					
					$query = $this->db->query( "SELECT * FROM categories WHERE categories_title = '".$category."'" );
					
					if( $query->num_rows == 0 ){
						
						// nincs meg ilyen kategoria a categories tablaban
						
						$this->db->query( "INSERT INTO categories ( categories_title ) VALUES ( '".$category."' )" );
						
						$newCategoriesId = $this->db->insert_id;
						
						$categoriesIDs[] = $newCategoriesId;
						
					}
					else {
						
						$rows = $query->fetch_assoc();
						
						$categoriesIDs[] = $rows[ 'categories_id' ];
						
					}
					
				}
				
			}
			else {
				
				$query = $this->db->query( "SELECT * FROM categories WHERE categories_title = '".$moviesCategory."'" );
				
				if( $query->num_rows == 0 ){
					
					// nincs meg ilyen kategoria a categories tablaban
					
					$this->db->query( "INSERT INTO categories ( categories_title ) VALUES ( '".$moviesCategory."' )" );
					
					$newCategoriesId = $this->db->insert_id;
					
					$categoriesIDs[] = $newCategoriesId;
					
				}
				else {
					
					$rows = $query->fetch_assoc();
					
					$categoriesIDs[] = $rows[ 'categories_id' ];
					
				}
				
			}
			
			return $categoriesIDs;
			
		}
		
	}

