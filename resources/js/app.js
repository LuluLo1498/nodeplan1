import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'es',
            events: '/events',
            editable: true,
            selectable: true,

            //para que cuando se haga click en un cuadro del mes se abra ese día completo 
            dateClick: function(info) {
                calendar.changeView('timeGridDay', info.dateStr);
            },
            //esto es lo mimo pero forzandolo
            select: function(info) {
                calendar.changeView('timeGridDay', info.startStr);
            },

            eventClick: function(info) {
                if (confirm("¿Deseas eliminar este evento?")) {
                    eliminarEvento(info.event.id);
                } else {
                    document.getElementById('event-title').value = info.event.title;
                    document.getElementById('event-start').value = info.event.startStr.slice(0, 16);
                    // Guardamos el ID en el formulario por si queremos editar luego
                    form.dataset.eventId = info.event.id;
                    btnText.textContent = 'Actualizar Nota';
                }
            },
        }); // Cierre de new Calendar

        calendar.render();

        async function eliminarEvento(id) {
            try {
                const response = await fetch(`/events/${id}`, {
                    method: 'DELETE',
                    headers: {
                        // Importante: Laravel necesita el token CSRF
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    }
                });

                if (response.ok) {
                    calendar.refetchEvents();
                    showToast('Evento eliminado 🗑️', 'success');
                } else {
                    showToast('No se pudo eliminar', 'error');
                }
            } catch (error) {
                showToast('Error de conexión', 'error');
            }
        }
    } // Cierre de if(calendarEl)
}); // Cierre de DOMContentLoaded