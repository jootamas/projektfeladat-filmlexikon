import './App.scss';

import { Link } from 'react-router-dom';

import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

import Home from './pages/home';
import MoviesDetails from './pages/moviesDetails';
import PersonsList from './pages/personsList';
import PersonsDetails from './pages/personsDetails';

function App() {
  return (
    <div className="App">
      <header>
      	<div className="container">
      		<div className="row">
						<div className="col-12 col-lg-6">
	      			<Link to="/" className="logo">Filmlexikon</Link>
		      	</div>
						<div className="col-12 col-lg-6">
			  			<nav>
								<ul>
									<li>
										<Link to="/">Filmek</Link>
									</li>
									<li>
										<Link to="/persons">Személyek</Link>
									</li>
								</ul>
							</nav>
		      	</div>
		    	</div>
      	</div>
      </header>
      <main>
				<Routes>
					<Route path='/' element={<Home />} />
					<Route path='/movie/:moviesId' element={<MoviesDetails />} />
					<Route path='/persons' element={<PersonsList />} />
					<Route path='/person/:personsId' element={<PersonsDetails />} />
				</Routes>
      </main>
    </div>
  );
}

export default App;
