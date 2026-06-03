// Toast Notification System
let toastTimeout;

function showToast(type, title, message) {
    const toastHtml = `
        <div class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-300 ease-out opacity-0 pointer-events-none" id="toast">
            <div class="bg-white rounded-lg shadow-xl border-l-4 border-r-4 border-gray-200 p-4 min-w-[300px] max-w-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center">
                        ${getIconHtml(type)}
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900 ${getTextColor(type)}">
                            ${title}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            ${message}
                        </p>
                    </div>
                    <button onclick="hideToast()" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing toast if any
    const existingToast = document.getElementById('toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Add new toast to container
    const container = document.getElementById('toast-container');
    if (container) {
        container.innerHTML = toastHtml;
        
        // Trigger animation
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
                toast.classList.add('translate-x-0', 'opacity-100');
            }
        }, 100);
        
        // Auto hide after 5 seconds
        toastTimeout = setTimeout(() => {
            hideToast();
        }, 5000);
    }
}

function hideToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
        toast.classList.remove('translate-x-0', 'opacity-100');
        
        // Clear timeout
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        
        // Remove from DOM after animation
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

function toggleUnitStatus(unitId, unitCode, unitName, currentActive) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    const actionLabel = currentActive ? 'Deactivate' : 'Activate';

    fetch('/units/' + unitId + '/toggle-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const isActive = data.data.is_active;
            updateUnitStatusInTable(unitId, isActive);
            showToast(
                'success',
                'Status Updated',
                `Unit ${unitCode} - ${unitName} sekarang ${isActive ? 'Active' : 'Inactive'}`
            );
        } else {
            showToast('error', 'Update Failed', data.message || 'Gagal mengubah status unit.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Network Error', 'Gagal mengubah status unit. Silakan coba lagi.');
    });
}

function deleteUnit(event, unitId, unitCode, unitName) {
    event.preventDefault();
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    fetch('/units/' + unitId, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector('[data-unit-id="' + unitId + '"]');
            if (row) {
                row.remove();
            }
            showToast(
                'success',
                'Unit Deleted',
                `Unit ${unitCode} - ${unitName} berhasil dihapus.`
            );
        } else {
            showToast('error', 'Delete Failed', data.message || 'Gagal menghapus unit.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Network Error', 'Gagal menghapus unit. Silakan coba lagi.');
    });
}

function updateUnitStatusInTable(unitId, isActive) {
    const statusCell = document.querySelector('[data-unit-id="' + unitId + '"] .status-indicator');
    if (statusCell) {
        statusCell.className = 'status-indicator px-2 py-1 text-xs font-medium rounded-full ' + (isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
        statusCell.textContent = isActive ? 'Active' : 'Inactive';
    }
}

function getIconHtml(type) {
    const icons = {
        success: '<div class="bg-green-100 rounded-full p-2"><i class="bi bi-check-circle text-green-600 text-xl"></i></div>',
        error: '<div class="bg-red-100 rounded-full p-2"><i class="bi bi-x-circle text-red-600 text-xl"></i></div>',
        warning: '<div class="bg-yellow-100 rounded-full p-2"><i class="bi bi-exclamation-triangle text-yellow-600 text-xl"></i></div>',
        info: '<div class="bg-blue-100 rounded-full p-2"><i class="bi bi-info-circle text-blue-600 text-xl"></i></div>'
    };
    return icons[type] || icons.info;
}

function getTextColor(type) {
    const colors = {
        success: 'text-green-600',
        error: 'text-red-600',
        warning: 'text-yellow-600',
        info: 'text-blue-600'
    };
    return colors[type] || colors.info;
}
