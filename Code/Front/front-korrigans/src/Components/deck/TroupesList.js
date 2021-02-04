import './TroupesList.css';
import IconTroupe from './IconTroupe';

const TroupesList = ({ troupes }) => {
    return [
        troupes.map(data => {
            return <IconTroupe key={data.idTroupeJoueur} level={data.niveauTroupe} troupe={data.idTroupe} />
        })
    ];
}

export default TroupesList;