<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowCredit;
use App\Models\EmployeeLeave;
use App\Models\EmpNotification;
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
        if($leave->save()){
            EmpNotification::create([
                'user_id' => $leave->user_id,
                'employee_leave_id' => $leave->id,
                'leave_type_id' => $leave->leave_type_id,
                'notify' => 0
                ]);
        }
        $borr = BorrowCredit::where('employee_leave_id', $request->id)->first();
        if(isset($borr)){
            $borr->status = $request->status;
            $borr->save();
        }

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

    public function getEmployeeNotification(){
        $notify = EmpNotification::with('leave','user', 'type')
        ->where('user_id', Auth::id())
        ->where('notify',0)
        ->orderBy('created_at', 'desc')->get();
        return response()->json($notify, 200);
    }

    public function changeNotify(Request $request){
        $notify = EmpNotification::find($request->id);
        $notify->notify = 1;
        $notify->save();
        return response()->json($notify, 200);
    }

    public function cancelLeave(Request $request){
        $columns = ['state_reason','date_from','date_to', 'remarks', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = EmployeeLeave::with('borrow', 'approvern')
        ->where('deleted', 1)
        ->where('user_id', Auth::id())
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

    public function leaveDelete(Request $request){
        $leave = EmployeeLeave::find($request->id);
        $leave->delete();
        $borr = BorrowCredit::where('employee_leave_id', $request->id)->first();
        if(isset($borr)){
            $borr->deleted();
        }
        return response()->json($leave, 200);
    }
}
