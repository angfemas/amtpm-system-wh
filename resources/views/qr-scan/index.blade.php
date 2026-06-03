<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-qr-code-scan mr-3 text-purple-600"></i>
                    QR Scanner
                </h1>
                <p class="mt-1 text-sm text-gray-500">Scan QR codes to quickly access unit information</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" href="{{ route('units.index') }}" icon="list">
                    View All Units
                </x-industrial-button>
            </div>
        </div>
    </x-slot>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1E40AF 0%, #E60012 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .scanner-frame {
            width: 280px;
            height: 280px;
            border: 3px solid white;
            border-radius: 20px;
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .scanner-frame::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border: 2px solid #FF6B00;
            border-radius: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.02); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .scanner-line {
            position: absolute;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #FF6B00, transparent);
            animation: scan 2s linear infinite;
        }
        
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
        
        .action-button {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .action-button:active {
            transform: scale(0.95);
        }
        
        .quick-action {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .quick-action:active {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0.98);
        }
        
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .mobile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="text-white">
    <div x-data="{ 
        scanning: false, 
        scanResult: null,
        showManualModal: false,
        manualInput: '',
        recentScans: @json($recentScans ?? [])
    }" class="min-h-screen flex flex-col">
        
        <!-- Header -->
        <header class="bg-black bg-opacity-20 backdrop-filter backdrop-blur-lg px-4 py-3">
            <div class="flex items-center justify-between">
                <button class="p-2" onclick="history.back()">
                    <i class="bi bi-arrow-left text-xl"></i>
                </button>
                <h1 class="text-lg font-semibold">QR Scanner</h1>
                <button class="p-2" @click="showManual = !showManual">
                    <i class="bi bi-list-ul text-xl"></i>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col items-center justify-center px-4 py-8">
            
            <!-- Scanner Frame -->
            <div class="scanner-frame mb-8" x-show="!scanResult">
                <div class="scanner-line"></div>
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <i class="bi bi-qr-code-scan text-6xl mb-4 opacity-50"></i>
                        <p class="text-sm opacity-75">Align QR code within frame</p>
                    </div>
                </div>
            </div>

            <!-- Scan Result -->
            <div x-show="scanResult" x-transition class="mobile-card p-6 mb-8 w-full max-w-sm">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Unit Found!</h2>
                    <p class="text-gray-600 mb-1" x-text="scanResult?.code"></p>
                    <p class="text-lg font-medium text-gray-900" x-text="scanResult?.name"></p>
                    
                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <button class="btn-primary text-sm py-2">
                            <i class="bi bi-eye mr-1"></i> View Details
                        </button>
                        <button class="btn-secondary text-sm py-2 text-gray-700">
                            <i class="bi bi-wrench mr-1"></i> Maintenance
                        </button>
                    </div>
                    
                    <button @click="scanResult = null" class="mt-4 text-gray-500 text-sm">
                        Scan Another
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-6 mb-8">
                <button class="action-button quick-action" @click="showManual = !showManual">
                    <i class="bi bi-keyboard text-white"></i>
                </button>
                
                <button class="action-button bg-white text-honda-red" @click="startScan">
                    <i class="bi bi-camera-fill"></i>
                </button>
                
                <button class="action-button quick-action" @click="toggleFlash">
                    <i class="bi bi-lightbulb-fill text-white"></i>
                </button>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 gap-3 w-full max-w-sm mb-8">
                <button class="quick-action p-4 rounded-xl text-center">
                    <i class="bi bi-clock-history text-2xl mb-2"></i>
                    <p class="text-sm">Recent Scans</p>
                </button>
                <button class="quick-action p-4 rounded-xl text-center">
                    <i class="bi bi-qr-code text-2xl mb-2"></i>
                    <p class="text-sm">Generate QR</p>
                </button>
            </div>
        </main>

        <!-- Manual Input Modal -->
        <div x-show="showManual" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end">
            
            <div x-show="showManual"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform translate-y-full"
                 x-transition:enter-end="transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="transform translate-y-0"
                 x-transition:leave-end="transform translate-y-full"
                 class="bg-white rounded-t-3xl w-full max-h-[80vh] overflow-y-auto">
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Manual Entry</h3>
                        <button @click="showManual = false" class="text-gray-400">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Code</label>
                            <input type="text" 
                                   placeholder="Enter unit code (e.g., HT-0234)"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent text-gray-900">
                        </div>
                        
                        <button class="btn-primary w-full py-3" @click="searchUnit">
                            <i class="bi bi-search mr-2"></i> Search Unit
                        </button>
                    </div>
                    
                    <!-- Recent Scans -->
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Recent Scans</h4>
                        <div class="space-y-2">
                            <template x-for="scan in recentScans" :key="scan.code">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="scan.name"></p>
                                        <p class="text-xs text-gray-500" x-text="scan.code + ' • ' + scan.time"></p>
                                    </div>
                                    <button class="text-honda-red">
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <div class="grid grid-cols-4 py-2">
                <button class="flex flex-col items-center py-2 text-gray-600">
                    <i class="bi bi-house text-xl mb-1"></i>
                    <span class="text-xs">Home</span>
                </button>
                <button class="flex flex-col items-center py-2 text-honda-red">
                    <i class="bi bi-qr-code-scan text-xl mb-1"></i>
                    <span class="text-xs">Scan</span>
                </button>
                <button class="flex flex-col items-center py-2 text-gray-600">
                    <i class="bi bi-truck text-xl mb-1"></i>
                    <span class="text-xs">Units</span>
                </button>
                <button class="flex flex-col items-center py-2 text-gray-600">
                    <i class="bi bi-person text-xl mb-1"></i>
                    <span class="text-xs">Profile</span>
                </button>
            </div>
        </nav>
    </div>

    <script>
        function startScan() {
            Alpine.store.app.scanning = true;
            
            // Check if device has camera
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'environment' } 
                })
                .then(function(stream) {
                    // Camera access granted
                    setTimeout(() => {
                        // Simulate QR code detection
                        fetch('/qr-scan/process', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                action: 'scan',
                                timestamp: new Date().toISOString()
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Alpine.store.app.scanning = false;
                            if (data.success) {
                                Alpine.store.app.scanResult = data.unit;
                                // Add to recent scans
                                Alpine.store.app.recentScans.unshift({
                                    code: data.unit.code,
                                    name: data.unit.name,
                                    time: 'Just now'
                                });
                                
                                // Show notification
                                showNotification('success', 'Unit scanned successfully!');
                                
                                // Redirect to unit details after 2 seconds
                                setTimeout(() => {
                                    window.location.href = `/units/${data.unit.id}`;
                                }, 2000);
                            } else {
                                showNotification('error', data.message || 'Failed to scan unit');
                            }
                        })
                        .catch(error => {
                            Alpine.store.app.scanning = false;
                            showNotification('error', 'Scan failed. Please try again.');
                        });
                    }, 3000);
                })
                .catch(function(error) {
                    Alpine.store.app.scanning = false;
                    showNotification('error', 'Camera access denied');
                });
            } else {
                // Fallback to manual input
                Alpine.store.app.showManualModal = true;
                Alpine.store.app.scanning = false;
            }
        }
        
        function toggleFlash() {
            // Toggle flashlight functionality
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                const track = document.querySelector('video')?.srcObject?.getVideoTracks()[0];
                if (track && track.getCapabilities().torch) {
                    track.applyConstraints({
                        advanced: [{ torch: !track.getSettings().torch }]
                    });
                }
            }
        }
        
        function searchUnit() {
            const unitCode = Alpine.store.app.manualInput.trim();
            if (!unitCode) {
                showNotification('warning', 'Please enter a unit code');
                return;
            }
            
            fetch('/qr-scan/search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    code: unitCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Alpine.store.app.scanResult = data.unit;
                    Alpine.store.app.recentScans.unshift({
                        code: data.unit.code,
                        name: data.unit.name,
                        time: 'Just now'
                    });
                    Alpine.store.app.showManualModal = false;
                    Alpine.store.app.manualInput = '';
                    showNotification('success', 'Unit found successfully!');
                    
                    setTimeout(() => {
                        window.location.href = `/units/${data.unit.id}`;
                    }, 1500);
                } else {
                    showNotification('error', data.message || 'Unit not found');
                }
            })
            .catch(error => {
                showNotification('error', 'Search failed. Please try again.');
            });
        }
        
        function showNotification(type, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm w-full ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-yellow-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="bi ${
                        type === 'success' ? 'bi-check-circle' :
                        type === 'error' ? 'bi-x-circle' :
                        'bi-exclamation-triangle'
                    } mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Initialize camera when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check for recent scans from server
            fetch('/qr-scan/recent')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Alpine.store.app.recentScans = data.scans;
                    }
                });
        });
    </script>
</body>
</html>
