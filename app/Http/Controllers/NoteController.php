<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Note;
use App\Http\Resources\Note as NoteResource;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get notes
        $notes = Note::orderBy('created_at', 'desc')->paginate(5);

        // Return collection of notes as a resource
        return NoteResource::collection($notes);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = $request->isMethod('put') ? Note::findOrFail($request->note_id) : new Note;

        $note->id = $request->input('note_id');
        $note->note = $request->input('note');
        $note->color = $request->input('color');

        if($note->save()) {
            return new NoteResource($note);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get note
        $note = Note::findOrFail($id);

        // Return single note as a resource
        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get note
        $note = Note::findOrFail($id);

        if($note->delete()) {
            return new NoteResource($note);
        }    
    }
}