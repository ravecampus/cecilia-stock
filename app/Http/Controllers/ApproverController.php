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

    public function leaveStatus(Request $request){

        $columns = ['state_reason','date_from','date_to', 'remarks', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = EmployeeLeave::with('borrow')
        ->where('deleted', 0)
        ->where('status','>=', 2)
        // ->whereYear('created_at', Carbon::now()->format('Y'))
        ->orderBy('created_at', $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('state_reason', 'like', '%'.$searchValue.'%')
                ->orWhere('remarks', 'like', '%'.$searchValue.'%')
                ->orWhere('ref_number', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }
}
