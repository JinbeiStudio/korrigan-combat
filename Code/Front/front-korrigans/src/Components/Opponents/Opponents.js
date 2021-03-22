import React from 'react';
import './Opponents.css';

class Opponents extends React.Component{
    render(){
        return (
            <div className="bloc-adversaire">
                <p>Pseudo Joueur</p>
                <p>Niveau</p>
                <button className="button-adversaire" style={{ background: `url('${process.env.PUBLIC_URL}/images/adversaires/glob_small-button2..png')` }}>Affronter</button>
            </div>
        );
    }
}

export default Opponents;