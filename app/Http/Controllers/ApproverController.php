<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowCredit;
use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApproverController extends Controller
{
    public function changeStatus(Request $request){
        $request->validate(['feedback'=>'required|string']);
        
        $leave = EmployeeLeave::find($request->id);
        $leave->status = $request->status;
        $leave->feedback = $request->feedback;
        $leave->approver = Auth::id();
        $leave->approve_date = Carbon::now()->format('Y-m-d');
        $leave->save();
        return response()->json($leave, 200);
    }
}
