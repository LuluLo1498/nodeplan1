<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{

    public function index()
    {
        $notes = Note::where('user_id', auth()->id())->latest()->get();
        
        // Necesitamos las asignaturas para mostrarlas en la cuadrícula o filtros
        $subjects = Subject::where('user_id', auth()->id())->get(); 
        
        // IMPORTANTE: 'notes.index' apunta a resources/views/notes/index.blade.php
        return view('notes.index', compact('notes', 'subjects'));
    }

    public function create($id = null)
    {
        $note = $id ? Note::where('user_id', auth()->id())->findOrFail($id) : null;
        $subjects = Subject::where('user_id', auth()->id())->get();
        
        // IMPORTANTE: 'notes.create' apunta a resources/views/notes/create.blade.php
        return view('notes.create', compact('note', 'subjects'));
    }

    public function upsert(Request $request)
    {
        $note = Note::updateOrCreate(
            ['id' => $request->id], 
            [
                'title'   => $request->title ?? 'Sin título',
                'content' => $request->content,
                'subject' => $request->subject,
                'color'   => $request->color ?? '#3b82f6',
                'user_id' => Auth::id(),
            ]
        );

        $fileUrl = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('attachments', 'public');
            $note->update(['file_path' => $path]);
            $fileUrl = asset('storage/' . $path);
        }

        return response()->json([
            'success' => true,
            'note_id' => $note->id,
            'file_url' => $fileUrl
        ]);
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) abort(403);
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Nota eliminada correctamente');
    }
}