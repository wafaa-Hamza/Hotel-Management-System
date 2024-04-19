<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrCodeController extends Controller
{
    public function GenerateQr_Code()
{   
    $this->authorize('create-qrcode');

    return QrCode::size(200)
    ->backgroundColor(255, 255, 0)
    ->color(0, 0, 255)
    ->margin(1)
    ->generate(
        'https://masasoft.net/',
    );
}


public function download()
{
    $this->authorize('create-qrcode');

    return response()->streamDownload(
        function () {
            echo QrCode::size(200)
                ->format('png')
                ->generate('https://harrk.dev');
        },
        'qr-code.png',
        [
            'Content-Type' => 'image/png',
        ]
    );
}

 
}
