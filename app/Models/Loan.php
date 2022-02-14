<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_title',
        'user_id',
        'amount',
        'term',
        'start_date',
        'end_date',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function installments(){
        return $this->hasMany(Installment::class,'loan_id','id');
    }
}
