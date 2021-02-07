import './Popup.css';
import IconTroupe from './IconTroupe';
import CounterTroupe from './CounterTroupe';
import TrainButton from './TrainButton';
import { useState } from 'react';

const Popup = ({ popupOpen, onPopupClick, infos, stats }) => {

    const [nbTroupe, setNbTroupe] = useState(1);

    const disabledClick = (event, idTroupe) => {
        event.stopPropagation();
    }

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

    const title_bar_popup = "/images/title_bar.png";

    return (
        popupOpen && (
        <>
            <div 
                style={{
                    display: (popupOpen) ? 'block' : 'none',
                }} 
                className="popup"
                onClick={() => onPopupClick()}>
            </div>
            <div 
                style={{
                    display: (popupOpen) ? 'block' : 'none',
                }} 
                className="popup-inner">
                <div>
                    <img className="title-bar" src={title_bar_popup} />
                    <h2 className="troupe-name">{stats.nomTroupe}</h2>
                </div>
                <div className="middle-popup">
                    <IconTroupe onTroupeClick={disabledClick} className="troupe-icon" level={infos.niveauTroupe} troupe={infos.idTroupe} />
                </div>
                <div className="bottom-popup">
                    <CounterTroupe counter={nbTroupe} add={addTroupe} remove={removeTroupe} />
                    <TrainButton text="Entrainer" />
                </div>
            </div>
        </>)
    );
}

export default Popup;