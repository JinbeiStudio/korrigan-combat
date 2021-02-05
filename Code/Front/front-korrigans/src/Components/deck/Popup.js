import './Popup.css';
import IconTroupe from './IconTroupe';

const Popup = ({ popupOpen, onPopupClick, infos }) => {

    const title_bar_popup = "/images/title_bar.png";

    const troupes = {"listeTroupes":
                        [{"idTroupe":"1",
                          "nomTroupe":"Soldat",
                          "niveauDebloquage":"1",
                          "poids":"1",
                          "tempsFormation":"0.25",
                          "capaciteTransport":"1",
                          "portee":"1",
                          "vitesse":"1"},{"idTroupe":"2","nomTroupe":"Archer","niveauDebloquage":"1","poids":"2","tempsFormation":"0.5","capaciteTransport":"1","portee":"2","vitesse":"1"},{"idTroupe":"3","nomTroupe":"Lancier","niveauDebloquage":"1","poids":"2","tempsFormation":"0.5","capaciteTransport":"1","portee":"2","vitesse":"1"},{"idTroupe":"4","nomTroupe":"Aigle","niveauDebloquage":"1","poids":"3","tempsFormation":"3","capaciteTransport":"1","portee":"2","vitesse":"3"},{"idTroupe":"5","nomTroupe":"Ours","niveauDebloquage":"1","poids":"4","tempsFormation":"3","capaciteTransport":"1","portee":"1","vitesse":"1"},{"idTroupe":"6","nomTroupe":"Araign\u00e9e","niveauDebloquage":"1","poids":"4","tempsFormation":"10","capaciteTransport":"1","portee":"2","vitesse":"2"},{"idTroupe":"7","nomTroupe":"Dragon","niveauDebloquage":"1","poids":"5","tempsFormation":"13","capaciteTransport":"1","portee":"3","vitesse":"1"},{"idTroupe":"8","nomTroupe":"Lanceur de Foudre","niveauDebloquage":"1","poids":"3","tempsFormation":"15","capaciteTransport":"1","portee":"3","vitesse":"1"}]};

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
                    <h2>{}</h2>
                </div>
                <IconTroupe level={infos.niveauTroupe} troupe={infos.idTroupe} />
            </div>
        </>)
    );
}

export default Popup;