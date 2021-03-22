import Minuteur from '../Decks/Minuteur';
import './IconTroupe.css';

const IconTroupe = ({ level, troupe, onTroupeClick, quantite, time }) => {

    const image = `/images/troupes/${troupe}.png`;
    const levelText = `Level ${level}`;

    return (
        <div onClick={(event) => onTroupeClick(event, troupe)} className="icon-troupe">
            <img src={image} alt={troupe} alt={troupe} />
            <span className="level">{levelText}</span>
            {quantite ? <div className="quantiteTroupe"><span>{quantite}</span></div> : ""}
            {time ? <div className="tempsFormation" ><span><Minuteur time={time}/></span></div> : ""}
        </div>
    );
}

export default IconTroupe;