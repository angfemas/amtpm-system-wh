@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">QR Code - {{ $unit->nomor_nama }}</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ route('qr-codes.download', $unit->id) }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                    </div>
                    <div class="mb-3">
                        <h6>Kode Unit: {{ $unit->kode_unit }}</h6>
                        <p class="text-muted">{{ $unit->qr_code }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Kategori: {{ $unit->unitCategory->name ?? 'N/A' }}</h6>
                    </div>
                    <div class="mb-3">
                        <h6>Area: {{ $unit->warehouseArea->name ?? 'N/A' }}</h6>
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('qr-codes.download', $unit->id) }}" class="btn btn-primary">
                            <i class="bi bi-download"></i> Download QR Code
                        </a>
                        <a href="{{ route('units.show', $unit->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
