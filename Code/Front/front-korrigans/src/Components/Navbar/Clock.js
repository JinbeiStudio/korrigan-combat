import React from 'react';

class Clock extends React.Component {
    constructor(props) {
      super(props);
      this.updateDate = this.updateDate.bind(this);
      
      this.state = {
        date: new Date().toLocaleTimeString(),
      }
      this.interval = setInterval(this.updateDate, 1000);
    }
    
    componentWillUnmount() {
      clearInterval(this.interval);
    }
    
    updateDate() {
      this.setState({
        date: new Date().toLocaleTimeString(),
      });
    }
    
    render() {
      const style = {
        paddingLeft:"20px",
        paddingTop:"10px",
        fontSize:"16px"
      }
      return(
        <div style={style}>{this.state.date}</div>
      );
    }
  }

export default Clock;