<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionApi extends Model
{
    use HasFactory;

    protected $table = 'transaction_api';

    public $timestamps = false;

    public function ratios()
    {
        return $this->hasMany(Ratio::class, 'transaction_api_id');
    }
}
