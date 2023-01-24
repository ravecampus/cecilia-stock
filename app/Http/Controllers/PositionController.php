<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['name', 'department', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = Position::select('positions.*', 'departments.name as department')
        ->join('departments', 'departments.id', '=','positions.department_id')
        ->orderBy($columns[$column], $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('name', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'department_id'=>'required',
            ]);
        $pos = Position::create([
            'name'=>$request->name,
            'department_id'=>$request->department_id,
        ]);
        
        return response()->json($pos, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string',
            'department_id'=>'required',
            ]);
        $pos = Position::find($id);
        $pos->name = $request->name;
        $pos->department_id = $request->department_id;
        $pos->save();
        
        return response()->json($pos, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
