import './Troupes.css';
import TroupeList from './TroupesList';
import { useEffect, useState } from 'react';

const Troupes = () => {

    function getCookie(key) {
        const regexp = new RegExp(`.*${key}=([^;]*)`);
        const result = regexp.exec(document.cookie);
        if(result) {
          return result [1];
        }
    }

    const [connexion, setConnexion] = useState(localStorage.getItem('connexion') ?? false);

    useEffect(() => {
      if(!connexion) {
        fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0", {
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Cache': 'no-cache'
          }
        }).then( res => {
          if(getCookie('kortok')) {
            console.log(getCookie('kortok'));
            localStorage.setItem('connexion', true);
            setConnexion(true);
          } else {
            console.log("pas de cookie");
          }
        })
        .catch((err) => {
          console.log(err);
        });
      } else {
        console.log(connexion);
      }
    }, []);

    if(connexion) {
      fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/troupe", {
          credentials: 'same-origin'
      })
          .then(res => {
            return res.json();
          })
          .then(result => {
            console.log(result);
          })
          .catch((err) => {
            console.log(err);
          });
      }

    // Troupes du joueur 1
    const troupes = {"troupesJoueur1":
                        [{
                          "idTroupeJoueur":"1",
                          "idTroupe":"1",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"},
                        {
                          "idTroupeJoueur":"2",
                          "idTroupe":"2",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"3",
                          "idTroupe":"3",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"7",
                          "idTroupe":"7",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"4",
                          "idTroupe":"4",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"5",
                          "idTroupe":"5",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"6",
                          "idTroupe":"6",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        },
                        {
                          "idTroupeJoueur":"8",
                          "idTroupe":"8",
                          "idJoueur":"1",
                          "niveauTroupe":"1",
                          "experience":"0"
                        }]
                    };
    
    return (
        <div className="troupes-disponibles">
            <div className="titre-troupes">
                <p>Troupes débloquées</p>
            </div>
            <div className="troupes">
                <TroupeList troupes={troupes.troupesJoueur1}/>
            </div>
        </div>
    );
};

export default Troupes;


                     /*
    function getCookie(key) {
        const regexp = new RegExp(`.*${key}=([^;]*)`);
        const result = regexp.exec(document.cookie);
        if(result) {
          return result [1];
        }
    }

    function reqListener () {
        console.log(this.responseText);
      }
      
      $.ajax({
        type: 'GET',
        url: 'https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0',
        xhrFields: {
            withCredentials: true
        }
      });

      var oReq = new XMLHttpRequest();
      oReq.onload = reqListener;
      oReq.open("get", "https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0", true);
      oReq.withCredentials = true;
      oReq.send();


    axios.defaults.withCredentials = true;
    axios.get("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0", {
        method: 'GET',
        withCredentials: true,
        crossDomain: true,
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(res => console.log(res));

    /*

    let header = new Headers();
    header.append('Access-Control-Allow-Credentials', 'true');
    header.append('Content-type', 'application/json');

    fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0", {
        credentials: 'same-origin'
    })
        .then(res => {  

            console.log(getCookie('kortok'));

            fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/joueur/1", {
                credentials: 'same-origin',
                headers: header
            })
                .then(res => {
                    return res.json();
                })
                .then(result => {
                        console.log(result);
                });
        });
    
    console.log(document.cookie);

    
    */