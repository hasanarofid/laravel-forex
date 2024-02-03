<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratio extends Model
{
    use HasFactory;
    
    protected $fillable = ['type','transaction_api_id','currency', 'company', 'buy','sell'];
}

