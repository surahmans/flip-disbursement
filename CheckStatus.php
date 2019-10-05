<?php

namespace App;

use App\Helpers\Flip;
use App\Contracts\DisplayPrompt;

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
        $flip = new Flip(app()->config);
        $response = $flip->checkStatus($reqParams['transaction_id']);
        echo $response;
    }
}
