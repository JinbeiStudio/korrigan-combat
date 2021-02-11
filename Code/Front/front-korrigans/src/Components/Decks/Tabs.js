import IconVide from "../Troupe/IconVide";
import IconTroupe from '../Troupe/IconTroupe';
import Tab from "./Tab";
import './Tabs.css';
import { useState, useEffect } from 'react';

const Tabs = ({ troupeToAdd, isAdd, deckJoueur, toggleTab, activeTab, tabTabs, TabIconsVides }) => {

    const [troupeEnFormation, setTroupeEnFormation] = useState(false);

    const disabledClick = (event, idTroupe) => {
        event.stopPropagation();
    }

    /**************************************************************** */
    /*************** Envoi d'une troupe en formation *****************/
    /**************************************************************** */
    useEffect(() => {
        console.log({troupeToAdd});

        const fetchAjoutTroupeDeck = async () => {
            var details = {
                'idTroupeJoueur': Number(troupeToAdd.idTroupeJoueur),
                'quantite': Number(troupeToAdd.nbTroupes),
                'idDeck': Number("1")
            };
            
            var formBody = [];
            for (var property in details) {
              var encodedKey = encodeURIComponent(property);
              var encodedValue = encodeURIComponent(details[property]);
              formBody.push(encodedKey + "=" + encodedValue);
            }
            formBody = formBody.join("&");
            console.log(formBody);

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
                    console.log(result);
                    setTroupeEnFormation(true);
                });
        }
        if(!isAdd) {  
            console.log({troupeToAdd}); 
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
                    console.log(data);
                    setTroupeEnFormation(true);
                });
        }

        if(troupeEnFormation) {
            fetchTroupeEnFormation();
        }
    }, [troupeEnFormation])

    return (
        <div className="tabs">
            <div className="tab">
                {tabTabs.map(data => {
                    return <Tab key={data} handleClickTab={toggleTab} title={data} active={ data === activeTab ? true : false } />
                })}
            </div>

            <div className="tab-content">
                {deckJoueur.length !== 0 ? deckJoueur.map((data) => {
                    console.log(data.quantite);
                    return  <IconTroupe level="1" onTroupeClick={disabledClick} quantite={data.quantite} key={data.idTroupeJoueur} troupe={data.idTroupeJoueur} />
                }) : ""}
                {TabIconsVides.length !== 0 ? TabIconsVides.map(data => {
                    return <IconVide opacity="1" key={data} />
                }) : ""}
            </div>
        </div>
    );
}

export default Tabs;