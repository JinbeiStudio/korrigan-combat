import React from 'react';
import Clock from './Clock.js';
import './Navbar.css';

class Navbar extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            gold: "",
            xp: "" 
        }
    }
    /*componentDidMount() {
      fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0", {credentials: 'same-origin'})
      .then(fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/joueur/1",{credentials: 'same-origin'})
        .then(res => res.json())
        .then(
          (result) => {
            this.setState({
              isLoaded: true,
              gold: result.gold,
              xp: result.xp
            });
          },
          (error) => {
            this.setState({
              isLoaded: true,
              error
            });
          }
        )
      )
      
    }*/
    render() {
    /*const { error, isLoaded, gold, xp } = this.state;
    if (error) {
      return <div>Erreur : {error.message}</div>;
    } else if (!isLoaded) {
      return <div>Chargement…</div>;
    } else {*/
      return (
        <nav className="NavbarItems">
          <Clock />
          <div className="bloc-nav-elements">
            <div className="bloc-gold">
              <img src="/images/glob_mini-gold.png" className="icon-gold"/>
              <p>73,803</p>
            </div>
            <div className="bloc-ligue-point">
              <img src="/images/glob_mini-crown-power.png" className="icon-ligue"/>
              <p>1340</p>
            </div>
            <progress className="bloc-xp" max="100" value="70" />
          </div>
        </nav>
      );
    }
  }


export default Navbar;
