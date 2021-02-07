import './CounterTroupe.css';
import Button from './Button';

const CounterTroupe = () => {

    let counter = 1;

    return (
        <div className="counter-troupe">
            <Button text={"-"} />
            <span className="span-counter">{counter}</span>
            <Button text={"+"} />
        </div>
    );
}

export default CounterTroupe;