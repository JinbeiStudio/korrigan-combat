import Troupes from './Components/deck/Troupes';
import './App.css';
import Navbar from './Components/Navbar/Navbar';
import NavFooter from './Components/Footer/NavFooter';

function App() {
  return (
    <>
      <div className="background" style={{ background: `url('${process.env.PUBLIC_URL}/images/background.jpg')` }}>
      </div>
      <div className="screen">
            <Navbar />
            <Troupes />
            <NavFooter />
      </div>
    </>
  );
}

export default App;
