import React from 'react';
import './Opponents.css';
import Popup from './Popup';
import { useState, useEffect } from 'react';


const Opponents = ()=>{
    
    const [buttonPopup, setButtonPopup] = useState(false);
    const [loading, setLoading] = useState(true);
    const [adversaire, setAdversaire] = useState([]);
    const [id_adversaire, setIdAdversaire] = useState(0);

    useEffect(() => {
        const fetchAdversaire = async () => {
        
            const getAdversaire = await fetch(
              'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/adversaire/1 ', {
                credentials: 'include',
              })
                  .then(res => {
                    return res.json();
                  })
                  .then(result => {
                    setAdversaire(result.adversaire);
                  })
                  .catch((err) => {
                    console.log(err);
                  });
        }
        fetchAdversaire();
    }, [])
    return (
            <div>
                {adversaire.map(i=> (
                    <div className="bloc-adversaire">
                        <p>{i.login}</p>
                        <p>Niveau : {i.level}</p>
                        <button onClick={()=> {setButtonPopup(true); setIdAdversaire(i.id);}} className="button-adversaire" style={{ background: `url('${process.env.PUBLIC_URL}/images/adversaires/glob_small-button2..png')` }}>Affronter</button>
                    </div>
                ))}
                <Popup trigger={buttonPopup} setTrigger={setButtonPopup} id_adversaire={id_adversaire} />
            </div>
            
        );
}

export default Opponents;