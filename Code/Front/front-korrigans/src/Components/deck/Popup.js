import './Popup.css';

const Popup = ({ popupOpen, onPopupClick, infos }) => {
    console.log("ok");
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
                <h1>troupes : {infos.idTroupe}</h1>
            </div>
        </>)
    );
}

export default Popup;