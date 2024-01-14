import React, { useState, useEffect } from "react";

import { Link } from 'react-router-dom';

const PersonsList = () => {
	
	document.title = 'Személyek | Filmlexikon';
	
	const [ persons, setPersons ] = useState( [] );
	
	const listPersons = async () => {
		
		let form_data = new FormData();
		
		form_data.append( 'action', 'list_persons' );
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
				
				setPersons( data.persons );
				
			})
			.catch( ( err ) => {
				
				console.log( err.message );
				
			});
		
	}
	
	useEffect( () => {
		
		listPersons();
		
	}, []);
	
	return (
		<>
     	<div className="container list">
				
				<div className="row">
				
					<h1 className="mb-4">Személyek</h1>
					
					<div className="col-12 mb-4">
						
						<div className="input-group">
							<input type="text" name="search" className="form-control" placeholder="Keresés" onKeyUp={ listPersons } />
						</div>
						
					</div>
					
					{ persons.map( ( person ) => {
						
						return (
							
							<div className="col-6 col-md-3 col-xl-2" key={ `person` + person.persons_id }>
								
								<Link to={ `/person/` + person.persons_id }>
									
									<div className="card mb-3 text-center">
										<img src={ person.meta.photo_url } title={ person.persons_name } />
										<div className="card-body">
											<h5 className="card-title">{ person.persons_name }</h5>
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

export default PersonsList;

