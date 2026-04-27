<x-app-layout>
    <style>
        .toast { position: fixed; top: 20px; right: 20px; padding: 1rem 2rem; border-radius: 8px; color: white; opacity: 0; transition: opacity 0.3s; z-index: 9999; pointer-events: none; }
        .toast.show { opacity: 1; }
        .toast.success { background: #10b981; }
        .toast.error { background: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.75rem; display: none; margin-top: 4px; }
        .error-msg.visible { display: block; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="toast" class="toast"></div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="max-w-[95%] mx-auto mt-10 bg-[#fdfbf7] shadow-2xl rounded-lg border border-gray-200 flex relative overflow-hidden" style="min-height: 800px;">
                    
                    <div class="w-2/3 p-8 pr-12 bg-white relative">
                        <div id="calendar"></div>
                    </div>

                    <div class="w-1/3 p-8 pl-12 bg-[#fdfbf7] relative">
                        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(#000 0 1px, transparent 1px 100%); background-size: 100% 2.5rem;"></div>

                        <div class="relative z-20">
                            <h3 class="text-4xl font-bold mb-6 text-gray-800" style="font-family: 'Shadows Into Light', cursive;">Mis Notas</h3>
                            
                            <form id="eventForm" class="space-y-8">
                                @csrf
                                <div class="border-b-2 border-blue-200 pb-2">
                                    <label class="block text-gray-500 text-sm uppercase font-sans">¿Qué hay que hacer?</label>
                                    <input type="text" id="event-title" name="title" placeholder="Escribe aquí..." class="w-full bg-transparent border-none focus:ring-0 text-2xl" style="font-family: 'Shadows Into Light', cursive;">
                                    <p id="error-title" class="error-msg">Debes escribir un título</p>
                                </div>

                                <div class="flex gap-4">
                                    <div class="border-b-2 border-blue-200 flex-1">
                                        <label class="block text-gray-500 text-sm font-sans uppercase">Inicio</label>
                                        <input type="datetime-local" id="event-start" name="start_time" class="w-full bg-transparent border-none focus:ring-0">
                                        <p id="error-start" class="error-msg">Selecciona una fecha</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 py-4">
                                    <input type="checkbox" name="is_exam" class="rounded border-gray-400 text-red-600 focus:ring-red-500">
                                    <span class="font-sans text-gray-700 uppercase text-xs font-bold">¿Es un Examen? ⚠️</span>
                                </div>

                                <button type="submit" id="submit-btn" class="bg-red-600 text-white px-8 py-3 rounded-full text-xl shadow-lg hover:bg-red-700 transition transform hover:scale-105" style="font-family: 'Shadows Into Light', cursive;">
                                    <span id="btn-text">Anotar en agenda</span>
                                </button>
                            </form>

                            <div class="mt-10 relative z-20">
                                <h4 class="text-2xl font-bold mb-4 text-gray-700" style="font-family: 'Shadows Into Light', cursive;">Próximos eventos</h4>
                                <div id="events-list" class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                    <p class="text-gray-400 italic text-sm">Cargando eventos...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
    // 1. Función de Toast (Notificaciones)
    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        if(!t) return;
        t.textContent = msg;
        t.className = `toast ${type} show`;
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    // 2. Función de Validación
    function validateForm() {
        let isValid = true;
        const title = document.getElementById('event-title');
        const start = document.getElementById('event-start');
        const errT = document.getElementById('error-title');
        const errS = document.getElementById('error-start');

        if (!title.value.trim()) {
            errT.classList.add('visible');
            isValid = false;
        } else {
            errT.classList.remove('visible');
        }

        if (!start.value) {
            errS.classList.add('visible');
            isValid = false;
        } else {
            errS.classList.remove('visible');
        }
        return isValid;
    }

    // 3. Función para enfocar el calendario desde la lista
    window.focusEvent = function(dateISO) {
        if (window.currentCalendar) {
            // Ir a la fecha y cambiar a vista de día para ver el detalle
            window.currentCalendar.gotoDate(dateISO);
            window.currentCalendar.changeView('timeGridDay');
            
            // Scroll suave hacia el calendario si estamos en móvil/pantalla pequeña
            document.getElementById('calendar').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    window.deleteEvent = async function(e, id) {
        // Evitamos que al hacer clic en borrar se active el focusEvent del padre
        e.stopPropagation();

        if (!confirm('¿Estás seguro de que quieres eliminar esta nota?')) return;

        try {
            const response = await fetch(`/events/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                showToast('Evento eliminado correctamente', 'success');
                // Recargamos el calendario y la lista
                window.currentCalendar.refetchEvents();
                // Esta función debe ser accesible. Si fetchEventsList está dentro del DOMContentLoaded,
                // puedes moverla fuera o disparar un evento personalizado. 
                // Por simplicidad, asumiremos que refrescas la lista llamando a la función que ya tienes:
                location.reload(); // Opción rápida, o mejor llama a fetchEventsList() si la hiciste global.
            } else {
                showToast('No se pudo eliminar el evento', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error de conexión', 'error');
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const eventsListEl = document.getElementById('events-list');
        const form = document.getElementById('eventForm');
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');

        // 4. Inicialización de FullCalendar
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 748,
            selectable: true,
            navLinks: true,
            editable: true,
            events: '/events', // Ruta que devuelve el JSON
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
        });

        calendar.render();
        window.currentCalendar = calendar; // Guardar instancia global

        // 5. Función para obtener y renderizar la lista de la derecha
        async function fetchEventsList() {
            try {
                const response = await fetch('/events');
                const events = await response.json();
                renderEventsList(events);
            } catch (error) {
                console.error('Error al cargar la lista:', error);
            }
        }

        function renderEventsList(events) {
            if (!eventsListEl) return;
            
            if (events.length === 0) {
                eventsListEl.innerHTML = '<p class="text-gray-400 italic text-sm">No hay tareas pendientes.</p>';
                return;
            }

            // Ordenar por fecha ascendente
            events.sort((a, b) => new Date(a.start) - new Date(b.start));

            eventsListEl.innerHTML = events.map(event => {
                const date = new Date(event.start);
                // Detectar si es examen (ya sea por atributo directo o extendedProps de FullCalendar)
                const isExam = event.is_exam || (event.extendedProps && event.extendedProps.is_exam);
                
                const bgColor = isExam ? 'bg-red-100 border-red-400' : 'bg-blue-50 border-blue-300';
                const textColor = isExam ? 'text-red-800' : 'text-blue-800';
                const badge = isExam ? '⚠️ EXAMEN' : 'TAREA';
                const dateISO = date.toISOString().split('T')[0];

                
                return `
                    <div class="relative group mb-3">
                        <div onclick="focusEvent('${dateISO}')" 
                            class="p-4 ${bgColor} border-l-4 rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-all transform hover:-translate-y-1">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-black uppercase tracking-widest ${textColor}">${badge}</span>
                                <span class="text-[10px] text-gray-500 font-mono">${date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                            </div>
                            <p class="font-bold text-gray-900 text-lg leading-tight pr-8" style="font-family: 'Shadows Into Light', cursive;">
                                ${event.title}
                            </p>
                            <div class="mt-2 text-[11px] text-gray-600 font-sans font-bold uppercase">
                                ${date.toLocaleDateString('es-ES', { day: '2-digit', month: 'long' })}
                            </div>
                        </div>
                        
                        <button onclick="deleteEvent(event, ${event.id})" 
                                class="absolute top-2 right-2 p-2 text-gray-400 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
            }).join('');
        }

        // 6. Lógica del Formulario (Guardar)
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!validateForm()) return;

            btn.disabled = true;
            btnText.textContent = 'Anotando...';

            try {
                const response = await fetch("{{ route('events.store') }}", {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.errors ? Object.values(data.errors)[0][0] : 'Error al guardar');
                }

                // Actualizar ambos componentes
                calendar.refetchEvents();
                fetchEventsList(); 
                
                form.reset();
                showToast('¡Anotado con éxito! 📝');
            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                btn.disabled = false;
                btnText.textContent = 'Anotar en agenda';
            }
        });

        // Carga inicial de la lista
        fetchEventsList();
    });
</script>
</x-app-layout>