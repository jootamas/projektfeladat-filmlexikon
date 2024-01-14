import React, { useState, useEffect } from "react";

import { Link } from 'react-router-dom';

const Home = () => {
	
	document.title = 'Filmek | Filmlexikon';
	
	const [ movies, setMovies ] = useState( [] );
	
	const listMovies = async () => {
		
		// filmek listajanak letoltese
		
		let form_data = new FormData();
		
		form_data.append( 'action', 'list_movies' );
		form_data.append( 'search', document.querySelector( '[name=search]' ).value );
		
		const getConfig = await fetch( process.env.PUBLIC_URL + '/filmlexikon.config.json' );
		const config = await getConfig.json();
		
		fetch(
			config.apiEndpoint,
			{
				method: 'POST',
				body: form_data
			}
		)
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				
				setMovies( data.movies );
				
			})
			.catch( ( err ) => {
				
				console.log( err.message );
				
			});
		
	}
	
  useEffect(() => {
  	
		listMovies();
		
  }, []);
  
	return (
		<>
     	<div className="container list">
				
				<div className="row">
				
					<h1 className="mb-4">Filmek</h1>
					
					<div className="col-12 mb-4">
						
						<div className="input-group">
							<input type="text" name="search" className="form-control" placeholder="Keresés" onKeyUp={ listMovies } />
						</div>
						
					</div>
					
					{ movies.map( ( movie ) => {
						
						return (
							
							<div className="col-6 col-md-3 col-xl-2" key={ `movie` + movie.movies_id }>
								
								<Link to={ `/movie/` + movie.movies_id }>
									
									<div className="card mb-3 text-center">
										<img src={ movie.meta.poster_url } title={ movie.movies_title } />
										<div className="card-body">
											<h5 className="card-title">{ movie.movies_title }</h5>
											<p className="card-text">{ movie.meta.countries_and_categories_string }</p>
											<p className="card-text">{ movie.movies_year }</p>
										</div>
									</div>
									
								</Link>
								
							</div>
							
						);
						
					})}
					
				</div>
				
			</div>
			
		</>
	);
	
};

export default Home;

