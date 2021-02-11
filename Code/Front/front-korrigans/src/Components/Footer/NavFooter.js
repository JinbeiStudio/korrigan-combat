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
                <div className="nav-bloc"><a href="#"><img src="/images/glob_icon-notif-build.png" className="icon-build"/></a></div>
                <div className="nav-bloc"><a href="#"><img src="/images/map_attack-icon.png" className="icon-attack"/></a></div>
                <div className="nav-bloc"><a href="#"><img src="/images/glob_nobility-icon.png" className="icon-helmet"/></a></div>
                <div className="nav-bloc"><a href="#"><img src="/images/store_battle-backpack.png" className="icon-backpack"/></a></div>
                <div className="nav-bloc"><Link to="/Opponents"><img src="/images/glob_minirare-skillartifact.png" className="icon-artifact"/></Link></div>
            </footer>
        );
    }
}

export default NavFooter;