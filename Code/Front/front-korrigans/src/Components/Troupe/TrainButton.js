import { useEffect } from 'react';
import './TrainButton.css';

const TrainButton = ({ text, handleClickTraining, dataTroupe, nbTroupe, onButtonIsClick }) => {

    return (
        <>
            <button onClick={(event) => {
                handleClickTraining(event, dataTroupe, nbTroupe);
                onButtonIsClick();
                }} type="button" className="train-button">
                <span>{text}</span>
            </button>
        </>
    ); 
}

export default TrainButton;