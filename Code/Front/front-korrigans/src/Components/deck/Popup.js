import './Popup.css';
import IconTroupe from './IconTroupe';

const Popup = ({ popupOpen, onPopupClick, infos, stats }) => {

    const title_bar_popup = "/images/title_bar.png";

    return (
        popupOpen && (<>
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
                <IconTroupe className="troupe-icon" level={infos.niveauTroupe} troupe={infos.idTroupe} />
            </div>
        </>)
    );
}

export default Popup;