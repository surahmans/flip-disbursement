<?php

namespace App;

require_once __DIR__ . '/bootstrap/app.php';

echo "Apa yang Anda butuhkan?". PHP_EOL;
echo "[1] Pencairan dana". PHP_EOL;
echo "[2] Cek status". PHP_EOL;
$option = readline("Masukan pilihan: ");

switch ($option) {
    case 1:
        return (new Disbursement)->run();
    case 2:
        return (new CheckStatus)->run();
    default:
        echo "Pilihan yang Anda masukan tidak tersedia!". PHP_EOL;
}
