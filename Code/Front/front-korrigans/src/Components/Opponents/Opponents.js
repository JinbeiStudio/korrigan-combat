import React from 'react';
import './Opponents.css';

class Opponents extends React.Component{
    state = {
        loading: true,
        adversaire: []
    };
    async componentDidMount() {
        const url = "https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/adversaire/5";
        const response = await fetch(url, {credentials: 'include'});
        const data = await response.json();
        this.setState({adversaire: data.adversaire})
    }
    render(){
        return (
            <div>
                {this.state.adversaire.map(i=> (
                    <div className="bloc-adversaire">
                        <p>{i.login}</p>
                        <p>{i.level}</p>
                        <button className="button-adversaire" style={{ background: `url('${process.env.PUBLIC_URL}/images/adversaires/glob_small-button2..png')` }}>Affronter</button>
                    </div>
                ))}
            </div>
        );
    }
}

export default Opponents;