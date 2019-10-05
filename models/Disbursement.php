<?php

namespace App\Models;

use App\Contracts\Model;

class Disbursement extends Model
{
    protected $table = 'disbursements';

    protected $fillable = [
        'bank_code',
        'account_number',
        'amount',
        'remark'
    ];
}
