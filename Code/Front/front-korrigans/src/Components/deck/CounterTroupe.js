import './CounterTroupe.css';
import Button from './Button';

const CounterTroupe = ({ counter, add, remove }) => {

    return (
        <div className="counter-troupe">
            <Button onButtonClick={remove} text={"-"} />
            <span className="span-counter">{counter}</span>
            <Button onButtonClick={add} text={"+"} />
        </div>
    );
}

export default CounterTroupe;