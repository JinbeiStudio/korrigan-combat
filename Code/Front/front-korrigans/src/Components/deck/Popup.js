import './Popup.css';
import IconTroupe from './IconTroupe';
import CounterTroupe from './CounterTroupe';
import TrainButton from './TrainButton';

const Popup = ({ popupOpen, onPopupClick, infos, stats }) => {

    const disabledClick = (event, idTroupe) => {
        event.stopPropagation();
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
                <IconTroupe onTroupeClick={disabledClick} className="troupe-icon" level={infos.niveauTroupe} troupe={infos.idTroupe} />
                <div className="bottom-popup">
                    <CounterTroupe />
                    <TrainButton text="Entrainer" />
                </div>
            </div>
        </>)
    );
}

export default Popup;