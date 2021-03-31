import { useState, useEffect } from 'react';

function Popup(props) {

    const [combatT1, setCombatT1] = useState([]);
    useEffect(() => {
        const fetchCombatT1 = async () => {
        
            const getCombatT1 = await fetch(
              `https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/combat-tour1/attaquant/1/defenseur/${props.id_adversaire}`, {
                credentials: 'include',
              })
                  .then(res => {
                    return res.json();
                  })
                  .then(result => {
                    setCombatT1(result.combatT1);
                  })
                  .catch((err) => {
                    console.log(err);
                  });
        }
        fetchCombatT1();
    }, [])
    let resT1;
    if((combatT1.perdu = false) && (combatT1.gagne = true)){
         resT1 =
            <div className="popup">
                <div className="popup-inner">
                    <p>{props.id_adversaire}</p>
                    <p>Vous avez gagné !</p>
                    <p>Or gagné : {combatT1.orGagne}</p>
                    <p>Ressources gagnées : {combatT1.ressourceGagne}</p>
                    <p>Troupes perdues :</p>
                    {combatT1.map(j=>
                        <div>
                            <p>{j.quantite}</p>
                        </div>    
                    )}
                    <button className="surrend-btn" onClick={() => props.setTrigger(false)}>Quitter</button>
                    { props.children }
                </div>
            </div>
            
    }else if((combatT1.perdu = true) && (combatT1.gagne = false)){
         resT1 = 
            <div className="popup">
                <div className="popup-inner">
                    <p>{props.id_adversaire}</p>
                    <p>Vous avez perdu !</p>
                    <p>Or perdu : {combatT1.orPerdu}</p>
                    <p>Ressources gagnées : {combatT1.ressourcePerdu}</p>
                    <p>Troupes perdues :</p>
                    {combatT1.map(j=>
                        <div>
                            <p>{j.quantite}</p>
                        </div>    
                    )}
                    <button className="surrend-btn" onClick={() => props.setTrigger(false)}>Quitter</button>
                    { props.children }
                </div>
            </div>

    }else if((combatT1.perdu = false) && (combatT1.gagne = false)){
         resT1 =
            <div className="popup">
                <div className="popup-inner">
                    <p>{props.id_adversaire}</p>
                    <button className="next-btn">Round suivant</button>
                    <button className="surrend-btn" onClick={() => props.setTrigger(false)}>Abandonner</button>
                    { props.children }
                </div>
            </div> 
    }else{
        resT1 =
            <div className="popup">
                <div className="popup-inner">
                    <p>{props.id_adversaire}</p>
                    <button className="next-btn">Round suivant</button>
                    <button className="surrend-btn" onClick={() => props.setTrigger(false)}>Abandonner</button>
                    { props.children }
                </div>
            </div> 
    }

    return (props.trigger) ? (
        <div>
            {resT1}
        </div>
    ) : "";
}

export default Popup
