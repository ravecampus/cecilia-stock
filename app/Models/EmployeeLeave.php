<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;

    protected $table = 'employee_leave';

    protected $fillable = [
        'leave_type_id', 
        'user_id', 
        'state_reason',
        'date_from',
        'ext_from',
        'date_to',
        'ext_to',
        'remarks',
        'leave'
    ];

    public function borrow()
    {
        return $this->hasOne(BorrowCredit::class, 'employee_leave_id', 'id');
    }
}
