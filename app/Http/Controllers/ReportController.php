<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReportController extends Controller
{
    public function leaveReport(Request $request){

        $columns = ['last_name','department','position', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = User::with('leave', 'borrow')->select('users.*', 'departments.name as department', 'positions.name as position')
        ->join('departments', 'departments.id','=', 'users.department_id')
        ->join('positions', 'positions.id','=', 'users.position_id')
        ->where('users.role', 0)->orderBy('last_name', 'asc');
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%')
                ->orWhere('last_name', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->get();
        return ['data'=>$projects, 'draw'=> $request->draw];
    }
}
