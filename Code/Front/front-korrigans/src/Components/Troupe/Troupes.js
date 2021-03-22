import './Troupes.css';
import TroupeList from './TroupesList';
import { useEffect, useState, useContext } from 'react';
import { TroupeAjoutee } from '../../App';

const Troupes = ({ handleClickTraining }) => {

    const [troupeJoueur, setTroupeJoueur] = useState([]);
    const context = useContext(TroupeAjoutee);

    const { connexion } = context;

    useEffect(() => {
      let isSubscribed = true;

      const fetchTroupeJoueur = async () => {
        
        const getTroupeJoueur = await fetch(
          'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/troupes-joueur/1', {
            credentials: 'include',
            mode: 'cors'
          })
              .then(res => {
                return res.json();
              })
              .then(result => {
                if(isSubscribed) {
                  setTroupeJoueur(result.troupesJoueur1);
                }
              })
              .catch((err) => {
                console.log(err);
              });
      }

      fetchTroupeJoueur(); 
      return () => (isSubscribed = false);   
    }, [connexion]);

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