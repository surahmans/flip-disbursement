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

    public function markAsSuccess(array $response)
    {
        try {
            $this->update([
                'status'      => $response['status'],
                'receipt'     => $response['receipt'],
                'time_served' => $response['time_served']
            ]);

            echo sprintf('Selamat! Permintaan Anda berhasil diproses. Silahkan unduh bukti transaksinya di %s', $this->receipt) . PHP_EOL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
