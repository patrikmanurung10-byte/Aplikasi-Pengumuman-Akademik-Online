/**
 * API Integration for APAO Polibatam
 * JavaScript functions untuk integrasi dengan backend API
 */

class APAOAPI {
    constructor() {
        this.baseURL = 'backend/api/';
        this.sessionToken = localStorage.getItem('apao_session_token');
    }

    // Generic API call method
    async apiCall(endpoint, method = 'GET', data = null) {
        const url = this.baseURL + endpoint;
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            }
        };

        if (data) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: 'Network error occurred' };
        }
    }

    // Authentication methods
    async login(username, password) {
        const result = await this.apiCall('auth.php?action=login', 'POST', {
            username: username,
            password: password
        });

        if (result.success) {
            this.sessionToken = result.user.session_token;
            localStorage.setItem('apao_session_token', this.sessionToken);
            localStorage.setItem('apao_user', JSON.stringify(result.user));
        }

        return result;
    }

    async register(userData) {
        return await this.apiCall('auth.php?action=register', 'POST', userData);
    }

    async logout() {
        if (!this.sessionToken) return { success: false, message: 'No active session' };

        const result = await this.apiCall('auth.php?action=logout', 'POST', {
            session_token: this.sessionToken
        });

        if (result.success) {
            this.sessionToken = null;
            localStorage.removeItem('apao_session_token');
            localStorage.removeItem('apao_user');
        }

        return result;
    }

    async validateSession() {
        if (!this.sessionToken) return { success: false, message: 'No session token' };

        return await this.apiCall(`auth.php?action=validate&token=${this.sessionToken}`);
    }

    // Announcement methods
    async getAnnouncements(page = 1, limit = 10, categoryId = null, search = null) {
        let endpoint = `announcements.php?action=list&page=${page}&limit=${limit}`;
        
        if (categoryId) endpoint += `&category_id=${categoryId}`;
        if (search) endpoint += `&search=${encodeURIComponent(search)}`;

        return await this.apiCall(endpoint);
    }

    async getAnnouncementDetail(id) {
        return await this.apiCall(`announcements.php?action=detail&id=${id}`);
    }

    async createAnnouncement(announcementData) {
        if (!this.sessionToken) return { success: false, message: 'Authentication required' };

        announcementData.session_token = this.sessionToken;
        return await this.apiCall('announcements.php?action=create', 'POST', announcementData);
    }

    async updateAnnouncement(id, announcementData) {
        if (!this.sessionToken) return { success: false, message: 'Authentication required' };

        announcementData.id = id;
        announcementData.session_token = this.sessionToken;
        return await this.apiCall('announcements.php?action=update', 'PUT', announcementData);
    }

    async deleteAnnouncement(id) {
        if (!this.sessionToken) return { success: false, message: 'Authentication required' };

        return await this.apiCall('announcements.php?action=delete', 'DELETE', {
            id: id,
            session_token: this.sessionToken
        });
    }

    async getCategories() {
        return await this.apiCall('announcements.php?action=categories');
    }

    async getStatistics() {
        if (!this.sessionToken) return { success: false, message: 'Authentication required' };

        return await this.apiCall(`announcements.php?action=statistics&token=${this.sessionToken}`);
    }

    // Utility methods
    getCurrentUser() {
        const userStr = localStorage.getItem('apao_user');
        return userStr ? JSON.parse(userStr) : null;
    }

    isLoggedIn() {
        return !!this.sessionToken;
    }

    hasRole(roles) {
        const user = this.getCurrentUser();
        if (!user) return false;
        
        if (Array.isArray(roles)) {
            return roles.includes(user.role);
        }
        
        return user.role === roles;
    }

    // Show notification
    showNotification(message, type = 'info') {
        // Simple notification - you can enhance this with a proper notification library
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
}

// Initialize API instance
const apaoAPI = new APAOAPI();

// Example usage functions
async function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (!username || !password) {
        apaoAPI.showNotification('Username dan password harus diisi', 'warning');
        return;
    }
    
    const result = await apaoAPI.login(username, password);
    
    if (result.success) {
        apaoAPI.showNotification('Login berhasil!', 'success');
        
        // Redirect based on role
        if (result.user.role === 'admin' || result.user.role === 'dosen') {
            window.location.href = 'pages/admin/Dashboard_Admin.html';
        } else {
            window.location.href = 'pages/dashboard/Dashboard_Mahasiswa.html';
        }
    } else {
        apaoAPI.showNotification(result.message, 'danger');
    }
}

async function handleLogout() {
    const result = await apaoAPI.logout();
    
    if (result.success) {
        apaoAPI.showNotification('Logout berhasil', 'success');
        window.location.href = '../../index.html';
    } else {
        apaoAPI.showNotification('Gagal logout', 'danger');
    }
}

// Check authentication on page load
document.addEventListener('DOMContentLoaded', async function() {
    if (apaoAPI.isLoggedIn()) {
        const validation = await apaoAPI.validateSession();
        
        if (!validation.success) {
            // Session expired, redirect to login
            localStorage.removeItem('apao_session_token');
            localStorage.removeItem('apao_user');
            
            if (!window.location.pathname.includes('Login_')) {
                window.location.href = '../../index.html';
            }
        }
    }
});