import Cookies from 'js-cookie';

const Troupes = () => {
    
    fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0")
        .then(res => res.json())
        .then(data => console.log(data));

    let cookie = Cookies.get('kortok');
    console.log(cookie);

    fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/joueur/1", {
            method: 'GET',
            headers: {
                "content-type": "application/json",
            },
            credentials: 'same-origin'
            }
        )
        .then(res => res.json())
        .then(
            result => {
                console.log(result);
            }
        );

    return (
        <div>
            <p>Liste des troupes disponibles</p>
        </div>
    );
};

export default Troupes;