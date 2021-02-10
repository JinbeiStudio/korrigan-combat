import Troupes from './Components/Troupe/Troupes';
import './App.css';
import Navbar from './Components/Navbar/Navbar';
import NavFooter from './Components/Footer/NavFooter';
import Deck from './Components/Decks/Deck';
import Opponents from './Components/Opponents/Opponents'
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";
import { useState } from 'react';

function App() {

  const [troupeToAdd, setTroupeToAdd] = useState([]);

  const handleClickTraining = (event, troupe, nbTroupes) => {
      let dataTroupe = troupe;
      dataTroupe.nbTroupes = nbTroupes;

      setTroupeToAdd(dataTroupe);
      event.preventDefault();
  }

  return (
    <Router>
    <>
      <div className="background" style={{ background: `url('${process.env.PUBLIC_URL}/images/background.jpg')` }}>
      </div>
      <div className="screen">
            <Navbar />
            <Switch>
              <Route path="/">
                <Deck troupeToAdd={troupeToAdd} />
                <Troupes handleClickTraining={handleClickTraining} />
              </Route>
              <Route path="/Opponents">
                <Opponents />
              </Route>
            </Switch>
            <NavFooter />
      </div>
    </>
    </Router>
  );
}

export default App;
