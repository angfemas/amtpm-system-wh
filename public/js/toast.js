// Toast notification functions
function showToast(message, type = "info", title = null) {
    const container = document.getElementById("toast-container");
    if (!container) return;

    const typeClasses = {
        success: "bg-green-500 text-white",
        error: "bg-red-500 text-white",
        warning: "bg-yellow-500 text-white",
        info: "bg-blue-500 text-white",
    };

    const iconClasses = {
        success: "bi-check-circle-fill",
        error: "bi-x-circle-fill",
        warning: "bi-exclamation-triangle-fill",
        info: "bi-info-circle-fill",
    };

    const typeClass = typeClasses[type] || typeClasses.info;
    const icon = iconClasses[type] || iconClasses.info;

    const toast = document.createElement("div");
    toast.className =
        "fixed top-4 right-4 z-50 max-w-sm w-full transform transition-all duration-300 ease-out";
    toast.style.transform = "translateX(100%)";
    toast.style.opacity = "0";

    toast.innerHTML = `
        <div class="${typeClass} rounded-lg shadow-lg p-4 flex items-start space-x-3">
            <i class="bi ${icon} text-xl flex-shrink-0 mt-0.5"></i>
            <div class="flex-1 min-w-0">
                ${title ? `<p class="font-semibold">${title}</p>` : ""}
                <p class="text-sm ${title ? "mt-1" : ""}">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-4">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.transform = "translateX(0)";
        toast.style.opacity = "1";
    });

    setTimeout(() => {
        toast.style.transform = "translateX(100%)";
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function hideToast() {
    const container = document.getElementById("toast-container");
    if (container) {
        container.innerHTML = "";
    }
}

function toggleUnitStatus(unitId, unitCode, unitName, currentActive) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    if (!csrfToken) {
        console.error("CSRF token not found");
        showToast("Security token not found. Please refresh the page.", "error");
        return;
    }

    fetch(`/units/${unitId}/toggle-status`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        credentials: "same-origin",
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast(
                    `Unit ${unitCode} - ${unitName} sekarang ${data.data.is_active ? "Active" : "Inactive"}`,
                    "success",
                    "Status Updated"
                );

                const row = document.querySelector(`[data-unit-id="${unitId}"]`);
                if (row) {
                    const statusCell = row.querySelector(".status-indicator");
                    if (statusCell) {
                        statusCell.className =
                            "status-indicator px-2 py-1 text-xs font-medium rounded-full " +
                            (data.data.is_active
                                ? "bg-green-100 text-green-800"
                                : "bg-red-100 text-red-800");
                        statusCell.textContent = data.data.is_active ? "Active" : "Inactive";
                    }

                    const toggleIcon = row.querySelector("button[onclick*='toggleUnitStatus'] i.bi");
                    if (toggleIcon) {
                        toggleIcon.className =
                            "bi " +
                            (data.data.is_active ? "bi-toggle-on" : "bi-toggle-off");
                    }
                }
            } else {
                showToast(
                    data.message || "Failed to update unit status",
                    "error",
                    "Update Failed"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast(
                "An error occurred while updating unit status",
                "error",
                "Network Error"
            );
        });
}

function deleteUnit(event, unitId, unitCode, unitName) {
    if (event && event.preventDefault) {
        event.preventDefault();
    }

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    if (!csrfToken) {
        console.error("CSRF token not found");
        showToast("Security token not found. Please refresh the page.", "error");
        return;
    }

    fetch(`/units/${unitId}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        credentials: "same-origin",
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const row = document.querySelector(`[data-unit-id="${unitId}"]`);
                if (row) {
                    row.remove();
                }
                showToast(
                    `Unit ${unitCode} - ${unitName} berhasil dihapus.`,
                    "success",
                    "Deleted"
                );
            } else {
                showToast(
                    data.message || "Failed to delete unit",
                    "error",
                    "Delete Failed"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast(
                "An error occurred while deleting unit",
                "error",
                "Network Error"
            );
        });
}

