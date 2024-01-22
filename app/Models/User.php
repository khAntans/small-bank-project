<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
        'password' => 'hashed',
    ];

    public function userCheckingAccounts()
    {
        return $this->hasMany(CheckingAccount::class, 'user_id');
    }

    public function userInvestmentAccount()
    {
        return $this->hasOne(InvestmentAccount::class, 'user_id');
    }
    public function userSentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sending_user_id');
    }
    public function userReceivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiving_user_id');
    }

    public function getUserTransactions()
    {
        return $this->userSentTransactions()->union($this->userReceivedTransactions()->toBase())->orderBy('created_at','DESC');

    }
}
