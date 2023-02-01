<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeLeave;

class DashboardController extends Controller
{
    public function adminDashEmployee(){
        $emp = User::where('users.role', 0)->get();
        return response()->json($emp, 200);
    }

    public function adminLeaveApp(){
        $lev = EmployeeLeave::where('deleted', 0)->where('status', 1)->get();
        return response()->json($lev, 200);
    }

    public function adminLeaveStatus(){
        $lev = EmployeeLeave::where('deleted', 0)->where('status','>=', 2)->get();
        return response()->json($lev, 200);
    }
}
