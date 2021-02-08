import Troupes from './Components/Troupe/Troupes';
import './App.css';
import Navbar from './Components/Navbar/Navbar';
import NavFooter from './Components/Footer/NavFooter';
import Deck from './Components/Deck/Deck';

function App() {
  return (
    <>
      <div className="background" style={{ background: `url('${process.env.PUBLIC_URL}/images/background.jpg')` }}>
      </div>
      <div className="screen">
            <Navbar />
            <Deck />
            <Troupes />
            <NavFooter />
      </div>
    </>
  );
}

export default App;
