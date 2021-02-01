import Troupes from './deck/Troupes';
import useTheme from '@material-ui/core/styles';
import useMediaQuery from '@material-ui/core/useMediaQuery';
import './App.css';
/*
  TODO :
  npm install @material-ui/core
*/
function App() {
  const theme = useTheme();
  const matches = useMediaQuery(theme.breakpoints.up('sm'));
  const css = {};

  if(matches) {
    css = {
      width: '100vw'
    }
  } else {
    css = {
      width: '25em'
    }
  }

  

  return (
    <div class="screen" style={css}>
      <Troupes />
    </div>
  );
}

export default App;
