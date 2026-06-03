<!-- Toast Notification Component - Fixed Version -->
@once('showToast')
    <div id="toast" class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-300 ease-out {{ $show ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0 pointer-events-none' }}">
        <div class="bg-white rounded-lg shadow-xl border-l-4 border-r-4 border-gray-200 p-4 min-w-[300px] max-w-md">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center">
                    @if($type === 'success')
                        <div class="bg-green-100 rounded-full p-2">
                            <i class="bi bi-check-circle text-green-600 text-xl"></i>
                        </div>
                    @elseif($type === 'error')
                        <div class="bg-red-100 rounded-full p-2">
                            <i class="bi bi-x-circle text-red-600 text-xl"></i>
                        </div>
                    @elseif($type === 'warning')
                        <div class="bg-yellow-100 rounded-full p-2">
                            <i class="bi bi-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                    @else
                        <div class="bg-blue-100 rounded-full p-2">
                            <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                        </div>
                    @endif
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900 {{ $type === 'success' ? 'text-green-600' : ($type === 'error' ? 'text-red-600' : ($type === 'warning' ? 'text-yellow-600' : 'text-blue-600') }}">
                        {{ $title }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $message }}
                    </p>
                </div>
                <button onclick="hideToast()" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container (hidden by default) -->
    <div id="toast-container" class="fixed top-4 right-4 z-40 pointer-events-none"></div>
@endonce
