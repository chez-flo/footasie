import './App.css';
import CustomCalendar from "./components/calendar/CustomCalendar"
import AppHeader from "./components/header/AppHeader"
function App() {
  return (
    <div className="App">
      <AppHeader/>
      <div className='content'>
        <CustomCalendar/>
      </div>
    </div>
  );
}

export default App;
