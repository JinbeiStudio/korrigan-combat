const Troupes = () => {
    
    fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/login?login=korrigans&password=korrigans&ver=1.0")
        .then(res => console.log(res));
    
    let myHeaders = new Headers();
    myHeaders.append('Content-Type', 'application/json');
    myHeaders.append('Access-Control-Allow-Origin', '*');

    fetch("https://korrigans-team2-ws.lpweb-lannion.fr/api/1.0/joueur/1", {
            mode: 'cors',
            method:'GET',
            headers: myHeaders
        })
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