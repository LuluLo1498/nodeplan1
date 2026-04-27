<x-app-layout>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Shadows+Into+Light&display=swap');
        
        .status-dot {
            height: 8px; width: 8px; border-radius: 50%;
            display: inline-block; margin-right: 5px; transition: background-color 0.3s;
        }

        /* Estilos para que el editor Quill se vea limpio */
        .ql-container.ql-snow { border: none !important; }
        .ql-toolbar.ql-snow { 
            border: none !important; 
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 8px 20px !important;
        }
        #note-editor {
            font-size: 1.25rem;
            min-height: 500px;
            color: #4b5563;
        }
    </style>

    <div class="py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-4 rounded-t-2xl border-b flex flex-wrap gap-6 items-center justify-between shadow-sm px-8">
                <div class="flex gap-4 items-center">
                    <button type="button" onclick="window.location.href='{{ route('notes.index') }}'"
                            class="mr-2 p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>

                    <div class="h-8 w-[1px] bg-gray-200 mr-2"></div>

                    <div class="flex flex-col">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Asignatura</label>
                        <select id="note-subject" class="border-none focus:ring-0 font-semibold text-gray-700 p-0 text-lg bg-transparent cursor-pointer">
                            <option value="">Sin Asignatura</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->name }}" 
                                    {{ (isset($note) && $note->subject == $subject->name) ? 'selected' : '' }}
                                    data-color="{{ $subject->color }}">
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col ml-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Color</label>
                        <input type="color" id="note-color" value="{{ $note->color ?? '#3b82f6' }}" 
                            class="w-8 h-8 rounded-lg border-none cursor-pointer bg-transparent p-0">
                    </div>

                    <div class="flex flex-col ml-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Adjuntar</label>
                        <label class="cursor-pointer group flex items-center gap-2">
                            <input type="file" id="note-file" class="hidden">
                            <div class="p-2 bg-gray-50 group-hover:bg-blue-50 rounded-lg border border-dashed border-gray-300 group-hover:border-blue-400 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                            <span id="file-name-display" class="text-[10px] text-gray-500 max-w-[80px] truncate">Opcional</span>
                        </label>
                    </div>
                </div>

                <div id="save-indicator" class="flex items-center text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-4 py-2 rounded-full border">
                    <span id="status-dot" class="status-dot bg-gray-300"></span>
                    <span id="status-text">{{ isset($note) ? 'Guardado' : 'Listo' }}</span>
                </div>
            </div>

            <div id="editor-container" data-id="{{ $note->id ?? '' }}" class="bg-white shadow-2xl rounded-b-2xl">
                <div class="px-10 md:px-20 pt-10">
                    <input type="text" id="note-title" placeholder="Título de la lección..." 
                        value="{{ $note->title ?? '' }}"
                        class="w-full text-5xl font-bold border-none focus:ring-0 placeholder-gray-100 mb-6"
                        style="font-family: 'Shadows Into Light', cursive;">
                    <hr class="border-gray-100">
                </div>

                <div id="file-preview-zone" class="px-10 md:px-20 mt-4">
                    @if(isset($note) && $note->file_path)
                        <div class="p-3 bg-blue-50 rounded-lg flex items-center justify-between border border-blue-100">
                            <div class="flex items-center text-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" /></svg>
                                <span class="text-sm font-medium">Archivo adjunto guardado</span>
                            </div>
                            <a href="{{ asset('storage/' . $note->file_path) }}" target="_blank" class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Ver archivo</a>
                        </div>
                    @endif
                </div>

                <div class="px-10 md:px-20 pb-20 mt-4">
                    <div id="note-editor">
                        {!! $note->content ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // --- INICIALIZACIÓN DE QUILL ---
        const quill = new Quill('#note-editor', {
            theme: 'snow',
            placeholder: 'Escribe aquí o pega una imagen directamente...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['image', 'link', 'blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [1, 2, 3, false] }]
                ]
            }
        });

        const container = document.getElementById('editor-container');
        let currentNoteId = container.getAttribute('data-id') || null;
        let timeout = null;

        const statusText = document.getElementById('status-text');
        const statusDot = document.getElementById('status-dot');

        function updateStatus(state) {
            if(!statusText) return;
            switch(state) {
                case 'typing':
                    statusText.innerText = "Escribiendo...";
                    statusDot.className = "status-dot bg-blue-400 animate-pulse";
                    break;
                case 'saving':
                    statusText.innerText = "Guardando...";
                    statusDot.className = "status-dot bg-yellow-400";
                    break;
                case 'saved':
                    statusText.innerText = "Guardado";
                    statusDot.className = "status-dot bg-green-400";
                    break;
            }
        }

        async function autoSave() {
            updateStatus('typing');
            clearTimeout(timeout);

            timeout = setTimeout(async () => {
                updateStatus('saving');

                try {
                    const formData = new FormData();
                    formData.append('id', currentNoteId || '');
                    formData.append('title', document.getElementById('note-title').value || 'Sin título');
                    formData.append('content', quill.root.innerHTML); // Capturamos el HTML de Quill
                    formData.append('subject', document.getElementById('note-subject').value);
                    formData.append('color', document.getElementById('note-color').value);
                    
                    const fileInput = document.getElementById('note-file');
                    if (fileInput.files[0]) {
                        formData.append('file', fileInput.files[0]);
                    }

                    const response = await fetch("{{ route('notes.upsert') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (response.ok) {
                        currentNoteId = data.note_id;
                        container.setAttribute('data-id', data.note_id);
                        updateStatus('saved');

                        // Si se subió un archivo, actualizamos la vista previa dinámicamente
                        if (data.file_url) {
                            document.getElementById('file-preview-zone').innerHTML = `
                                <div class="p-3 bg-green-50 rounded-lg flex items-center justify-between border border-green-100">
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" /></svg>
                                        <span class="text-sm font-medium">¡Archivo adjunto actualizado!</span>
                                    </div>
                                    <a href="${data.file_url}" target="_blank" class="text-xs bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">Ver archivo</a>
                                </div>
                            `;
                            fileInput.value = ""; // Limpiar input para no re-subir
                            document.getElementById('file-name-display').innerText = "Guardado";
                        }
                    }
                } catch (error) {
                    console.error('Error al guardar:', error);
                }
            }, 1000); 
        }

        // --- LISTENERS ---
        document.getElementById('note-title').addEventListener('input', autoSave);
        document.getElementById('note-color').addEventListener('input', autoSave);
        quill.on('text-change', autoSave); // Listener especial para Quill

        document.getElementById('note-file').addEventListener('change', function() {
            if(this.files[0]) {
                document.getElementById('file-name-display').innerText = this.files[0].name;
                autoSave();
            }
        });

        document.getElementById('note-subject').addEventListener('change', function() {
            const color = this.options[this.selectedIndex].getAttribute('data-color');
            if (color) document.getElementById('note-color').value = color;
            autoSave();
        });
    </script>
</x-app-layout>