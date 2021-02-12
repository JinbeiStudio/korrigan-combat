import IconVide from "../Troupe/IconVide";
import IconTroupe from '../Troupe/IconTroupe';
import Tab from "./Tab";
import './Tabs.css';
import { useState, useEffect, useContext } from 'react';
import { FormationContext } from './Deck';

const Tabs = ({ troupeToAdd, isAdd, deckJoueur, toggleTab, activeTab, tabTabs, TabIconsVides }) => {

    const [TabFormationEnCours, setTabFormationEnCours] = useState([]);
    const context = useContext(FormationContext);

    const {troupeEnFormation, setTroupeEnFormation, IconFormationEnCours, setIconFormationEnCours} = context;

    const disabledClick = (event, idTroupe) => {
        event.stopPropagation();
    }

    /**************************************************************** */
    /*************** Envoi d'une troupe en formation *****************/
    /**************************************************************** */
    useEffect(() => {
        let type_deck = 2;
        if(activeTab === tabTabs[0]) {
            type_deck = 1;
        }

        const fetchAjoutTroupeDeck = async () => {
            var details = {
                'idTroupeJoueur': Number(troupeToAdd.idTroupeJoueur),
                'quantite': Number(troupeToAdd.nbTroupes),
                'idDeck': Number(type_deck)
            };
            
            var formBody = [];
            for (var property in details) {
              var encodedKey = encodeURIComponent(property);
              var encodedValue = encodeURIComponent(details[property]);
              formBody.push(encodedKey + "=" + encodedValue);
            }
            formBody = formBody.join("&");

            const ajoutTroupeDeck = await fetch(
                `https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/formation-troupes/${troupeToAdd.idJoueur}`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: formBody
            })
                .then(result => {
                    setTroupeEnFormation(true);
                });
        }
        if(!isAdd) {  
            fetchAjoutTroupeDeck();
        }
    }, [isAdd]);

    /**************************************************************** */
    /*************** Récuperation des troupes en formation ************/
    /**************************************************************** */
    useEffect(() => {
        const fetchTroupeEnFormation = async () => {
            const TroupesEnFormation = await fetch(
                `https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/formation-troupes/${troupeToAdd.idJoueur}`, {
                credentials: 'include'
            })
                .then(result => result.json())
                .then(data => {
                    setTroupeEnFormation(true);
                    setTabFormationEnCours(data.formationTroupes);
                });
        }

        if(troupeEnFormation) {
            fetchTroupeEnFormation();
        }
    }, [troupeEnFormation]);


    useEffect(() => {
        if(TabFormationEnCours.length > 0) {
            setIconFormationEnCours(TabFormationEnCours.map(data => {
                console.log(data);
                let fin = new Date(data.dateFinFormation);
                let debut = new Date(data.dateDebutFormation);
                
                let time = (fin - debut)/1000;
                data.time = time;
                TabIconsVides.shift();
                return data;
            }));
        }
    }, [TabFormationEnCours]);

    return (
        <div className="tabs">
            <div className="tab">
                {tabTabs.map(data => {
                    return <Tab key={data} handleClickTab={toggleTab} title={data} active={ data === activeTab ? true : false } />
                })}
            </div>

            <div className="tab-content">
                {deckJoueur.length !== 0 ? deckJoueur.map((data) => {
                    return <IconTroupe level="1" onTroupeClick={disabledClick} quantite={data.quantite} key={data.idTroupeJoueur} troupe={data.idTroupe} />
                }) : ""}
                {IconFormationEnCours.length !== 0 ? IconFormationEnCours.map(data => {
                    return <IconTroupe level="1" onTroupeClick={disabledClick} time={data.time} key={data.idTroupeJoueur} troupe={data.idTroupeJoueur} />
                }) : ""}
                {TabIconsVides.length !== 0 ? TabIconsVides.map(data => {
                    return <IconVide opacity="1" key={data} />
                }) : ""}
            </div>
        </div>
    );
}

export default Tabs;