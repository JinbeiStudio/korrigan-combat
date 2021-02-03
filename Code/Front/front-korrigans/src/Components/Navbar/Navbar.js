import React from 'react';
import Clock from './Clock.js';

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
    componentDidMount() {
      fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0")
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
      }
    render() {
    const { error, isLoaded, gold, xp } = this.state;
    if (error) {
      return <div>Erreur : {error.message}</div>;
    } else if (!isLoaded) {
      return <div>Chargement…</div>;
    } else {
      return (
        <nav className="NavbarItems">
            <Clock />
            <div className="bloc-gold">
                <p>{gold}</p>
            </div>
            <div className="bloc-ligue-point">
            
            </div>
            <div className="bloc-xp">
                <p>{xp}</p>
            </div>
        </nav>
      );
    }
  }
}

export default Navbar;
