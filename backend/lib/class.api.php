<?php
	
	// Api osztaly, ezt peldanyositjuk minden API hivas eseten a gyokerben levo api.php -ban
	
	class Api extends Db
	{
		// osszes film lekerese
		
		public function listMovies( $search = '' )
		{
			$search = addslashes( $search );
			
			if( $search != '' ){
				
				// ha kaptunk keresesi kifejezest, akkor arra keresunk a film cimek es a filmhez kapcsolodo (rendezo vagy szereplo) szemely nevek kozt
				
				$query = $this->db->query( "
					SELECT 
						movies.* 
					FROM movies 
						LEFT JOIN movies_persons ON movies_persons.movies_id = movies.movies_id 
						LEFT JOIN persons ON persons.persons_id = movies_persons.persons_id  
					WHERE 
						movies.movies_title LIKE '%".$search."%' OR 
						movies.movies_title_original LIKE '%".$search."%' OR 
						persons.persons_name LIKE '%".$search."%' OR 
						persons.persons_name LIKE '%".$search."%'
					GROUP BY movies.movies_id
					ORDER BY movies.movies_title ASC 
				" );
				
			}
			else {
				
				// ha nem, akkor felsoroljuk az osszes filmet
				
				$query = $this->db->query( "
					SELECT 
						movies.* 
					FROM movies 
					ORDER BY movies.movies_title ASC 
				" );
				
			}
			
			$moviesList = array();

			if( $query->num_rows != 0 ){

				$movies = new Movies();

				while( $mov = $query->fetch_assoc() ){

					$moviesList[] = $movies->getMovie( $mov[ 'movies_id' ] );

				}

			}

			// valaszban "ok" status es movies-ban a filmek
			
			$response = array(
				'status' => 'ok',
				'movies' => $moviesList
			);
			
			return $response;
			
		}
		
		// egy adott film minden adatanak lekerese
		
		public function getMovie( $moviesId )
		{
			/**
			 * peldanyositjuk a Movies osztalyt, aminek a getMovie metodusa lekeri
			 * es osszeallitja a kliensek altal megjelenitett adatokat
			 **/
			$movies = new Movies();
			
			// valaszban "ok" status es a film adatai
			
			$response = array(
				'status' => 'ok',
				'movies' => array( $movies->getMovie( $moviesId ) )
			);
			
			return $response;
			
		}
		
		// film hozzaadasa vagy modositasa

		public function saveMovieValidate( $request )
		{
			// nehany alap adatot ellenorzunk

			if( $request[ 'movies_title' ] == '' ){

				return array(
					'status' => 'no',
					'msg' => 'A film címe nem lehet üres'
				);

			}
			else if( $request[ 'movies_category' ] == '' ){

				return array(
					'status' => 'no',
					'msg' => 'A kategória nem lehet üres'
				);

			}
			else if( $request[ 'movies_year' ] == '' ){

				return array(
					'status' => 'no',
					'msg' => 'Az évszám nem lehet üres'
				);

			}
			else if( ! preg_match( '/^[0-9]{4}$/', $request[ 'movies_year' ] ) ){

				return array(
					'status' => 'no',
					'msg' => 'Az évszám csak négyjegyű szám lehet'
				);

			}
			else if( $request[ 'movies_director' ] == '' ){

				return array(
					'status' => 'no',
					'msg' => 'A rendező nem lehet üres'
				);

			}

			return true;

		}

		public function saveMovie( $request )
		{
			$saveMovieValidate = $this->saveMovieValidate( $request );

			if( $saveMovieValidate !== true ){

				return $saveMovieValidate;

			}
			
			$moviesTitle = trim( $request[ 'movies_title' ] );
			$moviesTitleOriginal = trim( $request[ 'movies_title_original' ] );
			$moviesCountry = trim( $request[ 'movies_country' ] );
			$moviesCategory = trim( $request[ 'movies_category' ] );
			$moviesYear = (int)trim( $request[ 'movies_year' ] );
			$moviesDirector = trim( $request[ 'movies_director' ] );
			$moviesActors = trim( $request[ 'movies_actors' ] );
			$moviesPlot = trim( $request[ 'movies_plot' ] );
			$moviesPosterURL = trim( $request[ 'poster_url' ] );
			$moviesPosterDelete = trim( $request[ 'poster_delete' ] );
			
			// alap adatok a movies tablaba, tovabbi adatok pedig a meta tablaba
			
			if( $request[ 'movies_id' ] == '' ){
				
				// ha nem kaptunk a klienstol movies_id -t, akkor uj film, azaz insert
				
				$this->db->query( "INSERT INTO movies ( movies_title, movies_title_original, movies_year ) VALUES ( '".addslashes( $moviesTitle )."', '".addslashes( $moviesTitleOriginal )."', ".$moviesYear." )" );
				
				// az uj film egyedi azonositoja, a movies_id
				
				$moviesId = $this->db->insert_id;
				
			}
			else {
				
				// ha kaptunk movies_id -t, akkor pedig annak az adatait frissitjuk, azaz update
				
				$moviesId = $request[ 'movies_id' ];
				
				$this->db->query( "UPDATE movies SET movies_title = '".addslashes( $moviesTitle )."', movies_title_original = '".addslashes( $moviesTitleOriginal )."', movies_year = ".$moviesYear." WHERE movies_id = " . $moviesId );
				
			}
			
			// a tovabbiakban pedig mentjuk a film tovabbi adatait a $moviesId alapjan
			
			/**
			 * orszag(ok)
			 *
			 * a $moviesCountry egy string a kovetkezo formaban: "amerikai;francia", azaz a film amerikai es francia gyartasu;
			 * ezt atadjuk a getCountriesIdByTitle() metodusnak, ami feldolgozza:
			 * - ha van mar "amerikai" a countries tablaban, akkor visszaadja az azonositojat
			 * - ha nincs meg, akkor hozzaadja es annak adja vissza az azonositojat
			 *
			 * ezeket az azonositokat pedig eltaroljuk egy filmhez tartozo meta-ban a kovetkezo formaban:
			 *
			 * |1|;|2| - azaz a countries tabla 1-es es 2-es soraban levo orszag tartozik a filmhez
			 *
			 * igy ha barmiert modositani kellene pl. az "amerikai"-t, akkor csak a countries tablaban kell atirnunk, nem pedig minden amerikai filmnel
			 **/
			
			if( $moviesCountry != '' ){
				
				$countries = new Countries();
				
				// feldolgozza az adminrol kapott stringet a fentiek alapjan es visszaadja az azonositokat
				
				$countriesIDs = $countries->getCountriesIdByTitle( $moviesCountry );
				
				$meta = new Meta();
				
				// metaba mentjuk a visszakapott azonositokat
				
				$meta->updateMeta( 'movies', $moviesId, 'countries', '|' . implode( '|;|', $countriesIDs ) . '|' );
				
			}
			
			// kategoriak - ugyan ugy, mint a fentebb irt orszagok
			
			if( $moviesCategory != '' ){
				
				$categories = new Categories();
				
				$categoriesIDs = $categories->getCategoriesIdByTitle( $moviesCategory );
				
				$meta = new Meta();
				
				$meta->updateMeta( 'movies', $moviesId, 'categories', '|' . implode( '|;|', $categoriesIDs ) . '|' );
				
			}
			
			/**
			 * a filmen dolgozo szemelyek, azaz rendezo es szereplok
			 *
			 * ha pl. egy filmnek Steven Spielberg a rendezoje, akkor o bekerul a persons tablaba (ha meg nincs benne), es lesz egy egyedi azonositoja
			 *
			 * ezutan a film azonositojat + szemely azonositojat eltaroljuk a movies_persons tablaban, azaz ez tartalmazza a filmen dolgozo szemelyeket az egyedi azonositojuk alapjan
			 *
			 * a movies_persons_role tartalmazza, hogy az adott szemely rendezo (director) vagy szinesz (actor)
			 *
			 * a movies_persons_character pedig a filmben jatszott karakter nevet, ez a rendezonel nyilvan ures, csak a szereploknel relevans
			 *
			 * igy ha egy szemely neve barmi miatt megvaltozik, vagy epp eszrevettuk, hogy elirtuk, akkor csak a persons tablaban kell modositanunk, nem minden filmjenel
			 **/
			
			// rendezo
			
			if( $request[ 'movies_id' ] == '' ){
				
				$persons = new Persons();
				
				$this->db->query( "INSERT INTO movies_persons ( movies_id, persons_id, movies_persons_role, movies_persons_character ) VALUES ( ".$moviesId.", '".$persons->getPersonIdByName( $moviesDirector )."', 'director', '' )" );
				
			}
			
			/**
			 * szereplok, beloluk tobb is lehet, de meg kell vizsgalnunk, hogy vannak-e egyaltalan szereplok, mert pl. egy dokumentumfilmnel altalaban nincsenek szineszek
			 *
			 * az adminrol egy tobbsoros szoveget kapunk, soronkent a szinesz neve es az altala jatszott karakter neve, pl.:
			 *
			 * Tom Hanks;Forrest Gump
			 *
			 * ekkor Tom Hanks bekerul a persons tablaba, a film azonositoja + szemely azonositoja pedig a movies_persons tablaba
			 **/
			
			if( $moviesActors != '' ){
				
				$persons = new Persons();
				
				foreach( explode( "\n", $moviesActors ) as $actor ){
					
					if( $actor == '' ){
						// ures sor
						continue;
					}
					
					$ex = explode( ';', $actor );
					
					$name = trim( $ex[ 0 ] );
					
					if( isset( $ex[ 1 ] ) ){
						
						$character = trim( $ex[ 1 ] );
						
					}
					else {
						
						// nem adtunk meg karakter nevet
						$character = '';
						
					}
					
					// szemely egyedi azonositoja a neve alapjan
					
					$personsId = $persons->getPersonIdByName( $name );
					
					$moviesPersonsQuery = $this->db->query( "SELECT * FROM movies_persons WHERE movies_id = ".$moviesId." AND persons_id = " . $personsId );
					
					if( $moviesPersonsQuery->num_rows == 0 ){
						
						// ez a szemely meg nincs hozzaadva ehhez a filmhez
						
						$this->db->query( "INSERT INTO movies_persons ( movies_id, persons_id, movies_persons_role, movies_persons_character ) VALUES ( ".$moviesId.", ".$personsId.", 'actor', '".$character."' )" );
						
					}
					else {
						
						// ez a szemely mar hozza van adva ehhez a filmhez, csak a szerep nevet frissitjuk, ha esetleg adminon atirtuk
						
						$moviesPersonsRows = $moviesPersonsQuery->fetch_assoc();
						
						$this->db->query( "UPDATE movies_persons SET movies_persons_character = '".$character."' WHERE movies_persons_id = " . $moviesPersonsRows[ 'movies_persons_id' ] );
						
					}
					
				}
				
			}
			
			// szinopszis - egyszeru szoveget kapunk az adminrol
			
			$meta = new Meta();
			
			$meta->updateMeta( 'movies', $moviesId, 'plot', $moviesPlot );
			
			// plakat - vagy magat a file-t kapjuk meg a $_FILES -ban, vagy csak egy URL-t, ekkor letoltjuk onnan a plakatot
			
			if( isset( $_FILES[ 'poster' ] ) && isset( $_FILES[ 'poster' ][ 'tmp_name' ] ) && file_exists( $_FILES[ 'poster' ][ 'tmp_name' ] ) ){
				
				// ha van mar plakatja (update eseten), akkor azt toroljuk
				
				$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'movies' AND meta_table_id = ".$moviesId." AND meta_key = 'poster_filename'" );
				
				if( $metaQuery->num_rows != 0 ){
					
					$metaRows = $metaQuery->fetch_assoc();
					
					unlink( 'uploads/' . $metaRows[ 'meta_value' ] );
					
				}
				
				// a file nevbe bekerul a timestamp, hogy ha plakatot cserelunk, akkor a frontenden a bongeszo ne az elozot toltse be cache-bol
				
				$filename = 'poster-' . $moviesId . '-' . time() . '.jpg';
				
				if( copy( $_FILES[ 'poster' ][ 'tmp_name' ], 'uploads/' . $filename ) ){
					
					// sikeresen felkerult a file, elmentjuk metaba a file nevet
					
					$meta->updateMeta( 'movies', $moviesId, 'poster_filename', $filename );
					
				}
				
			}
			else if( $moviesPosterURL != '' ) {
				
				// nem magat a plakatot kaptuk, hanem csak egy URL-t, ekkor letoltjuk a plakatot
				
				$getPoster = file_get_contents( $moviesPosterURL );
				
				if( $getPoster ){
					
					// sikerult letolteni a plakatot
					
					$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'movies' AND meta_table_id = ".$moviesId." AND meta_key = 'poster_filename'" );
					
					if( $metaQuery->num_rows != 0 ){
						
						// ha van mar plakatja (update eseten), akkor azt toroljuk
						
						$metaRows = $metaQuery->fetch_assoc();
						
						unlink( 'uploads/' . $metaRows[ 'meta_value' ] );
						
					}
					
					// a file nevbe bekerul a timestamp, hogy ha plakatot cserelunk, akkor a frontenden a bongeszo ne az elozot toltse be cache-bol
					
					$filename = 'poster-' . $moviesId . '-' . time() . '.jpg';
					
					if( file_put_contents( 'uploads/' . $filename, $getPoster ) ){
						
						// sikeresen felkerult a file, elmentjuk metaba a file nevet
						
						$meta->updateMeta( 'movies', $moviesId, 'poster_filename', $filename );
						
					}
					
				}
				
			}
			else if( $moviesPosterDelete == 'ok' ){
				
				// se plakatot nem kapunk, se URL-t, viszont torolni kell a fent levo plakatot
				
				$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'movies' AND meta_table_id = ".$moviesId." AND meta_key = 'poster_filename'" );
				
				if( $metaQuery->num_rows != 0 ){
					
					$metaRows = $metaQuery->fetch_assoc();
					
					unlink( 'uploads/' . $metaRows[ 'meta_value' ] );
					
					$meta->updateMeta( 'movies', $moviesId, 'poster_filename', '' );
					
				}
				
			}
			
			if( $request[ 'movies_id' ] == '' ){
				
				$response = array(
					'status' => 'ok',
					'msg' => 'A film sikeresen hozzáadva'
				);
				
			}
			else {
				
				$response = array(
					'status' => 'ok',
					'msg' => 'A film sikeresen módosítva'
				);
				
			}
			
			return $response;
			
		}
		
		// film torlese
		
		public function deleteMovie( $moviesId )
		{
			
			$movies = new Movies();
			
			// meg torles elott lekerjuk a film adatait, a plakat file nev miatt, amit kulon torlunk
			$movie = $movies->getMovie( $moviesId );
			
			// toroljuk a filmhez kapcsolodo szemelyeket
			
			$this->db->query( "DELETE FROM movies_persons WHERE movies_id = " . $moviesId );
			
			// toroljuk a film metait
			
			$this->db->query( "DELETE FROM meta WHERE meta_table = 'movies' AND meta_table_id = " . $moviesId );
			
			// es a filmet
			
			$delete = $this->db->query( "DELETE FROM movies WHERE movies_id = " . $moviesId );
			
			if( $delete ){
				
				if( ! empty( $movie[ 'meta' ][ 'poster_filename' ] ) ){
					
					unlink( 'uploads/' . $movie[ 'meta' ][ 'poster_filename' ] );
					
				}
				
				$response = array(
					'status' => 'ok',
					'msg' => 'A film sikeresen törölve'
				);
				
			}
			else {
				
				$response = array(
					'status' => 'no',
					'msg' => 'Sikertelen törlés'
				);
				
			}
			
			return $response;
			
		}
		
		// szemelyek listajanak lekerese, illetve szemely keresese
		
		public function listPersons( $search )
		{
			
			if( $search != '' ){
				
				$query = $this->db->query( "SELECT * FROM persons WHERE persons_name LIKE '%".$search."%' ORDER BY persons_name ASC" );
				
			}
			else {
				
				$query = $this->db->query( "SELECT * FROM persons ORDER BY persons_name ASC" );
				
			}
			
			$persons = new Persons();
			
			$personsList = array();
			
			while( $p = $query->fetch_assoc() ){
				
				$personsList[] = $persons->getPerson( $p[ 'persons_id' ] );
				
			}
			
			$response = array(
				'status' => 'ok',
				'persons' => $personsList
			);
			
			return $response;
			
		}
		
		// egy adott szemely minden adatanak lekerese
		
		public function getPerson( $personsId )
		{
			
			$personsList = array();
			
			$persons = new Persons();
			
			$personsList[] = $persons->getPerson( $personsId );
			
			$response = array(
				'status' => 'ok',
				'persons' => $personsList
			);
			
			return $response;
			
		}
		
		// szemely adatainak modositasa
		
		public function savePersonValidate( $request )
		{
			// ellenorzesek

			if( $request[ 'persons_name' ] == '' ){

				return array(
					'status' => 'no',
					'msg' => 'A személy neve nem lehet üres'
				);

			}

			else if( $request[ 'persons_birth' ] != '' && ! preg_match( '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request[ 'persons_birth' ] ) ){

				return array(
					'status' => 'no',
					'msg' => 'A megadott születési dátum formátuma helytelen, helyes formátum: ÉÉÉÉ-HH-NN'
				);

			}

			return true;

		}

		public function savePerson( $request )
		{
			$savePersonValidate = $this->savePersonValidate( $request );

			if( $savePersonValidate !== true ){

				return $savePersonValidate;

			}

			$meta = new Meta();
			$countries = new Countries();
			
			$personsId = trim( $request[ 'persons_id' ] );
			$personsName = trim( $request[ 'persons_name' ] );
			$personsBirth = trim( $request[ 'persons_birth' ] );
			$personsCountry = trim( $request[ 'persons_country' ] );
			$personsPhotoURL = trim( $request[ 'photo_url' ] );
			$personsPhotoDelete = trim( $request[ 'photo_delete' ] );
			
			$dbData = array(
				'persons_name' => $personsName
			);
			
			// menjuk a nevet a persons tablaba
			
			$this->db->query( "UPDATE persons SET persons_name = '".$personsName."' WHERE persons_id = " . $personsId );
			
			// szuletesi datum
			
			$meta->updateMeta( 'persons', $personsId, 'persons_birth', $personsBirth );
			
			// orszag(ok) - ha kitoltottuk
			
			if( $personsCountry != '' ){
				
				$countriesIDs = $countries->getCountriesIdByTitle( $personsCountry );
				
				// metaba mentjuk a $countriesIDs -t
				
				$meta->updateMeta( 'persons', $personsId, 'persons_country', '|' . implode( '|;|', $countriesIDs ) . '|' );
				
			}
			
			// foto
			
			if( isset( $_FILES[ 'photo' ] ) && isset( $_FILES[ 'photo' ][ 'tmp_name' ] ) && file_exists( $_FILES[ 'photo' ][ 'tmp_name' ] ) ){
				
				// ha van mar fotoja (update eseten), akkor azt toroljuk
				
				$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'persons' AND meta_table_id = ".$personsId." AND meta_key = 'photo_filename'" );
				
				if( $metaQuery->num_rows != 0 ){
					
					$metaRows = $metaQuery->fetch_assoc();
					
					unlink( 'uploads/' . $metaRows[ 'meta_value' ] );
					
				}
				
				// a file nevbe bekerul a timestamp, hogy ha fotot cserelunk, akkor a frontenden a bongeszo ne az elozot toltse be cache-bol
				
				$filename = 'photo-' . $personsId . '-' . time() . '.jpg';
				
				if( copy( $_FILES[ 'photo' ][ 'tmp_name' ], 'uploads/' . $filename ) ){
					
					// sikeresen felkerul a file, elmentjuk metaba a file nevet
					
					$meta->updateMeta( 'persons', $personsId, 'photo_filename', $filename );
					
				}
				
			}
			else if( $personsPhotoURL != '' ) {
				
				$getPhoto = file_get_contents( $personsPhotoURL );
				
				if( $getPhoto ){
					
					// sikerult letolteni a fotot
					
					$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'persons' AND meta_table_id = ".$personsId." AND meta_key = 'photo_filename'" );
					
					if( $metaQuery->num_rows != 0 ){
						
						// ha van mar fotoja (update eseten), akkor azt toroljuk
						
						$metaRows = $metaQuery->fetch_assoc();
						
						unlink( 'uploads/' . $metaRows[ 'meta_value' ] );
						
					}
					
					// a file nevbe bekerul a timestamp, hogy ha fotot cserelunk, akkor a frontenden a bongeszo ne az elozot toltse be cache-bol
					
					$filename = 'photo-' . $personsId . '-' . time() . '.jpg';
					
					if( file_put_contents( 'uploads/' . $filename, $getPhoto ) ){
						
						// sikeresen felkerul a file, elmentjuk metaba a file nevet
						
						$meta->updateMeta( 'persons', $personsId, 'photo_filename', $filename );
						
					}
					
				}
				
			}
			else if( $personsPhotoDelete == 'ok' ){
				
				// se fotot nem kapunk, se URL-t, viszont torolni kell a fent levo kepet
				
				$metaQuery = $this->db->query( "SELECT * FROM meta WHERE meta_table = 'persons' AND meta_table_id = ".$personsId." AND meta_key = 'photo_filename'" );
				
				if( $metaQuery->num_rows != 0 ){
					
					$metaRows = $metaQuery->fetch_assoc();
					
					unlink( 'uploads/' . $metaRows[ 'meta_value' ] );
					
					$meta->updateMeta( 'persons', $personsId, 'photo_filename', '' );
					
				}
				
			}
			
			$response = array(
				'status' => 'ok',
				'msg' => 'A személy adatai sikeresen módosítva'
			);
			
			return $response;
			
		}
		
	}
	
?>
