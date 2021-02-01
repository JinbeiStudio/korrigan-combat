import Troupes from './deck/Troupes';
import './App.css';

function App() {
  const css = {
    width: '25em'
  }

  return (
    <div className="screen" style={css}>
      <Troupes />
    </div>
  );
}

export default App;
