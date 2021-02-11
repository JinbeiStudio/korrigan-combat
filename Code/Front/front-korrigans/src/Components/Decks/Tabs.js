import IconVide from "../Troupe/IconVide";
import Tab from "./Tab";
import './Tabs.css';
import { useState, useEffect } from 'react';

const Tabs = ({ troupeToAdd, isAdd }) => {

    const tabTabs = ["Attaque", "Defense"];
    const [activeTab, setActiveTab] = useState(localStorage.getItem('deck') ?? "Attaque");
    const [deckJoueur, setDeckJoueur] = useState([]);

    useEffect(() => {
        let type_deck = 2;
        if(activeTab === tabTabs[0]) {
            type_deck = 1;
        }

        const fetchDeckJoueur = async () => {
            const getDeckJoueur = await fetch(
                `https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/deck-joueur/0/type/${type_deck}/numero/0`, {
                credentials: 'include'
            })
                .then(res => {
                    return res.json();
                })
                .then(result => {
                    console.log(result);
                    setDeckJoueur(result);
                })
                .catch((err) => {
                    console.log(err);
                });
        }

        fetchDeckJoueur();
        localStorage.setItem('deck', activeTab);

    }, [activeTab]);
/*
    useEffect(() => {
        console.log(troupeToAdd);
        const fetchAjoutTroupeDeck = async () => {
            const ajoutTroupeDeck = await fetch(
                `https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/formation-troupes/${troupeToAdd.idJoueur}`, {
                credentials: 'include',
                body: {
                    idTroupeJoueur: troupeToAdd.idTroupeJoueur,
                    quantite: troupeToAdd.nbTroupes,
                    idDeck: "1"
                }
            })
        }
        if(!isAdd) {   
            fetchAjoutTroupeDeck();
        }
    }, [isAdd]);
*/
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

    const toggleTab = (event, title) => {
        event.preventDefault();
        setActiveTab(title);
    }

    getIconsVidesDeck(8);

    return (
        <div className="tabs">
            <div className="tab">
                {tabTabs.map(data => {
                    return <Tab key={data} handleClickTab={toggleTab} title={data} active={ data === activeTab ? true : false } />
                })}
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