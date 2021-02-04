const IconTroupe = ({ level, troupe }) => {

    const image = "/images/troupes/" + troupe + ".png";

    return (
        <>
            <img src={image} />
        </>
    );

}

export default IconTroupe;