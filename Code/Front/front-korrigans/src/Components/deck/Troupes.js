const Troupes = () => {
    
    function getCookie(key) {
        const regexp = new RegExp(`.*${key}=([^;]*)`);
        const result = regexp.exec(document.cookie);
        if(result) {
          return result [1];
        }
    }

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

    

    

    return (
        <div>
            <p>Liste des troupes disponibles</p>
        </div>
    );
};

export default Troupes;