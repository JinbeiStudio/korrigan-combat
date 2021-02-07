import './TrainButton.css';

const TrainButton = ({ text }) => {

    return (
        <>
            <button type="button" className="train-button">
                <span>{text}</span>
            </button>
        </>
    ); 
}

export default TrainButton;