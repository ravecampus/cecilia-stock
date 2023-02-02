<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpNotification extends Model
{
    use HasFactory;

    protected $table = 'emp_notifications';
    protected $fillable = [
        'user_id', 
        'employee_leave_id',
        'notify',
        'leave_type_id'
    ];

    public function leave(){
        return $this->hasOne(EmployeeLeave::class, 'id', 'employee_leave_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function type(){
        return $this->hasOne(Leavetype::class, 'id', 'leave_type_id');
    }

}
