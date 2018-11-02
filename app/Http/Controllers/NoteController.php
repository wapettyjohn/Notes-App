<?php

namespace App\Http\Controllers;

use App\Note;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Note as NoteResource;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notes = Note::where('user_id', $request->user()->id)->orderBy('updated_at', 'desc')->get();
        
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

        $note->id      = $request->note_id;
        $note->note    = $request->note;
        $note->color   = $request->color;
        $note->user_id = $request->user_id;

        if ($note->save()) {
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
        $note = Note::findOrFail($id);

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
        $note = Note::findOrFail($id);

        if ($note->delete()) {
            return new NoteResource($note);
        }    
    }
}