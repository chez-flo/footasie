import FullCalendar from '@fullcalendar/react' // must go before plugins
import dayGridPlugin from '@fullcalendar/daygrid' // a plugin!

/**
 * Calendar functional component.
 * @returns 
 */
function CustomCalendar(props) {
  return (
        <FullCalendar
          plugins={[ dayGridPlugin ]}
          initialView="dayGridMonth"
          events={[
            { title: 'match 1', date: '2022-06-16' },
            { title: 'match 2', date: '2022-06-16' },
            { title: 'match 3', date: '2022-06-16' },
            { title: 'match 4', date: '2022-06-16' },
          ]}
        />
  );
}
export default CustomCalendar;