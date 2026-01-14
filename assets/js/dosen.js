// Dosen Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Sidebar toggle for mobile
    const sidebarToggle = document.querySelector('[data-bs-toggle="collapse"][data-bs-target="#sidebarCollapse"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        });
    }

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-action="delete"]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const itemName = this.getAttribute('data-item-name') || 'item ini';
            if (confirm(`Apakah Anda yakin ingin menghapus ${itemName}?`)) {
                const form = this.closest('form');
                if (form) {
                    form.submit();
                } else {
                    window.location.href = this.href;
                }
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Rich text editor for announcement content
    const contentTextarea = document.querySelector('#content');
    if (contentTextarea) {
        // Add basic formatting buttons
        addFormattingToolbar(contentTextarea);
    }

    // Auto-save draft functionality
    const announcementForm = document.querySelector('#announcementForm');
    if (announcementForm) {
        let saveTimeout;
        const inputs = announcementForm.querySelectorAll('input, textarea, select');
        
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(function() {
                    saveDraft();
                }, 2000);
            });
        });
    }

    // Character counter for title and content
    const titleInput = document.querySelector('#title');
    const contentInput = document.querySelector('#content');
    
    if (titleInput) {
        addCharacterCounter(titleInput, 255);
    }
    
    if (contentInput) {
        addCharacterCounter(contentInput, 5000);
    }

    // Preview functionality
    const previewBtn = document.querySelector('#previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            showPreview();
        });
    }
});

// Add formatting toolbar for textarea
function addFormattingToolbar(textarea) {
    const toolbar = document.createElement('div');
    toolbar.className = 'formatting-toolbar mb-2';
    toolbar.innerHTML = `
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold')">
                <i class="bi bi-type-bold"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic')">
                <i class="bi bi-type-italic"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline')">
                <i class="bi bi-type-underline"></i>
            </button>
        </div>
        <div class="btn-group ms-2" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertList('ul')">
                <i class="bi bi-list-ul"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertList('ol')">
                <i class="bi bi-list-ol"></i>
            </button>
        </div>
    `;
    
    textarea.parentNode.insertBefore(toolbar, textarea);
}

// Format text function
function formatText(format) {
    const textarea = document.querySelector('#content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formattedText = '';
    switch(format) {
        case 'bold':
            formattedText = `**${selectedText}**`;
            break;
        case 'italic':
            formattedText = `*${selectedText}*`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText}</u>`;
            break;
    }
    
    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
}

// Insert list function
function insertList(type) {
    const textarea = document.querySelector('#content');
    const listItem = type === 'ul' ? '- Item\n' : '1. Item\n';
    
    const start = textarea.selectionStart;
    textarea.value = textarea.value.substring(0, start) + listItem + textarea.value.substring(start);
    textarea.focus();
    textarea.setSelectionRange(start + listItem.length, start + listItem.length);
}

// Add character counter
function addCharacterCounter(input, maxLength) {
    const counter = document.createElement('div');
    counter.className = 'character-counter text-muted small mt-1';
    counter.textContent = `0/${maxLength} karakter`;
    
    input.parentNode.appendChild(counter);
    
    input.addEventListener('input', function() {
        const length = this.value.length;
        counter.textContent = `${length}/${maxLength} karakter`;
        
        if (length > maxLength * 0.9) {
            counter.classList.add('text-warning');
        } else {
            counter.classList.remove('text-warning');
        }
        
        if (length > maxLength) {
            counter.classList.add('text-danger');
            counter.classList.remove('text-warning');
        } else {
            counter.classList.remove('text-danger');
        }
    });
}

// Save draft functionality
function saveDraft() {
    const form = document.querySelector('#announcementForm');
    if (!form) return;
    
    const formData = new FormData(form);
    const draftData = {};
    
    for (let [key, value] of formData.entries()) {
        draftData[key] = value;
    }
    
    localStorage.setItem('announcement_draft', JSON.stringify(draftData));
    
    // Show save indicator
    showSaveIndicator();
}

// Load draft functionality
function loadDraft() {
    const draftData = localStorage.getItem('announcement_draft');
    if (!draftData) return;
    
    const data = JSON.parse(draftData);
    const form = document.querySelector('#announcementForm');
    if (!form) return;
    
    Object.keys(data).forEach(key => {
        const input = form.querySelector(`[name="${key}"]`);
        if (input) {
            input.value = data[key];
        }
    });
    
    // Show draft loaded message
    showAlert('Draft dimuat dari penyimpanan lokal', 'info');
}

// Show save indicator
function showSaveIndicator() {
    const indicator = document.createElement('div');
    indicator.className = 'save-indicator position-fixed';
    indicator.style.cssText = 'top: 20px; left: 20px; z-index: 9999;';
    indicator.innerHTML = '<small class="badge bg-success"><i class="bi bi-check"></i> Draft tersimpan</small>';
    
    document.body.appendChild(indicator);
    
    setTimeout(() => {
        indicator.remove();
    }, 2000);
}

// Show preview
function showPreview() {
    const title = document.querySelector('#title').value;
    const content = document.querySelector('#content').value;
    const category = document.querySelector('#category_id option:checked').textContent;
    const priority = document.querySelector('#priority option:checked').textContent;
    
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal') || createPreviewModal());
    
    document.querySelector('#previewTitle').textContent = title || 'Judul Pengumuman';
    document.querySelector('#previewContent').innerHTML = content.replace(/\n/g, '<br>') || 'Konten pengumuman...';
    document.querySelector('#previewCategory').textContent = category;
    document.querySelector('#previewPriority').textContent = priority;
    
    previewModal.show();
}

// Create preview modal
function createPreviewModal() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'previewModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h4 id="previewTitle"></h4>
                    <div class="mb-3">
                        <span class="badge bg-secondary me-2" id="previewCategory"></span>
                        <span class="badge bg-info" id="previewPriority"></span>
                    </div>
                    <div id="previewContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    return modal;
}

// Utility functions
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        if (alertDiv.parentNode) {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }
    }, 5000);
}

function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// AJAX helper
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': window.csrfToken || ''
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Request failed:', error);
            showAlert('Terjadi kesalahan saat memproses permintaan', 'error');
            throw error;
        });
}