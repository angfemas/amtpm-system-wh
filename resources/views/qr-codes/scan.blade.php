<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-qr-code-scan mr-3 text-purple-600"></i>
                    QR Scan
                </h1>
                <p class="mt-1 text-sm text-gray-500">Operator-friendly QR scanning untuk membuka unit</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium transition-colors">
                    <i class="bi bi-house mr-2"></i>Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    @unless(isset($unit))
        <!-- Camera Scan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">QR Camera Scanner</h2>
                    <p class="text-sm text-gray-500">Arahkan kamera ke QR code lalu tunggu deteksi otomatis.</p>
                </div>
                <div class="space-x-2">
                    <button type="button" onclick="startScan()" class="inline-flex items-center px-4 py-2 rounded-xl bg-honda-red text-white font-semibold hover:bg-red-700 transition-colors shadow-sm">
                        <i class="bi bi-camera-fill mr-2"></i> Start
                    </button>
                    <button type="button" onclick="stopScan()" class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-colors shadow-sm">
                        <i class="bi bi-stop-circle mr-2"></i> Stop
                    </button>
                </div>
            </div>

            <div class="scanner-frame mb-4 overflow-hidden rounded-xl relative bg-black">
                <video id="qr-video" playsinline class="w-full h-72 object-cover bg-black"></video>
                <canvas id="qr-canvas" class="hidden"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="text-center text-white/80">
                        <i class="bi bi-qr-code-scan text-5xl mb-3"></i>
                        <p class="text-sm">Tunggu deteksi QR code...</p>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-500">Jika kamera tidak bisa, gunakan input manual di bawah ini.</p>
        </div>

        <!-- Scan Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('qr-codes.scan') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan QR Code</label>
                    <input
                        type="text"
                        name="qr_code"
                        value="{{ old('qr_code', $searchQrCode ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="Contoh: UNIT-ABC123"
                        required
                    >
                </div>
                <button type="submit" class="touch-target inline-flex items-center justify-center px-6 py-3 rounded-xl bg-honda-red text-white font-semibold hover:bg-red-700 transition-colors shadow-sm">
                    <i class="bi bi-qr-code-scan mr-2"></i>
                    Scan
                </button>
            </form>
        </div>

        @if(isset($searchQrCode) && empty($unit))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-xl">
                <p class="text-sm text-yellow-700">QR code "{{ $searchQrCode }}" tidak ditemukan. Silakan coba lagi atau gunakan kamera.</p>
            </div>
        @endif
    @endunless

    <!-- Result -->
    @isset($unit)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-14 h-14 bg-purple-50 border border-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-truck text-purple-700 text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Unit Ditemukan</div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $unit->nama_unit }}</h2>
                        <div class="mt-1 text-sm text-gray-600">
                            <span class="font-semibold text-gray-900 font-mono">{{ $unit->kode_unit }}</span>
                            • {{ $unit->unitCategory->name ?? '-' }}
                            • {{ $unit->warehouseArea->name ?? '-' }}
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <x-status-badge status="{{ $unit->status }}" :pulse="$unit->is_overdue" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('units.show', $unit) }}" class="flex items-center justify-center px-5 py-3 rounded-xl font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                        <i class="bi bi-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a
                        href="{{ route('maintenance-logs.create', ['unit_id' => $unit->id]) }}"
                        class="flex items-center justify-center px-5 py-3 rounded-xl font-semibold bg-honda-red hover:bg-red-700 text-white transition-colors shadow-sm"
                    >
                        <i class="bi bi-file-earmark-check mr-2"></i>
                        Isi Checklist
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="text-sm text-gray-600">Kilometer</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">{{ $unit->kilometer ?? '-' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="text-sm text-gray-600">Hour Meter</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">{{ $unit->hour_meter ?? '-' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="text-sm text-gray-600">Next Due</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">
                        {{ $unit->maintenance_due_date ? $unit->maintenance_due_date->format('d M Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    @endisset

<script>
    let qrWorker = null;
    let qrStream = null;
    let scanActive = false;
    let scanTimer = null;

    function ensureQrWorker() {
        if (qrWorker) {
            return qrWorker;
        }

        qrWorker = new Worker('/js/qr-scanner-worker.js');
        qrWorker.onmessage = function(event) {
            if (!scanActive) {
                return;
            }

            const { success, data } = event.data;
            if (success && data) {
                scanActive = false;
                stopScan();
                window.location.href = `/scan?qr_code=${encodeURIComponent(data)}`;
            }
        };

        return qrWorker;
    }

    function startScan() {
        if (scanActive) {
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showNotification('warning', 'Kamera tidak tersedia di perangkat ini.');
            return;
        }

        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const ctx = canvas.getContext('2d');
        const scanWidth = 320;
        const scanHeight = 240;

        canvas.width = scanWidth;
        canvas.height = scanHeight;

        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment',
                width: { ideal: 640 },
                height: { ideal: 480 }
            }
        })
        .then(stream => {
            qrStream = stream;
            video.srcObject = stream;
            video.play().catch(() => {});
            scanActive = true;
            ensureQrWorker();
            scanFrame();
            showNotification('success', 'Kamera aktif. Arahkan ke QR code.');
        })
        .catch(() => {
            showNotification('error', 'Akses kamera ditolak.');
        });
    }

    function scanFrame() {
        if (!scanActive) {
            return;
        }

        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const ctx = canvas.getContext('2d');

        try {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            ensureQrWorker().postMessage({
                width: canvas.width,
                height: canvas.height,
                imageBuffer: imageData.data.buffer
            }, [imageData.data.buffer]);
        } catch (error) {
            // ignore frame errors and continue scanning
        }

        scanTimer = setTimeout(scanFrame, 120);
    }

    function stopScan() {
        scanActive = false;

        if (scanTimer) {
            clearTimeout(scanTimer);
            scanTimer = null;
        }

        if (qrStream) {
            qrStream.getTracks().forEach(track => track.stop());
            qrStream = null;
        }

        const video = document.getElementById('qr-video');
        if (video) {
            video.srcObject = null;
        }
    }
</script>
</x-industrial-layout>

