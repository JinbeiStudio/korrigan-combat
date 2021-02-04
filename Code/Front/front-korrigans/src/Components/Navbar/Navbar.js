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
            <div className="bloc-gold">
              <img src="/images/adv_gold-icon.png" className="icon-gold"/>
              <p>gold</p>
            </div>
            <div className="bloc-ligue-point">
              <p>ligue points</p>
            </div>
            <div className="bloc-xp">
              <p>xp</p>
            </div>
        </nav>
      );
    }
  }


export default Navbar;
