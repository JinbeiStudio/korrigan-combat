function Popup(props) {
    return (props.trigger) ? (
        <div className="popup">
            <div className="popup-inner">
                <button className="next-btn">Round suivant</button>
                <button className="surrend-btn" onClick={() => props.setTrigger(false)}>Abandonner</button>
                { props.children }
            </div>
        </div>
    ) : "";
}

export default Popup