function deleteCategory(event, el) {
    if (event && event.preventDefault) event.preventDefault();

    const id = el?.dataset?.id;
    const name = el?.dataset?.name || 'Category';

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found');
        showToast('Security token not found. Please refresh the page.', 'error');
        return;
    }

    fetch(`/unit-categories/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({}),
    })
        .then(async (response) => {
            const contentType = response.headers.get('content-type') || '';
            let data = null;
            try {
                if (contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    data = { success: false, message: await response.text() };
                }
            } catch (err) {
                data = { success: false, message: 'Invalid server response' };
            }

            if (!response.ok) {
                if (response.status === 419) {
                    showToast('Session expired. Please refresh the page and try again.', 'error', 'Authentication');
                } else {
                    showToast(data.message || 'Failed to delete category', 'error', 'Delete Failed');
                }
                return;
            }

            if (data && data.success) {
                let row = document.querySelector(`[data-category-id="${id}"]`);
                if (!row && el) {
                    row = el.closest && el.closest('tr');
                }
                if (row) {
                    row.remove();
                } else {
                    console.warn('Row for deleted category not found in DOM:', id);
                }
                showToast(data.message || `Kategori ${name} berhasil dihapus.`, 'success', 'Deleted');
            } else {
                showToast(data.message || 'Failed to delete category', 'error', 'Delete Failed');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            showToast('An error occurred while deleting category', 'error', 'Network Error');
        });
}

function deleteArea(event, el) {
    if (event && event.preventDefault) event.preventDefault();

    const id = el?.dataset?.id;
    const name = el?.dataset?.name || 'Area';

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found');
        showToast('Security token not found. Please refresh the page.', 'error');
        return;
    }

    fetch(`/warehouse-areas/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({}),
    })
        .then(async (response) => {
            const contentType = response.headers.get('content-type') || '';
            let data = null;
            try {
                if (contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    data = { success: false, message: await response.text() };
                }
            } catch (err) {
                data = { success: false, message: 'Invalid server response' };
            }

            if (!response.ok) {
                if (response.status === 419) {
                    showToast('Session expired. Please refresh the page and try again.', 'error', 'Authentication');
                } else {
                    showToast(data.message || 'Failed to delete area', 'error', 'Delete Failed');
                }
                return;
            }

            if (data && data.success) {
                let row = document.querySelector(`[data-area-id="${id}"]`);
                if (!row && el) {
                    row = el.closest && el.closest('tr');
                }
                if (row) {
                    row.remove();
                }
                showToast(data.message || `Area ${name} berhasil dihapus.`, 'success', 'Deleted');
            } else {
                showToast(data.message || 'Failed to delete area', 'error', 'Delete Failed');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            showToast('An error occurred while deleting area', 'error', 'Network Error');
        });
}

function setImportLoading(isLoading) {
    const importButton = document.getElementById('unit-import-submit');
    const fileInput = document.getElementById('unit-import-file');

    if (!importButton) {
        return;
    }

    if (isLoading) {
        importButton.disabled = true;
        importButton.textContent = 'Uploading...';
        if (fileInput) {
            fileInput.disabled = true;
        }
    } else {
        importButton.disabled = !fileInput?.files.length;
        importButton.textContent = importButton.dataset.originalText || 'Upload';
        if (fileInput) {
            fileInput.disabled = false;
        }
    }
}

function updateImportFeedback(message, type = 'info') {
    const feedback = document.getElementById('unit-import-feedback');
    if (!feedback) {
        return;
    }

    const colors = {
        success: 'border-green-200 bg-green-50 text-green-800',
        error: 'border-red-200 bg-red-50 text-red-800',
        warning: 'border-yellow-200 bg-yellow-50 text-yellow-800',
        info: 'border-gray-200 bg-gray-50 text-gray-700',
    };

    feedback.className = `mt-4 rounded-lg border p-4 text-sm ${colors[type] || colors.info}`;
    feedback.textContent = message;
    feedback.classList.remove('hidden');
}

function handleUnitImportSubmit(event) {
    event.preventDefault();

    const form = document.getElementById('unit-import-form');
    const fileInput = document.getElementById('unit-import-file');
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    if (!form || !fileInput) {
        return;
    }

    if (!fileInput.files.length) {
        showToast('warning', 'No file selected', 'Silakan pilih file sebelum mengimpor.');
        return;
    }

    if (!csrfToken) {
        showToast('error', 'CSRF token tidak ditemukan', 'Segarkan halaman dan coba lagi.');
        return;
    }

    if (!fileInput.files.length || !fileInput.files[0]) {
        updateImportFeedback('File tidak terdeteksi. Silakan pilih file lagi.', 'error');
        showToast('File tidak terdeteksi.', 'error', 'Import Gagal');
        return;
    }

    setImportLoading(true);
    updateImportFeedback('Mengunggah dan memproses file impor. Mohon tunggu...', 'info');

    const formData = new FormData(form);
    formData.set('file', fileInput.files[0]);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
        credentials: 'same-origin',
        body: formData,
    })
        .then(async (response) => {
            const data = await response.json();
            if (data.success) {
                updateImportFeedback(data.message, 'success');
                showToast(data.message, 'success', 'Import Selesai');
                fileInput.value = '';
                setImportLoading(false);
            } else {
                updateImportFeedback(data.message || 'Gagal mengimpor unit.', 'error');
                showToast(data.message || 'Gagal mengimpor unit.', 'error', 'Import Gagal');
                setImportLoading(false);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            updateImportFeedback('Terjadi kesalahan ketika mengunggah file impor.', 'error');
            showToast('Gagal mengirim import request.', 'error', 'Network Error');
            setImportLoading(false);
        });
}

function initUnitImportForm() {
    const form = document.getElementById('unit-import-form');
    const fileInput = document.getElementById('unit-import-file');

    if (!form || !fileInput) {
        return;
    }

    form.addEventListener('submit', handleUnitImportSubmit);
    fileInput.addEventListener('change', function () {
        const submitButton = document.getElementById('unit-import-submit');
        if (submitButton) {
            submitButton.disabled = !this.files.length;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initUnitImportForm();
});
