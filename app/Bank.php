<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = [
        'name', 'interest_rate', 'comparison_rate', 'annual_fees', 'monthly_fees', 'special'
    ];

}
