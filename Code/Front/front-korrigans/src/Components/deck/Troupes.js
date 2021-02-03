import axios from 'axios';
const Troupes = () => {
    
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
      /*
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
    

    return (
        <div>
            <p>Liste des troupes disponibles</p>
        </div>
    );
};

export default Troupes;