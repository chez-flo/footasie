import logo from './logo.svg';
import './App.css';
import CustomCalendar from "./components/calendar/CustomCalendar"
function App() {
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Foot Asie App
        </p>        
      </header>
      <div className='content'>
        <CustomCalendar/>
      </div>

    </div>
  );
}

export default App;
