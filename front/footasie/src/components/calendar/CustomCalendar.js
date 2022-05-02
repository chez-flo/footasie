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
        />
  );
}
export default CustomCalendar;