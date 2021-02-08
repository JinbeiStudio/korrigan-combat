import './Tab.css';

const Tab = ({ title, active }) => {
    
    const onglet = "/images/deck/bouton-deck.png";
    return (
        <li className="tab-item"
            style={{
                filter: active ? 'brightness(1)' : 'brightness(0.5)',
            }}>
            <img src={onglet} alt={"bouton"} />
            <p>{title}</p>
        </li>
    )
}

export default Tab;