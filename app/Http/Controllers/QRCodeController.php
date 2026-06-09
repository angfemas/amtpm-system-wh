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
     * List units with their QR codes.
     */
    public function index(): View
    {
        $units = Unit::with('unitCategory')
            ->orderByRaw('nomor_urut IS NULL, nomor_urut ASC')
            ->orderBy('nama_unit')
            ->paginate(12);

        return view('qr-codes.index', compact('units'));
    }

    /**
     * Generate QR code for a unit
     */
    public function generate(Unit $unit): Response
    {
        $qrCode = QRCodeFacade::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($unit->qr_code);

        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    /**
     * Download QR code for a unit
     */
    public function download(Unit $unit): Response
    {
        $qrCode = QRCodeFacade::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($unit->qr_code);

        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="qr-'.$unit->kode_unit.'.svg"',
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
