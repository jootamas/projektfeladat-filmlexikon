<?php
	
	// api vegpont, peldanyositjuk az Api osztalyt es az action alapjan meghivjuk a megfelelo metodusat
	
	include( 'config.php' );
	
	$api = new Api();
	
	$response = array();

	switch( $request[ 'action' ] ){
		
		case 'list_movies':
			
			$response = $api->listMovies( $request[ 'search' ] );
			
		break;
		
		case 'get_movie':
			
			$response = $api->getMovie( $request[ 'movies_id' ] );
			
		break;
		
		case 'save_movie':
			
			$response = $api->saveMovie( $request );
			
		break;
		
		case 'delete_movie':
			
			$response = $api->deleteMovie( $request[ 'movies_id' ] );
			
		break;
		
		case 'list_persons':
			
			$response = $api->listPersons( $request[ 'search' ] );
			
		break;
		
		case 'get_person':
			
			$response = $api->getPerson( $request[ 'persons_id' ] );
			
		break;
		
		case 'save_person':
			
			$response = $api->savePerson( $request );
			
		break;
		
		default:
			
			$response = array(
				'status' => 'no',
				'msg' => 'Ismeretlen művelet'
			);
		
	}
	
	echo json_encode( $response );
	
?>

