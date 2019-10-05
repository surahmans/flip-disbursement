<?php

namespace App\Models;

use App\Contracts\Model;

class DisbursementResponse extends Model
{
    protected $table = 'disbursement_responses';

    protected $fillable = [
        'disbursement_id',
        'disbursement_id',
        'transaction_id',
        'status',
        'amount',
        'timestamp',
        'bank_code',
        'account_number',
        'beneficiary_name',
        'remark',
        'receipt',
        'time_served',
        'fee'
    ];
}
