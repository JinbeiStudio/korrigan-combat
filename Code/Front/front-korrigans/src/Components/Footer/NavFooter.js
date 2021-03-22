import React from 'react';
import './NavFooter.css';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
  } from "react-router-dom";

class NavFooter extends React.Component{
    render(){
        return (
            <footer>
                <div className="nav-bloc"><Link to="/"><img src="/images/glob_icon-notif-build.png" className="icon-build"/></Link></div>
                <div className="nav-bloc"><Link to="/Opponents"><img src="/images/map_attack-icon.png" className="icon-attack"/></Link></div>
                <div className="nav-bloc"><Link to="/Deck"><img src="/images/glob_nobility-icon.png" className="icon-helmet"/></Link></div>
                <div className="nav-bloc"><a href="#"><img src="/images/store_battle-backpack.png" className="icon-backpack"/></a></div>
                <div className="nav-bloc"><img src="/images/glob_minirare-skillartifact.png" className="icon-artifact"/></div>
            </footer>
        );
    }
}

export default NavFooter;