import React from 'react';
import './NavFooter.css';

class NavFooter extends React.Component{
    render(){
        return (
            <footer>
                <div className="nav-bloc"><a href="#"><img src=""/></a></div>
                <div className="nav-bloc"><a href="#"><img src=""/></a></div>
                <div className="nav-bloc"><a href="#"><img src="/images/glob_nobility-icon.png"/></a></div>
                <div className="nav-bloc"><a href="#"><img src="/images/store_battle-backpack.png" className="icon-backpack"/></a></div>
                <div className="nav-bloc"><a href="#"><img src="" className=""/></a></div>
            </footer>
        );
    }
}

export default NavFooter;