<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowCredit extends Model
{
    use HasFactory;

    protected $table = "borrow_credits";
    protected $fillable = [
        'user_id', 
        'employee_leave_id', 
        'leave_type_id', 
        'status',
        'credits',
        'deleted',
    ];
}
