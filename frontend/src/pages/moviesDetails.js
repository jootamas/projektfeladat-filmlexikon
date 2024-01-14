import React, { useState, useEffect } from "react";

import { Route, Link, useParams } from 'react-router-dom';

const MoviesDetails = () => {
	
	let { moviesId } = useParams();
	
	const [ movies, setMovies ] = useState( [] );
	
	const getMovie = async () => {
		
		let form_data = new FormData();
		
		form_data.append( 'action', 'get_movie' );
		form_data.append( 'movies_id', moviesId );
		
		// film adatainak lekerese
		
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
				
				// egy egy elemu tomb a valasz, ez alapjan modositjuk az oldal cimet
				
				document.title = data.movies[ 0 ].movies_title + ' | Filmlexikon';
				
			})
			.catch( ( err ) => {
				
				console.log( err.message );
				
			});
		
	}
	
	useEffect( () => {
		
		getMovie();
		
	}, []);
	
	return (
		<>
     	<div className="container details">
				
				{ movies.map( ( movie ) => {
					
					let director = JSON.parse( movie.meta.director );
					
					return (
						
						<div className="row" key={ `movie` + movie.movies_id }>
							
							<div className="col-12 detailsTitle">
								
								<h1>{ movie.movies_title }</h1>
								{ movie.movies_title_original != '' ? <h2>{ movie.movies_title_original }</h2> : '' }
								
								<p>{ movie.meta.countries_and_categories_string }</p>
								
							</div>
							<div className="col-md-12 col-lg-4 mb-4 detailsPoster">
								
								<div className="img">
									<img src={ movie.meta.poster_url } title={ movie.movies_title } />
								</div>
								
							</div>
							<div className="col-md-12 col-lg-8 detailsTxt">
								
								{ movie.meta.plot != '' ? <p>{ movie.meta.plot }</p> : '' }
								
								<h3>Rendező:</h3>
								
								<p><Link to={ `/person/` + director.persons_id }>{ director.persons_name }</Link></p>
								
								<h3>Szereplők:</h3>
								
								<div className="row">
									
									{ movie.actors_list.map( (person) => {
										
										return (
										
										<div className="col-sm-6 col-md-4 col-lg-3" key={ `person` + person.person.persons_id }>
											
											<Link to={ `/person/` + person.person.persons_id }>
												
												<div className="card mb-3 text-center">
													<img src={ person.person.meta.photo_url } />
													<div className="card-body">
														<h5 className="card-title">{ person.person.persons_name }</h5>
														<p className="card-text">{ person.character }</p>
													</div>
												</div>
												
											</Link>
											
										</div>
										
										)
										
									})}
									
								</div>
								
							</div>
							
						</div>
					);
				})}
				
			</div>
		</>
	);
};

export default MoviesDetails;

