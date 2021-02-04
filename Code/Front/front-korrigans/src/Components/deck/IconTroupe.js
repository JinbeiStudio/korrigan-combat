import './IconTroupe.css';

const IconTroupe = ({ level, troupe }) => {

    const image = "/images/troupes/" + troupe + ".png";

    return (
        <div className="icon-troupe">
            <img src={image} />
        </div>
    );

}

export default IconTroupe;