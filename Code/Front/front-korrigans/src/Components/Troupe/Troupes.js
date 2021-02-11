import './Troupes.css';
import TroupeList from './TroupesList';
import { useEffect, useState } from 'react';

const Troupes = ({ handleClickTraining }) => {

    const [troupeJoueur, setTroupeJoueur] = useState([]);

    useEffect(() => {

      const fetchTroupeJoueur = async () => {

        const connexion = await fetch(
          'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0', {
            credentials: 'include'
          })
              .then( res => {
                localStorage.setItem('connexion', true);
              })
              .catch((err) => {
                console.log(err);
              });
      
        const getTroupeJoueur = await fetch(
          'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/troupes-joueur/1', {
            credentials: 'include',
            mode: 'cors'
          })
              .then(res => {
                return res.json();
              })
              .then(result => {
                setTroupeJoueur(result.troupesJoueur1);
              })
              .catch((err) => {
                console.log(err);
              });
      }

      fetchTroupeJoueur();    
    }, []);

    return (
        <div className="troupes-disponibles">
            <div className="titre-troupes">
                <p>Troupes débloquées</p>
            </div>
            <div className="troupes">
                <TroupeList handleClickTraining={handleClickTraining} troupesJoueur={troupeJoueur}/>
            </div>
        </div>
    );
};

export default Troupes;

    // Troupes du joueur 1
    /*
    const troupes = {"troupesJoueur1":
                        [{
                          "idTroupeJoueur":"1",
                          "idTroupe":"1",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"},
                        {
                          "idTroupeJoueur":"2",
                          "idTroupe":"2",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"3",
                          "idTroupe":"3",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"7",
                          "idTroupe":"7",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"4",
                          "idTroupe":"4",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"5",
                          "idTroupe":"5",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"6",
                          "idTroupe":"6",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"8",
                          "idTroupe":"8",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        }]
                    };*/