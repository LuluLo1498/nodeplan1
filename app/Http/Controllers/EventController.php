<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // 1. Listar eventos para el calendario (formato JSON)
    public function index()
    {
        $events = Auth::user()->events()->get();

        // Formateamos los datos 
        $formattedEvents = $events->map(function ($event) {
            return [
                'id'    => $event->id,
                'title' => $event->is_exam ? '⚠️ EXAMEN: ' . $event->title : $event->title,
                'start' => $event->start_time,
                'is_exam' => $event->is_exam,
                'color' => $event->is_exam ? '#ef4444' : $event->color,
            ];
        });

        return response()->json($formattedEvents);
    }

    // 2. Guardar un nuevo evento
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'nullable|date|after_or_equal:start_time',
        ]);

        
        $isExam = $request->boolean('is_exam');

        try {
            Auth::user()->events()->create([
                'title'       => $request->title,
                'description' => $request->description,
                'start_time'  => $request->start_time,
                // Si no se manda end_time, usamos start_time para que no falle
                'end_time'    => $request->end_time ?? $request->start_time,
                'is_exam'     => $isExam,
                'color'       => $isExam ? '#ef4444' : '#3788d8',
            ]);

            return response()->json(['success' => true, 'message' => '¡Evento guardado!']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el evento. Inténtalo de nuevo.',
            ], 500);
        }
    }

    // 3. Mostrar un evento concreto
    public function show(string $id)
    {
        
        $event = Event::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail(); // Usamos firstOrFail con user_id para que no vea eventos ajenos de otros usuarios

        return response()->json($event);
    }

    // 4. Actualizar una tarea
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'nullable|date|after_or_equal:start_time',
        ]);

        
        $event = Event::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail(); // Usamos firstOrFail con user_id para que no vea eventos ajenos de otros usuarios

        $isExam = $request->boolean('is_exam');

        try {
            $event->update([
                'title'       => $request->title,
                'description' => $request->description,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time ?? $request->start_time,
                'is_exam'     => $isExam,
                'color'       => $isExam ? '#ef4444' : '#3788d8',
            ]);

            return response()->json(['success' => true, 'message' => '¡Evento actualizado!']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el evento.',
            ], 500);
        }
    }

    // 5. Eliminar una tarea
    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
