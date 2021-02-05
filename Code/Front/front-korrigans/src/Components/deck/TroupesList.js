import './TroupesList.css';
import IconTroupe from './IconTroupe';
import { useState } from 'react';
import Popup from './Popup';


const TroupesList = ({ troupes }) => {
    const all_troupes = {"listeTroupes":
                        [{"idTroupe":"1",
                          "nomTroupe":"Soldat",
                          "niveauDebloquage":"1",
                          "poids":"1",
                          "tempsFormation":"0.25",
                          "capaciteTransport":"1",
                          "portee":"1",
                          "vitesse":"1"},{"idTroupe":"2","nomTroupe":"Archer","niveauDebloquage":"1","poids":"2","tempsFormation":"0.5","capaciteTransport":"1","portee":"2","vitesse":"1"},{"idTroupe":"3","nomTroupe":"Lancier","niveauDebloquage":"1","poids":"2","tempsFormation":"0.5","capaciteTransport":"1","portee":"2","vitesse":"1"},{"idTroupe":"4","nomTroupe":"Aigle","niveauDebloquage":"1","poids":"3","tempsFormation":"3","capaciteTransport":"1","portee":"2","vitesse":"3"},{"idTroupe":"5","nomTroupe":"Ours","niveauDebloquage":"1","poids":"4","tempsFormation":"3","capaciteTransport":"1","portee":"1","vitesse":"1"},{"idTroupe":"6","nomTroupe":"Araign\u00e9e","niveauDebloquage":"1","poids":"4","tempsFormation":"10","capaciteTransport":"1","portee":"2","vitesse":"2"},{"idTroupe":"7","nomTroupe":"Dragon","niveauDebloquage":"1","poids":"5","tempsFormation":"13","capaciteTransport":"1","portee":"3","vitesse":"1"},{"idTroupe":"8","nomTroupe":"Lanceur de Foudre","niveauDebloquage":"1","poids":"3","tempsFormation":"15","capaciteTransport":"1","portee":"3","vitesse":"1"}]};

    const [popupState, setPopupState] = useState(false);
    const [popupTroupeState, setpopupTroupeState] = useState(troupes[0]);
    const [statistiquesState, setStatistiqueState] = useState(all_troupes.listeTroupes[0]);

    const togglePopup = (idTroupe) => {
        setPopupState(!popupState);  
        setpopupTroupeState(troupes.find(element => element.idTroupe == idTroupe));

        setStatistiqueState(all_troupes.listeTroupes.find(element => element.idTroupe == idTroupe));
        console.log(statistiquesState);
    }

    const index = 0;
    return [
        <Popup key={index} popupOpen={popupState} onPopupClick={togglePopup} infos={popupTroupeState} stats={statistiquesState} />,
        troupes.map(data => {
            return <IconTroupe onTroupeClick={togglePopup} key={data.idTroupeJoueur} level={data.niveauTroupe} troupe={data.idTroupe} />
        })
    ];
}

export default TroupesList;