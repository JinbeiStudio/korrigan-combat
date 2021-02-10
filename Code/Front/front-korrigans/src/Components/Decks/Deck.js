import './Deck.css';
import Tabs from './Tabs';

const Deck = ({ troupeToAdd }) => {
    console.log(troupeToAdd);
    const background_top = "/images/deck/background-top.png";
    const background_bottom = "/images/deck/background-bottom.png";

    return (
        <div className="deck" style={{ background: `url('${process.env.PUBLIC_URL}/images/deck/background-deck.jpg')` }}>
            <img className="bg-top" src={background_top} alt="" />
            <img className="bg-bottom" src={background_bottom} alt="" />
            <Tabs />
        </div>
    );
}

export default Deck;