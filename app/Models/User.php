<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'address',
        'position_id',
        'department_id',
        'contact',
        'role',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position(){
        return $this->hasOne(Position::class, 'id', 'position_id');
    }
    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function leave(){
        return $this->hasMany(EmployeeLeave::class, 'user_id', 'id')
        ->whereYear('created_at', Carbon::now()->format('Y'))
        ->where('status','!=', 3)
        ->where('deleted', 0);
    }

    public function borrow(){
        return $this->hasMany(BorrowCredit::class, 'user_id', 'id')
        ->where('status','!=', 3)
        ->where('deleted', 0)
        ->whereYear('created_at', Carbon::now()->format('Y'));
    }

    public function leaves(){
        return $this->hasMany(EmployeeLeave::class, 'user_id', 'id')
        ->where('deleted', 0)->where('status','!=', 3);
    }

    public function borrows(){
        return $this->hasMany(BorrowCredit::class, 'user_id', 'id')
        ->where('status','!=', 3)
        ->where('deleted', 0);
    }

}
