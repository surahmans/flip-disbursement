<?php

namespace App;

use App\Helpers\Flip;
use App\Contracts\DisplayPrompt;
use App\Models\DisbursementResponse;

class CheckStatus extends DisplayPrompt
{
    protected $rules = [
        'transaction_id' => 'numeric',
    ];

    protected $ruleMessages = [
        'transaction_id.numeric' => 'Nomor transaksi hanya boleh mengandung angka',
    ];

    protected $parameters = [
        'transaction_id' => 'Nomor transaksi: ',
    ];

    public function processRequestParams(array $reqParams)
    {
        $disbursementLog = DisbursementResponse::findBy('transaction_id', $reqParams['transaction_id']);

        if (is_null($disbursementLog)) {
            die('Transaksi tersebut tidak dapat ditemukan dalam database!'. PHP_EOL);
        }

        if ($disbursementLog->status === 'SUCCESS') {
            echo sprintf('Transaksi tersebut sudah berhasil. Silahkan unduh bukti transaksinya di %s', $disbursementLog->receipt) . PHP_EOL;
            return;
        }

        $flip = new Flip(app()->config);
        $response = $flip->checkStatus($disbursementLog->transaction_id);
        $response = json_decode($response, true);

        if ($response['status'] === 'PENDING') {
            echo sprintf('Status transaksi masih "PENDING". Silahkan lakukan pengecekan secara berkala') . PHP_EOL;
            return;
        }

        switch ($response['status']) {
            case 'PENDING':
                echo sprintf('Status transaksi masih "PENDING". Silahkan lakukan pengecekan secara berkala') . PHP_EOL;
                break;
            case 'SUCCESS':
                $disbursementLog->markAsSuccess($response);
                break;
        }
    }
}
