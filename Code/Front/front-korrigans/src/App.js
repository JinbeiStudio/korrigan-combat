import Troupes from './Components/Troupe/Troupes';
import './App.css';
import Navbar from './Components/Navbar/Navbar';
import NavFooter from './Components/Footer/NavFooter';
import Deck from './Components/Decks/Deck';
import Opponents from './Components/Opponents/Opponents';
import Accueil from './Components/Accueil/Accueil';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";
import { useState, useEffect, createContext } from 'react';

function App() {

  const [troupeToAdd, setTroupeToAdd] = useState([]);
  const [isAdd, setIsAdd] = useState(true);
  const [connexion, setConnexion] = useState(false)

  const handleClickTraining = (event, troupe, nbTroupes) => {
      let dataTroupe = troupe;
      dataTroupe.nbTroupes = nbTroupes;

      setTroupeToAdd(dataTroupe);
      setIsAdd(false);
      event.preventDefault();
  }

  useEffect(() => {
    const getConnexion = async () => {
      const connexion = await fetch(
        'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0')
            .then( res => {
              localStorage.setItem('connexion', true);
              setConnexion(true);
            })
            .catch((err) => {
              console.log(err);
            });
    }

    getConnexion();
  }, []);


  return (
    <TroupeAjoutee.Provider value={{setIsAdd, connexion, isAdd, troupeToAdd}}>
      <Router>
        <div className="background" style={{ background: `url('${process.env.PUBLIC_URL}/images/background.jpg')` }}>
        </div>
        <div className="screen">
              <Navbar />
              <Switch>
                <Route exact path="/">
                  <Accueil />
                </Route>
                <Route path="/Deck">
                  <Deck />
                  <Troupes handleClickTraining={handleClickTraining} />
                </Route>
                <Route path="/Opponents">
                  <Opponents />
                </Route>
              </Switch>
              <NavFooter />
        </div>
      </Router>
    </TroupeAjoutee.Provider>
  );
}

export default App;
export const TroupeAjoutee = createContext();