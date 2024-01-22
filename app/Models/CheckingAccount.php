<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckingAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'balance_in_lcm',
        'currency_iso',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
