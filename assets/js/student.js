// Student Panel JavaScript

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

    // Search functionality with debounce
    const searchInput = document.querySelector('#searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            
            if (query.length >= 3) {
                searchTimeout = setTimeout(function() {
                    performSearch(query);
                }, 500);
            } else if (query.length === 0) {
                clearSearch();
            }
        });
    }

    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            applyFilter(filter);
            
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Category filter
    const categorySelect = document.querySelector('#categoryFilter');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const category = this.value;
            filterByCategory(category);
        });
    }

    // Priority filter
    const prioritySelect = document.querySelector('#priorityFilter');
    if (prioritySelect) {
        prioritySelect.addEventListener('change', function() {
            const priority = this.value;
            filterByPriority(priority);
        });
    }

    // Mark announcement as read
    const announcementLinks = document.querySelectorAll('.announcement-link');
    announcementLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            markAsRead(announcementId);
        });
    });

    // Share functionality
    const shareButtons = document.querySelectorAll('.share-btn');
    shareButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const announcementId = this.getAttribute('data-announcement-id');
            const title = this.getAttribute('data-title');
            shareAnnouncement(announcementId, title);
        });
    });

    // Load more functionality
    const loadMoreBtn = document.querySelector('#loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreAnnouncements();
        });
    }

    // Notification handling
    loadNotifications();
    
    // Check for new notifications every 30 seconds
    setInterval(function() {
        checkNewNotifications();
    }, 30000);

    // Initialize announcement cards animation
    animateAnnouncementCards();
});

// Search functionality
function performSearch(query) {
    showLoading();
    
    // Simulate API call
    setTimeout(function() {
        const announcements = document.querySelectorAll('.announcement-card');
        let visibleCount = 0;
        
        announcements.forEach(function(card) {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const content = card.querySelector('.card-text').textContent.toLowerCase();
            
            if (title.includes(query.toLowerCase()) || content.includes(query.toLowerCase())) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        hideLoading();
        
        if (visibleCount === 0) {
            showNoResults();
        } else {
            hideNoResults();
        }
    }, 300);
}

function clearSearch() {
    const announcements = document.querySelectorAll('.announcement-card');
    announcements.forEach(function(card) {
        card.style.display = 'block';
    });
    hideNoResults();
}

// Filter functionality
function applyFilter(filter) {
    const announcements = document.querySelectorAll('.announcement-card');
    
    announcements.forEach(function(card) {
        const priority = card.getAttribute('data-priority');
        
        if (filter === 'all' || priority === filter) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function filterByCategory(category) {
    if (!category) {
        window.location.href = '/student/announcements';
        return;
    }
    
    window.location.href = `/student/announcements?category=${category}`;
}

function filterByPriority(priority) {
    const announcements = document.querySelectorAll('.announcement-card');
    
    announcements.forEach(function(card) {
        const cardPriority = card.getAttribute('data-priority');
        
        if (!priority || cardPriority === priority) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Mark as read
function markAsRead(announcementId) {
    // Store in localStorage
    const readAnnouncements = JSON.parse(localStorage.getItem('readAnnouncements') || '[]');
    if (!readAnnouncements.includes(announcementId)) {
        readAnnouncements.push(announcementId);
        localStorage.setItem('readAnnouncements', JSON.stringify(readAnnouncements));
    }
    
    // Send to server
    makeRequest(`/api/announcements/${announcementId}/mark-read`, {
        method: 'POST'
    }).catch(error => console.error('Failed to mark as read:', error));
}

// Share functionality
function shareAnnouncement(announcementId, title) {
    const url = `${window.location.origin}/student/announcements/${announcementId}`;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(error => console.error('Error sharing:', error));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(function() {
            showAlert('Link berhasil disalin ke clipboard', 'success');
        }).catch(function() {
            showAlert('Gagal menyalin link', 'error');
        });
    }
}

// Load more announcements
function loadMoreAnnouncements() {
    const button = document.querySelector('#loadMoreBtn');
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memuat...';
    
    // Simulate API call
    setTimeout(function() {
        button.disabled = false;
        button.innerHTML = 'Muat Lebih Banyak';
        showAlert('Semua pengumuman telah dimuat', 'info');
    }, 1000);
}

// Notification handling
function loadNotifications() {
    makeRequest('/api/notifications/unread')
        .then(data => {
            updateNotificationBadge(data.count);
            displayNotifications(data.notifications);
        })
        .catch(error => console.error('Failed to load notifications:', error));
}

function checkNewNotifications() {
    makeRequest('/api/notifications/unread')
        .then(data => {
            const currentCount = parseInt(document.querySelector('.notification-badge')?.textContent || '0');
            if (data.count > currentCount) {
                updateNotificationBadge(data.count);
                showAlert('Anda memiliki notifikasi baru', 'info');
            }
        })
        .catch(error => console.error('Failed to check notifications:', error));
}

function updateNotificationBadge(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function displayNotifications(notifications) {
    const container = document.querySelector('#notificationContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (notifications.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-3">Tidak ada notifikasi</div>';
        return;
    }
    
    notifications.forEach(notification => {
        const item = document.createElement('div');
        item.className = 'notification-item';
        item.innerHTML = `
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-${notification.icon || 'bell'} text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-2">
                    <div class="fw-bold">${notification.title}</div>
                    <div class="small text-muted">${notification.message}</div>
                </div>
            </div>
        `;
        container.appendChild(item);
    });
}

// Animation
function animateAnnouncementCards() {
    const cards = document.querySelectorAll('.announcement-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'all 0.5s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(card => observer.observe(card));
}

// Loading states
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'loadingOverlay';
    loader.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
    loader.style.cssText = 'background: rgba(255,255,255,0.8); z-index: 9999;';
    loader.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
    
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.getElementById('loadingOverlay');
    if (loader) {
        loader.remove();
    }
}

function showNoResults() {
    const container = document.querySelector('#announcementContainer');
    if (!container) return;
    
    let noResults = document.querySelector('#noResults');
    if (!noResults) {
        noResults = document.createElement('div');
        noResults.id = 'noResults';
        noResults.className = 'text-center py-5';
        noResults.innerHTML = `
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">Tidak ada pengumuman yang ditemukan</p>
        `;
        container.appendChild(noResults);
    }
}

function hideNoResults() {
    const noResults = document.querySelector('#noResults');
    if (noResults) {
        noResults.remove();
    }
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
    
    setTimeout(function() {
        if (alertDiv.parentNode) {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }
    }, 5000);
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
            throw error;
        });
}