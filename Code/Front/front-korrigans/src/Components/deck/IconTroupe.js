import './IconTroupe.css';

const IconTroupe = ({ level, troupe, onTroupeClick }) => {

    const image = "/images/troupes/" + troupe + ".png";
    const levelText = "Level " + level;

    return (
        <div onClick={(event) => onTroupeClick(event, troupe)} className="icon-troupe">
            <img src={image} />
            <span className="level">{levelText}</span>
        </div>
    );
}

export default IconTroupe;