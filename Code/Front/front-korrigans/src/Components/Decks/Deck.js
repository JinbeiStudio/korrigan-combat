import './Deck.css';
import Tabs from './Tabs';
import { useEffect, useState, createContext } from 'react';
import Minuteur from './Minuteur';

const Deck = ({ troupeToAdd, isAdd }) => {

    const maxTroupeDeck = 8;
    const background_top = "/images/deck/background-top.png";
    const background_bottom = "/images/deck/background-bottom.png";
    const tabTabs = ["Attaque", "Defense"];

    const [activeTab, setActiveTab] = useState(localStorage.getItem('deck') ?? "Attaque");
    const [deckJoueur, setDeckJoueur] = useState([]);
    const [TabIconsVides, setTabIconsVides] = useState([]);
    const [IconFormationEnCours, setIconFormationEnCours] = useState([]);
    const [troupeEnFormation, setTroupeEnFormation] = useState(false);
    const [TroupeIsOk, setTroupeIsOk] = useState(false);

    const toggleTab = (event, title) => {
        event.preventDefault();
        setActiveTab(title);
    }
    
    let IconsVides = [];

    /**************************************************************** */
    /*************** Récuperation des données du deck *****************/
    /**************************************************************** */
    useEffect(() => {
        let type_deck = 2;
        if(activeTab === tabTabs[0]) {
            type_deck = 1;
        }

        const fetchDeckJoueur = async () => {
            const getDeckJoueur = await fetch(
                `https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/deck-joueur/1/type/${type_deck}/numero/0`, {
                credentials: 'include'
            })
                .then(res => {
                    return res.json();
                })
                .then(result => {
                    console.log(result);
                    setDeckJoueur(result[`deck-${type_deck}-0`]);

                    const getIconsVidesDeck = () => {
                        let nbIconsVides = maxTroupeDeck - result[`deck-${type_deck}-0`].length;

                        let iterator = 0;
                        
                        while(iterator < nbIconsVides) {
                            // pour avoir une clé aléatoire
                            IconsVides.push(Math.random() * 20);
                            iterator++;
                        }
                        setTabIconsVides(IconsVides);
                    }

                    getIconsVidesDeck();
                })
                .catch((err) => {
                    console.log(err);
                });
        }

        fetchDeckJoueur();
        localStorage.setItem('deck', activeTab);
    }, [activeTab, TroupeIsOk]);

    return (
        <FormationContext.Provider value={{ TroupeIsOk, setTroupeIsOk, troupeEnFormation, setTroupeEnFormation, IconFormationEnCours, setIconFormationEnCours }} >
        <div className="deck" style={{ background: `url('${process.env.PUBLIC_URL}/images/deck/background-deck.jpg')` }}>
            <img className="bg-top" src={background_top} alt="" />
            <img className="bg-bottom" src={background_bottom} alt="" />
            <Tabs 
                toggleTab={toggleTab} 
                activeTab={activeTab} 
                deckJoueur={deckJoueur} 
                troupeToAdd={troupeToAdd} 
                isAdd={isAdd} 
                tabTabs={tabTabs}
                TabIconsVides={TabIconsVides} />
            <Minuteur />
        </div>
        </FormationContext.Provider>
    );
}

export default Deck;
export const FormationContext = createContext();