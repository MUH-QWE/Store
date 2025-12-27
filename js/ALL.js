const INITIAL_MOCK_PRODUCTS = [];

const MOCK_ORDERS = [];

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
                { id: 2045, status: 'Pending', created_at: '2025-12-25T10:00:00Z', total_amount: 1850.00, address: 'Cairo, Maadi St. 5', phone: '01011223344', method: 'Vodafone Cash' },
                { id: 1982, status: 'Shipped', created_at: '2025-12-10T15:30:00Z', total_amount: 850.00, address: 'Alexandria, Corniche', phone: '01233445566', method: 'COD' }
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
            if (endpoint.includes('/products/update.php')) {
                const products = getProducts();
                const index = products.findIndex(p => p.id == data.id);
                if (index > -1) {
                    products[index] = { ...products[index], ...data };
                    saveProducts(products);
                    return { success: true, message: 'Protocol updated in core memory' };
                }
            }
            if (endpoint.includes('/products/add.php')) {
                const products = getProducts();
                const newProduct = {
                    ...data,
                    id: products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1,
                    price: parseFloat(data.price)
                };
                products.push(newProduct);
                saveProducts(products);
                return { success: true, message: 'New protocol initialized and saved' };
            }
            if (endpoint.includes('/products/delete.php')) {
                const products = getProducts();
                const index = products.findIndex(p => p.id == data.id);
                if (index > -1) {
                    const deletedName = products[index].name;
                    products.splice(index, 1);
                    saveProducts(products);
                    return { success: true, message: `Protocol ${deletedName} deleted from core memory` };
                }
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


