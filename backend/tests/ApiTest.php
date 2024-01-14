<?php

// "imitalunk" egy valos lekerest, ha nincs action, akkor a config.php nem enged tovabb
$request = array( 'action' => 'list_movies' );

include( '../config.php' );

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
  private $api;
  public function __construct(string $name)
  {
    parent::__construct($name);

    $this->api = new Api();
  }

  public function testDb()
  {
    // adatbazis kapcsolat ellenorzese
    $db = new Db();

    $conn = $db->getConn();

    $this->assertEquals( 0, $conn->connect_errno );
    $this->assertEquals( 0, $conn->errno );

  }

  public function testListMovies()
  {
    // filmlista lekeresenek tesztelese

    // #1 osszes film lekerese, azaz nincs kereses
    $listMovies = $this->api->listMovies( '' );

    $this->assertIsArray( $listMovies[ 'movies' ] );
    $this->assertNotEmpty( $listMovies[ 'movies' ][ 0 ] );

    // #2 film kereses tesztelese, "a" beture keresunk a filmek kozott
    $listMovies = $this->api->listMovies( 'a' );

    $this->assertIsArray( $listMovies[ 'movies' ] );
    $this->assertNotEmpty( $listMovies[ 'movies' ][ 0 ] );
  }

  public function testGetMovie()
  {
    // egy film lekeresenek tesztelese

    // lekerjuk a filmlistat
    $listMovies = $this->api->listMovies( '' );

    // veletlenszeruen lekerjuk 5 film adatait

    for( $i=0 ; $i<5 ; $i++ ){

      $getMovie = $this->api->getMovie( $listMovies[ 'movies' ][ rand( 0, ( count( $listMovies[ 'movies' ] ) - 1 ) ) ][ 'movies_id' ] );

      // megkaptuk-e a vart tombot, illetve megvan-e a film cime
      $this->assertIsArray( $getMovie[ 'movies' ] );
      $this->assertNotEmpty( $getMovie[ 'movies' ][ 0 ][ 'movies_title' ] );

    }
  }

  public function testListPersons()
  {
    // szemelyek listaja lekeresenek tesztelese

    // #1 osszes szemely lekerese, azaz nincs kereses
    $listPersons = $this->api->listPersons( '' );

    $this->assertIsArray( $listPersons[ 'persons' ] );
    $this->assertNotEmpty( $listPersons[ 'persons' ][ 0 ] );

    // #2 szemely kereses tesztelese, "a" beture keresunk a szemelyek kozott
    $listPersons = $this->api->listPersons( 'a' );

    $this->assertIsArray( $listPersons[ 'persons' ] );
    $this->assertNotEmpty( $listPersons[ 'persons' ][ 0 ] );
  }

  public function testGetPerson()
  {
    // egy szemely lekeresenek tesztelese

    // lekerjuk a szemelyek listajat
    $listPersons = $this->api->listPersons( '' );

    // veletlenszeruen lekerjuk 5 szemely adatait

    for( $i=0 ; $i<5 ; $i++ ){

      $getPerson = $this->api->getPerson( $listPersons[ 'persons' ][ rand( 0, ( count( $listPersons[ 'persons' ] ) - 1 ) ) ][ 'persons_id' ] );

      // megkaptuk-e a vart tombot, illetve megvan-e a szemely neve
      $this->assertIsArray( $getPerson[ 'persons' ] );
      $this->assertNotEmpty( $getPerson[ 'persons' ][ 0 ][ 'persons_name' ] );

    }
  }

}
