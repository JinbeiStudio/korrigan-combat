import Troupes from './deck/Troupes';
import './App.css';
import Navbar from './Components/Navbar/Navbar.js'

function App() {
  const css = {
    width: '25em'
  }

  return (
    <div className="screen" style={css}>
      <Navbar />
      <Troupes />
    </div>
  );
}

export default App;
