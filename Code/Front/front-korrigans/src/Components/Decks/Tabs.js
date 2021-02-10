import IconVide from "../Troupe/IconVide";
import Tab from "./Tab";
import './Tabs.css';

const Tabs = () => {

    let tabIconsVides = []
    const getIconsVidesDeck = (maxTroupeDeck) => {
        let nbIconsVides = maxTroupeDeck;
        let iterator = 0;
        
        while(iterator < nbIconsVides) {
            // pour avoir une clé aléatoire
            tabIconsVides.push(Math.random() * 20);
            iterator++;
        }
    }

    getIconsVidesDeck(8);

    return (
        <div className="tabs">
            <div className="tab">
                <Tab title={"Attaque"} active={true} />
                <Tab title={"Defense"} active={false}/>
            </div>

            <div className="tab-content">
                {tabIconsVides.map(data => {
                    return <IconVide opacity="1" key={data} />
                })}
            </div>
        </div>
    );
}

export default Tabs;