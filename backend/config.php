<?php

	// konfiguracio es kotelezo parameterek ellenorzese
	
	define( 'API_URL', '' );
	
	define( 'DB_HOST', '' );
	define( 'DB_USER', '' );
	define( 'DB_PW', '' );
	define( 'DB_DB', '' );
	
	define( 'API_KEY', 'NRxJQcZzB4ZKAbqTlN0R' );
	
	// osztalyok betoltese
	
	include( 'lib/class.db.php' );
	include( 'lib/class.api.php' );
	include( 'lib/class.categories.php' );
	include( 'lib/class.countries.php' );
	include( 'lib/class.meta.php' );
	include( 'lib/class.movies.php' );
	include( 'lib/class.persons.php' );
	
	// a valasz minden esetben JSON
	
	header( 'Content-Type: application/json; charset=utf-8' );
	
	// nincs kulon GET/POST/PUT/DELETE, minden keres POST-ban erkezeik, es a $_POST[ 'action' ] tartalmazza a kert muveletet
	
	if( ! isset( $request ) && isset( $_POST ) ){
		
		$request = $_POST;
		
	}
	
	if( empty( $request ) ){
		
		/**
		 * ures a request, hibauzenetet kuldunk vissza;
		 * minden valaszban van status, ha ez "ok", akkor sikeres a kert muvelet, ha "no", akkor sikertelen;
		 * ha a status "no", akkor biztosan van "msg", benne a hibauzenettel;
		 * ha a status "ok", akkor nem minden esetben van "msg", hanem pl. a filmek vagy szemelyek listajat kuldjuk vissza
		 **/
		$response = array(
			'status' => 'no',
			'msg' => 'Nem adtál meg egy paramétert sem'
		);
		
		// JSON-ben kiirjuk a valaszt
		
		echo json_encode( $response );
		
		exit;
		
	}
	
	if( ! isset( $request[ 'action' ] ) ){
		
		/**
		 * action kotelezo, kulonben nem tudjuk mit szeretne a kliens, lekerni, bekuldeni stb.;
		 * sikertelen muvelet, ezt a status-szal jelezzuk, msg-ben pedig a hibauzenet
		 **/
		$response = array(
			'status' => 'no',
			'msg' => 'Az action paraméter megadása kötelező'
		);
		
		// JSON-ben kiirjuk a valaszt
		
		echo json_encode( $response );
		
		exit;
		
	}
	
	// a kovetkezo action-ok eseten kotelezo az API key, megnezzuk van-e es helyes-e
	
	if( in_array( $request[ 'action' ], array( 'save_movie', 'delete_movie', 'save_person' ) ) ){
		
		if( ! isset( $request[ 'apiKey' ] ) ){
			
			/**
			 * API key kotelezo;
			 * sikertelen muvelet, ezt a status-szal jelezzuk, msg-ben pedig a hibauzenet
			 **/
			$response = array(
				'status' => 'no',
				'msg' => 'API key megadása kötelező'
			);
			
			// JSON-ben kiirjuk a valaszt
			
			echo json_encode( $response );
			
			exit;
			
		}
		
		if( $request[ 'apiKey' ] != API_KEY ){
			
			/**
			 * helytelen API key;
			 * sikertelen muvelet, ezt a status-szal jelezzuk, msg-ben pedig a hibauzenet
			 **/
			$response = array(
				'status' => 'no',
				'msg' => 'Helytelen API key'
			);
			
			// JSON-ben kiirjuk a valaszt
			
			echo json_encode( $response );
			
			exit;
			
		}
		
	}
	
?>
