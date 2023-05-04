<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['last_name','department','position', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = User::select('users.*', 'departments.name as department', 'positions.name as position')
        ->join('departments', 'departments.id','=', 'users.department_id')
        ->join('positions', 'positions.id','=', 'users.position_id')
        ->where('users.role', 0)->orderBy($columns[$column], $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%')
                ->orWhere('last_name', 'like', '%'.$searchValue.'%');
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
                'first_name' => 'required|string',
                'middle_name' => 'required|string',
                'last_name' => 'required|string',
                'department_id' => 'required',
                'position_id' => 'required',
                'gender' => 'required',
                'address' => 'required|string',
                'contact' => 'required|string',
                'username' => 'required|unique:users,username',
                'password' => 'required|string|min:6|confirmed',
            ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'contact' => $request->contact,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);
        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('position','department','leave', 'borrow', 'leaves')->find($id);
        return response()->json($user, 200);
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
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string',
            'department_id' => 'required',
            'position_id' => 'required',
            'gender' => 'required',
            'address' => 'required|string',
            'contact' => 'required|string',
        ]);

        $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->department_id = $request->department_id;
        $user->position_id = $request->position_id;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->contact = $request->contact;
        $user->username = $request->username;
        $user->save();
        return response()->json($user, 200);
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

    public function changePassword(Request $request){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json($user, 200);
    }
}
