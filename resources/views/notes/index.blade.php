<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-gray-800" style="font-family: 'Shadows Into Light', cursive;">Mis Apuntes</h2>
                    <a href="{{ route('subjects.index') }}" class="text-blue-600 hover:underline text-sm font-bold flex items-center gap-1 mt-1">
                        ⚙️ Gestionar Asignaturas
                    </a>
                </div>
                <a href="{{ route('notes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full shadow-lg transition-all transform hover:scale-105 font-bold">
                    + Nuevo Apunte
                </a>
            </div>

            @if($notes->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 text-xl italic">Aún no tienes apuntes creados.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($notes as $note)
                        <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border-t-8" style="border-color: {{ $note->color }}">
                            <a href="{{ route('notes.edit', $note->id) }}" class="block p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded">
                                        {{ $note->subject ?? 'General' }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">{{ $note->title }}</h3>
                                <p class="text-gray-500 text-sm line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit($note->content, 120) }}
                                </p>
                            </a>

                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar este apunte?')" class="bg-white p-2 rounded-full shadow-md text-gray-300 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>