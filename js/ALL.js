const INITIAL_MOCK_PRODUCTS = [
    { id: 1, name: 'Essential Cotton Tee', price: 450.00, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400', description: 'Premium cotton t-shirt for everyday comfort.', stock: 100 },
    { id: 2, name: 'Urban Denim Jacket', price: 1200.00, image: 'https://images.unsplash.com/photo-1523205771623-e0faa4d2813d?w=400', description: 'Classic denim jacket with a modern fit.', stock: 45 },
    { id: 3, name: 'Slim Fit Chinos', price: 850.00, image: 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400', description: 'Versatile trousers suitable for any occasion.', stock: 60 },
    { id: 4, name: 'Oversized Hoodie', price: 950.00, image: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=400', description: 'Cozy and stylish hoodie for relaxed vibes.', stock: 30 },
    { id: 5, name: 'Summer Floral Dress', price: 750.00, image: 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400', description: 'Lightweight dress perfect for warm weather.', stock: 25 }
];

const MOCK_ORDERS = [
    { id: 2045, user: 'Ahmed Tech', total: 1850.00, method: 'Vodafone Cash', status: 'Pending', proof: 'proof_01.png' },
    { id: 2046, user: 'Sarah Digital', total: 950.00, method: 'COD', status: 'Processing' },
    { id: 2047, user: 'Yassir Node', total: 3200.00, method: 'Bank Transfer', status: 'Pending', proof: 'proof_02.png' }
];

function getProducts() {
    let products = JSON.parse(localStorage.getItem('demo_products_v3'));
    if (!products) {
        products = INITIAL_MOCK_PRODUCTS;
        localStorage.setItem('demo_products_v3', JSON.stringify(products));
    }
    return products;
}

function saveProducts(products) {
    localStorage.setItem('demo_products_v3', JSON.stringify(products));
}

const API_BASE = './app/api'; // Correct path if index.html is in the same level as app/

window.api = {
    async get(endpoint, token = null) {
        console.log('API - GET:', endpoint);
        const url = endpoint.startsWith('http') ? endpoint : `${API_BASE}${endpoint}`;

        try {
            const headers = { 'Content-Type': 'application/json' };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch(url, { method: 'GET', headers });
            if (response.ok) return await response.json();
            throw new Error('Network response not ok');
        } catch (error) {
            console.warn('Backend unavailable, switching to Demo Mode for endpoint:', endpoint);
            // --- Fallback Mock Data ---
            const products = getProducts();
            if (endpoint.includes('/orders/get_all.php')) return MOCK_ORDERS;
            if (endpoint.includes('/orders/get.php')) return [
                { id: 2045, status: 'pending', created_at: '2025-12-18T10:00:00Z', total_amount: 145.00 },
                { id: 1982, status: 'paid', created_at: '2025-12-10T15:30:00Z', total_amount: 55.40 }
            ];
            if (endpoint.includes('/products/get.php')) return products;
            if (endpoint.includes('/products/get_single.php')) {
                const urlParams = new URL(endpoint, 'http://dummy.com').searchParams;
                const id = urlParams.get('id');
                return products.find(p => p.id == id) || { error: 'Protocol not found' };
            }
            if (endpoint.includes('/wishlist/get.php')) {
                const wishlistIds = JSON.parse(sessionStorage.getItem('store_wishlist_demo') || '[]');
                return products.filter(p => wishlistIds.includes(p.id));
            }
            if (endpoint.includes('/settings/get.php')) return { db_sync_status: sessionStorage.getItem('db_sync_status') || '0' };
            return [];
        }
    },

    async post(endpoint, data, token = null) {
        console.log('API - POST:', endpoint, data);
        const url = endpoint.startsWith('http') ? endpoint : `${API_BASE}${endpoint}`;

        try {
            const headers = { 'Content-Type': 'application/json' };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch(url, {
                method: 'POST',
                headers,
                body: JSON.stringify(data)
            });
            if (response.ok) return await response.json();
            throw new Error('Network response not ok');
        } catch (error) {
            console.warn('Backend unavailable, simulating action for endpoint:', endpoint);
            // --- Fallback Simulation ---
            if (endpoint.includes('/checkout/process.php') || endpoint.includes('/orders/create.php')) {
                return { message: 'Transmission successful (Simulated)', order_id: 9999 };
            }
            if (endpoint.includes('/auth/login.php')) {
                const isAdmin = data.email === 'admin@store.com';
                return {
                    token: 'demo-session-token',
                    user: { id: isAdmin ? 99 : 1, name: isAdmin ? 'Grand Admin' : 'Demo User', role: isAdmin ? 'admin' : 'user' }
                };
            }
            return { message: 'Action simulated successfully' };
        }
    }
};

window.store = {
    getToken() {
        return sessionStorage.getItem('token');
    },
    getUser() {
        const user = sessionStorage.getItem('user');
        try {
            return user ? JSON.parse(user) : null;
        } catch (e) {
            return null;
        }
    },
    setAuth(token, user) {
        sessionStorage.setItem('token', token);
        sessionStorage.setItem('user', JSON.stringify(user));
        window.dispatchEvent(new Event('auth-change'));
    },
    logout() {
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('user');
        window.dispatchEvent(new Event('auth-change'));
        window.location.href = 'index.html';
    },
    isInWishlist(id) {
        const wishlist = JSON.parse(sessionStorage.getItem('store_wishlist_demo') || '[]');
        return wishlist.includes(id);
    }
};

window.showNotification = function (message, type = 'info', title = null) {
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `notification ${type}`;

    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    const defaultTitles = {
        success: 'Protocol Success',
        error: 'System Error',
        info: 'Nexus Message'
    };

    toast.innerHTML = `
        <i class="fas ${icons[type] || icons.info} notification-icon"></i>
        <div class="notification-content">
            <div class="notification-title">${title || defaultTitles[type]}</div>
            <div class="notification-message">${message}</div>
        </div>
    `;

    container.appendChild(toast);

    setTimeout(() => toast.classList.add('active'), 10);

    setTimeout(() => {
        toast.classList.remove('active');
        setTimeout(() => toast.remove(), 500);
    }, 4000);
};

window.alert = (msg) => window.showNotification(msg, 'info');

window.LuxeApp = {
    init() {
        console.log('LuxeApp initialized');
    }
};

window.AdminPanel = class {
    constructor() {
        console.log('AdminPanel initialized');
    }
};


