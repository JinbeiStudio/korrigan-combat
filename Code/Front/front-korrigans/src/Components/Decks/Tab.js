import './Tab.css';

const Tab = ({ title, active, formationEnCours, handleClickTab }) => {

    const onglet = "/images/deck/bouton-deck.png";
    return (
        <li onClick={(event) => handleClickTab(event, title)}
            className="tab-item"
            style={{
                pointerEvents: 'auto',
                filter: active ? 'brightness(1)' : 'brightness(0.5)',
            }}>
            <img src={onglet} alt={"bouton"} />
            <p>{title}</p>
        </li>
    )
}

export default Tab;