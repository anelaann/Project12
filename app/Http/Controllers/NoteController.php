<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index()
    {
       $Note = Note::latest()->paginate(5);

       return view('index', compact('Note'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function create()
    {
        return view('create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'text' => 'required'
        ]);

        Note::create(array_merge($request->all(), ['status' => 'active']));

        $Note = Note::latest()->paginate(5);

        return view('index', compact('Note'))
        ->with('i', (request()->input('page', 1) - 1) * 5)
        ->with('sucess', 'Note updated sucessfully!');
    }

    public function show($id)
    {
        $Note = Note::find($id);

        return view('show', compact('Note'));
    }

    public function edit($id)
    {
        $Note = Note::find($id);
        return view('edit', compact('Note', 'id'));
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'title' => 'required',
            'text' => 'required',
            'status' => 'required'
        ]);


        $Note = Note::find($id);
        $Note->title = request('title');
        $Note->text = request('text');
        $Note->status = request('status');
        $Note->save();

        
        $Note = Note::latest()->paginate(5);
        return view('index', compact('Note'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function destroy($id)
    {
        $Note = Note::find($id);
        if($Note->status === 'active'){
            $Note->status = 'deleted';
        }else{
            $Note->status = 'active';
        }
        $Note->save();

        
        $Note = Note::latest()->paginate(5);
        return view('index', compact('Note'))
        ->with('i', (request()->input('page', 1) - 1) * 5);

    }
}