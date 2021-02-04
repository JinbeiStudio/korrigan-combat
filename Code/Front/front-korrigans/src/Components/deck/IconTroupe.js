import './IconTroupe.css';

const IconTroupe = ({ level, troupe }) => {

    const image = "/images/troupes/" + troupe + ".png";
    const levelText = "Level " + level;

    return (
        <div className="icon-troupe">
            <img src={image} />
            <span className="level">{levelText}</span>
        </div>
    );

}

export default IconTroupe;