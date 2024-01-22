<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sending_account_id',
        'sending_user_id',
        'sending_account_currency',
        'receiving_account_id',
        'receiving_user_id',
        'receiving_account_currency',
        'money_in_lcm',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class,'sending_user_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiving_user_id');
    }

}
