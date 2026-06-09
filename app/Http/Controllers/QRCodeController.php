<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    /**
     * List units with their QR codes.
     */
    public function index(Request $request): View
    {
        $query = Unit::with('unitCategory');

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($categoryId = $request->get('category_id')) {
            $query->byCategory((int) $categoryId);
        }

        $units = $query->orderByRaw('nomor_urut IS NULL, nomor_urut ASC')
            ->orderBy('nama_unit')
            ->paginate(12)
            ->withQueryString();

        $categories = UnitCategory::active()->orderBy('name')->pluck('name', 'id');

        $qrCodes = [];
        foreach ($units as $unit) {
            $qrCodes[$unit->id] = $this->svg($this->qrValue($unit), 140);
        }

        return view('qr-codes.index', compact('units', 'categories', 'qrCodes'));
    }

    /**
     * Generate QR code for a unit
     */
    public function generate(Unit $unit): Response
    {
        return $this->svgResponse($unit);
    }

    /**
     * Download QR code for a unit
     */
    public function download(Unit $unit): Response
    {
        return $this->svgResponse($unit);
    }

    /**
     * Show QR code preview for a unit
     */
    public function show(Unit $unit): View
    {
        $qrSvg = $this->svg($this->qrValue($unit), 300);

        return view('qr-codes.show', compact('unit', 'qrSvg'));
    }

    /**
     * Resolve the value to encode in a unit's QR code.
     */
    private function qrValue(Unit $unit): string
    {
        return $unit->qr_code ?: ($unit->kode_unit ?: 'UNIT-'.$unit->id);
    }

    /**
     * Render an SVG QR code (no imagick dependency).
     */
    private function svg(string $value, int $size): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->generate($value);
    }

    /**
     * Build a downloadable SVG QR response for a unit.
     */
    private function svgResponse(Unit $unit): Response
    {
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($this->qrValue($unit));

        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="qr-'.$unit->kode_unit.'.svg"',
        ]);
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
