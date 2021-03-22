import './Minuteur.css';
import { useState, useEffect, useContext } from 'react';
import { FormationContext } from './Deck';
import { TroupeAjoutee } from '../../App';

const Minuteur = ({ time }) => {

    const context = useContext(FormationContext);
    const ajout = useContext(TroupeAjoutee);
    const {setIsAdd} = ajout;

    const [timer, setTimer] = useState(time);
    const { TroupeIsOk, setTroupeIsOk, setTroupeEnFormation, IconFormationEnCours, setIconFormationEnCours } = context;

    if(time) {
        let total = time*1000;
        setTimeout(() => {
            setTroupeIsOk(!TroupeIsOk);
            setTroupeEnFormation(false);
            setIconFormationEnCours([]);
            setIsAdd(true);
            clearTimeout(this);
        }, total);
    }

    setTimeout(() => {
        setTimer(timer-1)
    }, 1000);
    
    useEffect(() => {
        if(timer > 0) {
            setTimeout(() => {
                setTimer(timer-1);
            }, 1000);
        }
    }, [timer]);

    return (  
        <span>
            {timer ? timer : time}
        </span>
    );
}

export default Minuteur;