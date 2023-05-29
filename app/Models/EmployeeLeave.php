<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;

    protected $table = 'employee_leave';

    protected $fillable = [
        'ref_number', 
        'leave_type_id', 
        'user_id', 
        'state_reason',
        'date_from',
        'ext_from',
        'date_to',
        'ext_to',
        'remarks',
        'leave',
        'approver',
        'feedback',
        'approve_date',

    ];

    public function borrow()
    {
        return $this->hasOne(BorrowCredit::class, 'employee_leave_id', 'id')->with('leaveme');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id')->with('position');
    }

    public function type(){
        return $this->hasOne(Leavetype::class, 'id', 'leave_type_id');
    }
    public function approvern(){
        return $this->hasOne(User::class, 'id', 'approver');
    }

}
