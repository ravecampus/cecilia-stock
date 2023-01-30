<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowCredit;
use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['state_reason','date_from','date_to', 'remarks', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = EmployeeLeave::with('borrow')->where('deleted', 0)
        ->where('user_id', Auth::id())
        ->where('status', '!=', 2)
        ->where('status', '!=', 3)
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
            'leave_type' =>'required',
            'date_from' => 'required',
            'ext_from' => 'required',
            'date_to' => 'required',
            'ext_to' => 'required',
            'state_reason' => 'required|string',
            'remarks' => 'required|string',
        ]);

      
        
        $leave = EmployeeLeave::create([
            'leave_type_id' => $request->leave_type,
            'user_id' => Auth::id(),
            'date_from' => Carbon::parse($request->date_from)->format('Y-m-d'),
            'ext_from' => $request->ext_from,
            'date_to' => Carbon::parse($request->date_to)->format('Y-m-d'),
            'ext_to' => $request->ext_to,
            'state_reason' => $request->state_reason,
            'remarks' => $request->remarks,
            'leave' => $request->leave,
            'user_id' => Auth::id(),

        ]);

        $leave->ref_number ="CSF-".str_pad($leave->id, 6, "0", STR_PAD_LEFT);
        $leave->save();

        if($request->credits > 0){
            $borrow = BorrowCredit::create([
                'user_id' => Auth::id(),
                'employee_leave_id' => $leave->id,
                'leave_type_id'=> $request->borrow_id,
                'credits'=>$request->credits
            ]);
        }
        
        return response()->json($leave, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leave = EmployeeLeave::with('borrow','user','type', 'approvern')->find($id);
        return response()->json($leave, 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leave = EmployeeLeave::find($id);
        $leave->deleted = 1;
        $leave->save();

        return response()->json($leave, 200);
    }

    public function authConsumeLeave(){
        $leave = EmployeeLeave::where('deleted', 0)
            ->where('user_id', Auth::id())
            ->whereYear('created_at', Carbon::now()->format('Y'))->get();

        return response()->json($leave, 200);
    }
    public function authBarrowLeave(){
        $leave = BorrowCredit::where('user_id', Auth::id())
            ->whereYear('created_at', Carbon::now()->format('Y'))->get();

        return response()->json($leave, 200);
    }

    public function leaveApproved(Request $request){
        $columns = ['state_reason','date_from','date_to', 'remarks', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = EmployeeLeave::with('borrow', 'approvern')
        ->where('deleted', 0)
        ->where('status','>=', 2)
        ->where('user_id', Auth::id())
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

    public function getLeaveNotification(){
        $leave = EmployeeLeave::with('borrow','user', 'type')
        ->where('status', 0)
        ->where('deleted', 0)
        ->orderBy('created_at', 'desc')->get();
        return response()->json($leave, 200);
    }

    public function setReceived(Request $request){
        $leave = EmployeeLeave::find($request->id);
        $leave->status = 1;
        $leave->save();
        return response()->json($leave, 200);
    }
    // admin side
    public function application(Request $request){

        $columns = ['state_reason','date_from','date_to', 'remarks', 'created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = EmployeeLeave::with('borrow')
        ->where('deleted', 0)
        ->where('status', 1)
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
