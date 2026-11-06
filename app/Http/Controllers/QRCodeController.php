<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class QRCodeController extends Controller
{
    /**
     * Display QR codes management page
     */
    public function index(Request $request): View
    {
        $query = Unit::with('unitCategory');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_unit', 'like', "%{$search}%")
                  ->orWhere('nama_unit', 'like', "%{$search}%")
                  ->orWhere('nomor_urut', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('unit_category_id', $request->input('category_id'));
        }

        $units = $query->orderBy('nomor_urut')->orderBy('nama_unit')->paginate(24);

        // Get categories for filter
        $categories = UnitCategory::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        // Generate QR codes for all units
        $qrCodes = [];
        foreach ($units as $unit) {
            $qrCodes[$unit->id] = QrCode::format('svg')
                ->size(128)
                ->margin(1)
                ->generate($unit->qr_code);
        }

        return view('qr-codes.index', compact('units', 'categories', 'qrCodes'));
    }

    /**
     * Generate QR code for a unit
     */
    public function generate(Unit $unit): Response
    {
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($unit->qr_code);

        // Return inline image (for embedding in <img>)
        return response($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Download QR code for a unit
     */
    public function download(Unit $unit): Response
    {
        $qrCode = QrCode::format('png')
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

        if (! $qrCode) {
            return view('qr-codes.scan');
        }

        $unit = Unit::where('qr_code', $qrCode)->first();

        return view('qr-codes.scan', [
            'unit' => $unit,
            'searchQrCode' => $qrCode,
        ]);
    }
}
