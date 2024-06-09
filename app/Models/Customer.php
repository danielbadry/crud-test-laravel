<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname', 'lastname', 'date_of_birth', 'phone_number', 'email', 'bank_account_number'
    ];

    public static $rules = [
        'firstname' => 'required',
        'lastname' => 'required',
        'date_of_birth' => 'required|date',
        'phone_number' => 'required|unique:customers,phone_number',
        'email' => 'required|email|unique:customers,email',
        'bank_account_number' => 'required|unique:customers,bank_account_number',
    ];
}
