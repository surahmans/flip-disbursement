<?php

namespace App;

use App\Helpers\Flip;
use App\Models\Disbursement as DisbursementModel;
use App\Models\DisbursementResponse;
use App\Contracts\DisplayPrompt;

class Disbursement extends DisplayPrompt
{
    protected $rules = [
        'bank_code'      => 'string',
        'account_number' => 'numeric',
        'amount'         => 'numeric|min:10000|max:1000000000',
        'remark'         => 'string|max:20'
    ];

    protected $ruleMessages = [
        'bank_code.string'       => 'Karakter yang diperbolehkan hanya berupa huruf',
        'account_number.numeric' => 'Nomor rekening tujuan hanya boleh mengandung angka',
        'amount.numeric'         => 'Nominal harus berupa angka',
        'amount.min'             => 'Minimal penarikan adalah 10000',
        'amount.max'             => 'Minimal penarikan adalah 1000000000',
        'remark.string'          => 'Karakter yang diperbolehkan hanya berupa huruf',
        'remark.max'             => 'Berita penarikan maksimal 20 karakter',
    ];

    protected $parameters = [
        'bank_code'      => 'Kode bank: ',
        'account_number' => 'Nomor rekening: ',
        'amount'         => 'Jumlah penarikan: ',
        'remark'         => 'Berita penarikan: ',
    ];

    public function processRequestParams(array $reqParams)
    {
        $flip = new Flip(app()->config);
        $response = $flip->disbursement($reqParams);
        $response = json_decode($response, true);

        $disbursement = DisbursementModel::create($reqParams);
        $disbursementLog = DisbursementResponse::create(array_merge(
            $response,
            [
                'transaction_id'  => $response['id'],
                'disbursement_id' => $disbursement->id,
                'time_served'     => $response['status'] === 'SUCCESS' ? $response['time_served'] : null
            ]
        ));

        if ($response['status'] === 'SUCCESS') {
            echo sprintf('Selamat! Permintaan Anda berhasil diproses. Silahkan unduh bukti transaksinya di %s', $disbursementLog->receipt) . PHP_EOL;
            return;
        }

        echo sprintf('Status transaksi Anda "%s". Cek status tersebut menggunakan ID transaksi: %s', $disbursementLog->status, $disbursementLog->transaction_id) . PHP_EOL;
    }
}
