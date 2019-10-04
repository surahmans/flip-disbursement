<?php

namespace App;

use App\Helpers\Flip;
use App\Contracts\DisplayPrompt;

class Disbursement extends DisplayPrompt
{
    protected $rules = [
        'bank_code'      => 'string',
        'account_number' => 'numeric',
        'amount'         => 'numeric|min:10000',
        'remark'         => 'string|max:20'
    ];

    protected $ruleMessages = [
        'bank_code.string'       => 'Karakter yang diperbolehkan hanya berupa huruf',
        'account_number.numeric' => 'Nomor rekening tujuan hanya boleh mengandung angka',
        'amount.numeric'         => 'Nominal harus berupa angka',
        'amount.min'             => 'Minimal penarikan adalah 10000',
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
        echo $response;
    }
}
