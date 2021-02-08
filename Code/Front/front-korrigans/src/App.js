import Troupes from './Components/deck/Troupes';
import './App.css';
import Navbar from './Components/Navbar/Navbar';
import NavFooter from './Components/Footer/NavFooter';

function App() {
  return (
    <div className="Background">
      <div className="screen">
            <Navbar />
            <Troupes />
            <NavFooter />
          </div>
    </div>
  );
}

export default App;
