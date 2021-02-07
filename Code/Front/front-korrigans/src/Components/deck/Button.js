import './Button.css';

const Button = ({ text, onButtonClick }) => {

    return (
        <>
            <button onClick={() => onButtonClick()} type="button" className="button">
                <span>{text}</span>
            </button>
        </>
    ); 
}

export default Button;