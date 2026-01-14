/**
 * APAO Mobile Enhancements
 * JavaScript enhancements for mobile responsiveness
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================================================
    // Mobile Detection and Setup
    // ==========================================================================
    
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 576;
    
    // Add mobile class to body
    if (isMobile) {
        document.body.classList.add('mobile-device');
    }
    if (isSmallMobile) {
        document.body.classList.add('small-mobile-device');
    }
    
    // ==========================================================================
    // Enhanced Table Responsiveness
    // ==========================================================================
    
    function enhanceTableResponsiveness() {
        const tables = document.querySelectorAll('.table');
        
        tables.forEach(table => {
            if (isMobile) {
                // Add mobile-friendly classes
                table.classList.add('table-mobile-enhanced');
                
                // Wrap table in responsive container if not already wrapped
                if (!table.closest('.table-responsive')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'table-responsive';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
                
                // Add data labels for mobile stacking
                const headers = table.querySelectorAll('th');
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach((cell, index) => {
                        if (headers[index] && !cell.hasAttribute('data-label')) {
                            cell.setAttribute('data-label', headers[index].textContent.trim());
                        }
                    });
                });
            }
        });
    }
    
    // ==========================================================================
    // Form Enhancements
    // ==========================================================================
    
    function enhanceFormResponsiveness() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            if (isMobile) {
                // Add mobile form class
                form.classList.add('mobile-form');
                
                // Enhance button groups
                const buttonGroups = form.querySelectorAll('.btn-group');
                buttonGroups.forEach(group => {
                    group.classList.add('btn-group-mobile');
                });
                
                // Add touch-friendly classes to inputs
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.classList.add('mobile-input');
                });
            }
        });
    }
    
    // ==========================================================================
    // Card Enhancements
    // ==========================================================================
    
    function enhanceCardResponsiveness() {
        const cards = document.querySelectorAll('.card');
        
        cards.forEach(card => {
            if (isMobile) {
                card.classList.add('mobile-card');
                
                // Enhance card headers
                const header = card.querySelector('.card-header');
                if (header) {
                    header.classList.add('mobile-card-header');
                }
                
                // Enhance card bodies
                const body = card.querySelector('.card-body');
                if (body) {
                    body.classList.add('mobile-card-body');
                }
            }
        });
    }
    
    // ==========================================================================
    // Navigation Enhancements
    // ==========================================================================
    
    function enhanceNavigation() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebar && isMobile) {
            // Add swipe gesture support
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            
            // Touch events for swipe gestures
            document.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isDragging = true;
            });
            
            document.addEventListener('touchmove', function(e) {
                if (!isDragging) return;
                currentX = e.touches[0].clientX;
            });
            
            document.addEventListener('touchend', function(e) {
                if (!isDragging) return;
                isDragging = false;
                
                const diffX = currentX - startX;
                const threshold = 100;
                
                // Swipe right to open sidebar
                if (diffX > threshold && startX < 50 && !sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
                
                // Swipe left to close sidebar
                if (diffX < -threshold && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
        }
    }
    
    // ==========================================================================
    // Search and Filter Enhancements
    // ==========================================================================
    
    function enhanceSearchAndFilters() {
        const searchInputs = document.querySelectorAll('input[type="search"], input[placeholder*="Cari"]');
        const filterSelects = document.querySelectorAll('select[id*="Filter"], select[id*="filter"]');
        
        // Add mobile-friendly styling
        [...searchInputs, ...filterSelects].forEach(element => {
            if (isMobile) {
                element.classList.add('mobile-search-filter');
            }
        });
        
        // Enhanced search with debouncing for mobile
        searchInputs.forEach(input => {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    // Trigger search after 300ms delay
                    const event = new Event('search');
                    this.dispatchEvent(event);
                }, 300);
            });
        });
    }
    
    // ==========================================================================
    // Modal Enhancements
    // ==========================================================================
    
    function enhanceModals() {
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(modal => {
            if (isMobile) {
                modal.classList.add('mobile-modal');
                
                // Prevent body scroll when modal is open
                modal.addEventListener('shown.bs.modal', function() {
                    document.body.style.overflow = 'hidden';
                });
                
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.style.overflow = '';
                });
            }
        });
    }
    
    // ==========================================================================
    // Dropdown Enhancements
    // ==========================================================================
    
    function enhanceDropdowns() {
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            if (isMobile) {
                dropdown.classList.add('mobile-dropdown');
                
                // Adjust dropdown position for mobile
                const toggle = dropdown.querySelector('.dropdown-toggle');
                const menu = dropdown.querySelector('.dropdown-menu');
                
                if (toggle && menu) {
                    toggle.addEventListener('click', function() {
                        setTimeout(() => {
                            const rect = toggle.getBoundingClientRect();
                            const viewportHeight = window.innerHeight;
                            
                            // If dropdown would go off screen, show it above
                            if (rect.bottom + menu.offsetHeight > viewportHeight) {
                                menu.classList.add('dropup');
                            }
                        }, 10);
                    });
                }
            }
        });
    }
    
    // ==========================================================================
    // Toast and Alert Enhancements
    // ==========================================================================
    
    function enhanceToastsAndAlerts() {
        // Adjust SweetAlert2 for mobile
        if (typeof Swal !== 'undefined' && isMobile) {
            const originalFire = Swal.fire;
            Swal.fire = function(options) {
                if (typeof options === 'object') {
                    options.width = '90%';
                    options.padding = '1rem';
                    
                    if (isSmallMobile) {
                        options.width = '95%';
                        options.padding = '0.75rem';
                    }
                }
                return originalFire.call(this, options);
            };
        }
    }
    
    // ==========================================================================
    // Performance Optimizations
    // ==========================================================================
    
    function optimizeForMobile() {
        if (isMobile) {
            // Lazy load images
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
            
            // Reduce animation duration for better performance
            const style = document.createElement('style');
            style.textContent = `
                * {
                    animation-duration: 0.2s !important;
                    transition-duration: 0.2s !important;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // ==========================================================================
    // Accessibility Enhancements
    // ==========================================================================
    
    function enhanceAccessibility() {
        // Increase touch target size for mobile
        if (isMobile) {
            const touchTargets = document.querySelectorAll('button, a, input, select, textarea');
            touchTargets.forEach(target => {
                const rect = target.getBoundingClientRect();
                if (rect.height < 44 || rect.width < 44) {
                    target.style.minHeight = '44px';
                    target.style.minWidth = '44px';
                    target.style.display = 'inline-flex';
                    target.style.alignItems = 'center';
                    target.style.justifyContent = 'center';
                }
            });
        }
        
        // Add focus indicators for keyboard navigation
        const focusableElements = document.querySelectorAll('button, a, input, select, textarea');
        focusableElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid #0d6efd';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });
    }
    
    // ==========================================================================
    // Window Resize Handler
    // ==========================================================================
    
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const newIsMobile = window.innerWidth <= 768;
            const newIsSmallMobile = window.innerWidth <= 576;
            
            // Update body classes
            document.body.classList.toggle('mobile-device', newIsMobile);
            document.body.classList.toggle('small-mobile-device', newIsSmallMobile);
            
            // Re-run enhancements if screen size changed significantly
            if (newIsMobile !== isMobile) {
                location.reload(); // Simple approach - reload page
            }
        }, 250);
    });
    
    // ==========================================================================
    // Initialize All Enhancements
    // ==========================================================================
    
    enhanceTableResponsiveness();
    enhanceFormResponsiveness();
    enhanceCardResponsiveness();
    enhanceNavigation();
    enhanceSearchAndFilters();
    enhanceModals();
    enhanceDropdowns();
    enhanceToastsAndAlerts();
    optimizeForMobile();
    enhanceAccessibility();
    
    // ==========================================================================
    // Utility Functions
    // ==========================================================================
    
    // Make functions globally available
    window.mobileEnhancements = {
        isMobile: () => window.innerWidth <= 768,
        isSmallMobile: () => window.innerWidth <= 576,
        enhanceTable: enhanceTableResponsiveness,
        enhanceForm: enhanceFormResponsiveness,
        enhanceCard: enhanceCardResponsiveness
    };
    
    console.log('Mobile enhancements loaded successfully');
});

// ==========================================================================
// CSS-in-JS for Dynamic Mobile Styles
// ==========================================================================

const mobileStyles = `
    /* Dynamic mobile styles */
    @media (max-width: 768px) {
        .mobile-form .btn-group-mobile {
            flex-direction: column;
            width: 100%;
        }
        
        .mobile-form .btn-group-mobile .btn {
            width: 100%;
            margin-bottom: 0.5rem;
            border-radius: 0.375rem !important;
        }
        
        .mobile-input {
            font-size: 16px !important; /* Prevent zoom on iOS */
        }
        
        .mobile-card {
            margin-bottom: 1rem;
            border-radius: 0.5rem;
        }
        
        .mobile-dropdown .dropdown-menu {
            position: fixed !important;
            top: auto !important;
            left: 1rem !important;
            right: 1rem !important;
            width: auto !important;
            max-height: 50vh;
            overflow-y: auto;
        }
        
        .mobile-modal .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
        
        .table-mobile-enhanced {
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 576px) {
        .small-mobile-device .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
        
        .small-mobile-device .card-body {
            padding: 1rem;
        }
        
        .small-mobile-device .form-control,
        .small-mobile-device .form-select {
            font-size: 16px; /* Prevent zoom */
        }
    }
`;

// Inject mobile styles
const styleSheet = document.createElement('style');
styleSheet.textContent = mobileStyles;
document.head.appendChild(styleSheet);