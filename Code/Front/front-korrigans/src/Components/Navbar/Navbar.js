import React from 'react';
import Clock from './Clock.js';
import './Navbar.css';

class Navbar extends React.Component{
    state = {
      loading: true,
      joueur: null
    };

    async componentDidMount() {
      const url = "https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/joueur/1";
      const response = await fetch(url, {credentials: 'include'});
      const data = await response.json();
      this.setState({joueur: data.joueur[0]})
    }
    render() {
      return (
        <nav className="NavbarItems">
          <Clock />
          <div className="bloc-nav-elements">
            {!this.state.joueur ? (
              <div>Chargement..</div>
            ) : (
              <div className="bloc-gold">
                <img src="/images/glob_mini-gold.png" className="icon-gold"/>
                <p>{this.state.joueur.gold}</p>
              </div>
            )}
            {!this.state.joueur ? (
              <div>Chargement..</div>
            ) : (
              <div className="bloc-ligue-point">
                <img src="/images/glob_mini-crown-power.png" className="icon-ligue"/>
                <p>{this.state.joueur.xp}</p>
              </div>          
            )}
            <progress className="bloc-xp" max="100" value="70" />
          </div>
        </nav>
      );
    }
}

export default Navbar;

