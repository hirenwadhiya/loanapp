<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_id',
        'installment_number',
        'installment_amount',
        'installment_date',
        'payment_status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function loan(){
        return $this->belongsTo(Loan::class,'loan_id','id');
    }
}
