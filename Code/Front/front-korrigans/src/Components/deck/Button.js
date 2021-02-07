import './Button.css';

const Button = ({ text }) => {

    return (
        <>
            <button type="button" className="button">
                <span>{text}</span>
            </button>
        </>
    ); 
}

export default Button;