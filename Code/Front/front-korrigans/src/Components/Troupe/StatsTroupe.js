import './StatsTroupe.css';

const StatsTroupe = ({ stats, nbTroupe}) => {

    let duree = (stats.tempsFormation*60) * nbTroupe;

    const secondsToTime = (secs) => {
        let hours = Math.floor(secs / (60 * 60));
        let for_minutes = secs % (60 * 60);
        let minutes = Math.floor(for_minutes / 60);
        let for_seconds = for_minutes % 60;
        let seconds = Math.ceil(for_seconds);
        
        let result = "";
        if(minutes == 0 && hours == 0) {
            result = `${seconds}s`;
        } else if(seconds == 0 && hours == 0) {
            result = `${minutes}min`;
        } else if(hours != 0 && seconds == 0) {
            result = `${hours}h ${minutes}min`;
        } else if(hours != 0) {
            result = `${hours}h ${minutes}min ${seconds}s`;
        } else {
            result = `${minutes}min ${seconds}s`;
        }
        return result;
    }

    duree = secondsToTime(duree);

    const attaque = "/images/stats/attaque.png";
    const portee = "/images/stats/portee.png";
    const temps_formation = "/images/stats/temps_formation.png";
    const vitesse = "/images/stats/vitesse.png";

    const taille = "50";
    
    return (
        <div className="statistiques-troupe">
            <div>
                <img src={attaque} alt={"attaque"} width={taille} height={taille} />
                <p>{stats.poids}</p>
            </div>
            <div>
                <img src={portee} alt={"portee"} width={taille} height={taille} />
                <p>{stats.portee}</p>
            </div>
            <div>
                <img className="temps-formation" src={temps_formation} alt={"temps_formation"} width={taille} height={taille} />
                <p>{duree}</p>
            </div>
            <div>
                <img src={vitesse} alt={"vitesse"} width={taille} height={taille} />
                <p>{stats.vitesse}</p>
            </div>
        </div>
    );
}

export default StatsTroupe;