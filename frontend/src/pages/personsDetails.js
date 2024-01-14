import React, { useState, useEffect } from "react";

import { Route, Link, useParams } from 'react-router-dom';

const PersonsDetails = () => {
	
	let { personsId } = useParams();
	
	const [ persons, setPersons ] = useState( [] );
	
	const getPerson = async () => {
		
		let form_data = new FormData();
		
		form_data.append( 'action', 'get_person' );
		form_data.append( 'persons_id', personsId );
		
		// lekerjuk a szemely adatait
		
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
				
				setPersons( data.persons );
				
				document.title = data.persons[ 0 ].persons_name + ' | Filmlexikon';
				
			})
			.catch( ( err ) => {
				
				console.log( err.message );
				
			});
		
	}
	
	useEffect( () => {
		
		getPerson();
		
	}, []);
	
	return (
		<>
     	<div className="container details">
				
				{ persons.map( ( person ) => {
					
					return (
						
						<div className="row" key={ `person` + person.persons_id }>
							
							<div className="col-12 detailsTitle">
								
								<h1>{ person.persons_name }</h1>
								
								<p>{ person.meta.persons_title }</p>
								
							</div>
							<div className="col-4 detailsPoster">
								
								<div className="img">
									<img src={ person.meta.photo_url } title={ person.persons_name } />
								</div>
								
							</div>
							<div className="col-8 detailsTxt">
								
								<h3>Filmjei:</h3>
								
								<div className="row">
									
									{ person.movies_list.map( (movie) => {
										
										return (
											
											<div className="col-3" key={ `movie` + movie.movies_id }>
												
												<Link to={ `/movie/` + movie.movies_id }>
													
													<div className="card mb-3 text-center">
														<img src={ movie.meta.poster_url } />
														<div className="card-body">
															<h5 className="card-title">{ movie.movies_title }</h5>
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

export default PersonsDetails;

