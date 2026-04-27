<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-800" style="font-family: 'Shadows Into Light', cursive;">Mis Asignaturas</h2>
                    <p class="text-gray-500">Configura tus categorías para organizar tus apuntes.</p>
                </div>
                <button type="button" 
                        onclick="window.location.href='{{route('notes.index')}}'" 
                        class="text-blue-600 hover:underline flex items-center gap-2 cursor-pointer font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 8.959 8.959 0 01-9 9 8.959 8.959 0 01-9-9z" />
                    </svg>
                    Volver a Notas
                </button>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm mb-8 border border-gray-100">
                <form action="{{ route('subjects.store') }}" method="POST" class="flex flex-wrap md:flex-nowrap gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2 tracking-widest">Nombre de la Asignatura</label>
                        <input type="text" name="name" placeholder="Ej: Programación Web" required
                            class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="w-full md:w-32">
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2 tracking-widest">Color</label>
                        <input type="color" name="color" value="#3b82f6" 
                            class="w-full h-[42px] p-1 bg-white border border-gray-200 rounded-xl cursor-pointer">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-xl font-bold transition-all shadow-md">
                        Añadir
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse($subjects as $subject)
                    <div class="bg-white p-4 rounded-xl shadow-sm border-l-8 flex justify-between items-center group transition-all hover:shadow-md" 
                         style="border-color: {{ $subject->color }}">
                        <div>
                            <h3 class="font-bold text-gray-700 text-lg">{{ $subject->name }}</h3>
                        </div>
                        
                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar esta asignatura? Esto no borrará tus notas, pero quedarán sin categoría.')" 
                                    class="text-gray-300 hover:text-red-500 transition-colors p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-400 italic">No tienes asignaturas creadas todavía.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>