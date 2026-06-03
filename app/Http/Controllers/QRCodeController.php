<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode as QRCodeFacade;
use Illuminate\Http\Response;

class QRCodeController extends Controller
{
    /**
     * Generate QR code for a unit
     */
    public function generate(Unit $unit): Response
    {
        $qrCode = QRCodeFacade::format('png')
            ->size(300)
            ->margin(2)
            ->generate($unit->qr_code);

        return response($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-'.$unit->kode_unit.'.png',
        ]);
    }

    /**
     * Download QR code for a unit
     */
    public function download(Unit $unit): Response
    {
        $qrCode = QRCodeFacade::format('png')
            ->size(300)
            ->margin(2)
            ->generate($unit->qr_code);

        return response($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-'.$unit->kode_unit.'.png',
        ]);
    }

    /**
     * Show QR code preview for a unit
     */
    public function show(Unit $unit): View
    {
        return view('qr-codes.show', compact('unit'));
    }

    /**
     * Scan QR code and redirect to unit detail
     */
    public function scan(Request $request): View
    {
        $qrCode = $request->input('qr_code');
        $unit = Unit::where('qr_code', $qrCode)->firstOrFail();

        return view('qr-codes.scan', compact('unit'));
    }
}
