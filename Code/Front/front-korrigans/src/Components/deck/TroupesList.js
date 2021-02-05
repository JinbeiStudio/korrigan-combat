import './TroupesList.css';
import IconTroupe from './IconTroupe';
import { useState } from 'react';
import Popup from './Popup';


const TroupesList = ({ troupes }) => {
    const [popupState, setPopupState] = useState(false);
    const [popupTroupeState, setpopupTroupeState] = useState(troupes[0]);

    const togglePopup = (idTroupe) => {
        setPopupState(!popupState);  
        setpopupTroupeState(troupes.find(element => element.idTroupe == idTroupe));
    }
    const index = 0;
    return [
        <Popup key={index} popupOpen={popupState} onPopupClick={togglePopup} infos={popupTroupeState} />,
        troupes.map(data => {
            return <IconTroupe onTroupeClick={togglePopup} key={data.idTroupeJoueur} level={data.niveauTroupe} troupe={data.idTroupe} />
        })
    ];
}

export default TroupesList;