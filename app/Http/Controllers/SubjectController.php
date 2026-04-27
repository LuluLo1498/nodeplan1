<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('user_id', auth()->id())->get();
        
        // Esto buscará resources/views/subjects/index.blade.php
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string'
        ]);

        Subject::create([
            'name' => $request->name,
            'color' => $request->color,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Asignatura creada');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->user_id !== Auth::id()) abort(403);
        $subject->delete();
        return back()->with('success', 'Asignatura eliminada');
    }
}