import './TroupesList.css';
import IconTroupe from './IconTroupe';
import { TroupeAjoutee } from '../../App';
import { useState, useEffect, useContext } from 'react';
import Popup from './Popup';
import IconVide from './IconVide';


const TroupesList = ({ troupesJoueur, handleClickTraining }) => {

    const maxTroupeDeck = 8;
    const [popupState, setPopupState] = useState(false);
    const [caracteristiquesTroupes, setCaracteristiquesTroupes] = useState([])
    const [popupTroupeState, setpopupTroupeState] = useState([]);
    const [statistiquesState, setStatistiqueState] = useState([]);
    const [nbTroupe, setNbTroupe] = useState(1);
    const context = useContext(TroupeAjoutee);

    const { connexion } = context;

    useEffect(() => {
        let isSubscribed = true;

        const fetchTroupe = async () => {
            const getTroupe = await fetch(
                'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/troupe', {
                credentials: 'include'
            })
                .then(res => {
                return res.text();
                })
                .then(result => {
                    if(isSubscribed) {
                        setCaracteristiquesTroupes(result.listeTroupes);
                    }    
                })
                .catch((err) => {
                console.log(err);
                });
        }

        fetchTroupe();
        return () => (isSubscribed = false);
    }, [connexion]);

    const addTroupe = () => {
        if (nbTroupe < 50) {
            let count = nbTroupe + 1;
            setNbTroupe(count);
        }
    }

    const removeTroupe = () => {
        if (nbTroupe > 1) {
            let count = nbTroupe - 1;
            setNbTroupe(count);
        }
    }

    const togglePopup = (event, idTroupe) => {
        setPopupState(!popupState);  
        setpopupTroupeState(troupesJoueur.find(element => element.idTroupe === idTroupe));
        setNbTroupe(1);
        setStatistiqueState(caracteristiquesTroupes.find(element => element.idTroupe === idTroupe));
    }

    let tabIconsVides = []
    const getIconsVides = () => {
        let nbIconsVides = maxTroupeDeck - troupesJoueur.length;
        let iterator = 0;
        
        while(iterator < nbIconsVides) {
            // pour avoir une clé aléatoire
            tabIconsVides.push(Math.random() * 20);
            iterator++;
        }
    }

    getIconsVides();

    const index = 9652;
    return [
        <Popup key={index} 
               popupOpen={popupState} 
               nbTroupe={nbTroupe} 
               addTroupe={addTroupe} 
               removeTroupe={removeTroupe} 
               onPopupClick={togglePopup} 
               infos={popupTroupeState} 
               stats={statistiquesState} 
               handleClickTraining={handleClickTraining}
        />,
        troupesJoueur.map(data => {
            return <IconTroupe onTroupeClick={togglePopup} key={data.idTroupeJoueur} level={data.niveauTroupe} troupe={data.idTroupe} />
        }),
        tabIconsVides.map(data => {
            return <IconVide key={data} />
        })
    ];
}

export default TroupesList;