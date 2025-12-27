class CartManager {
    constructor() {
        this.cart = [];
        this.subscribers = [];

        this.STORAGE_KEY = 'store_cart_demo';

        window.addEventListener('auth-change', () => {
            this.loadCart();
        });

        this.loadCart();
    }

    subscribe(callback) {
        this.subscribers.push(callback);
        callback(this.cart);
    }

    notify() {
        this.subscribers.forEach(cb => cb(this.cart));
    }

    async loadCart() {
        const stored = sessionStorage.getItem(this.STORAGE_KEY);
        this.cart = stored ? JSON.parse(stored) : [];

        this.notify();
        this.updateCartCount();
    }

    async add(product, variantId = null, quantity = 1) {
        if (!store.getUser()) {
            window.location.href = 'LogIn.html';
            return false;
        }

        const size = product.variant || variantId || 'Standard';

        const existingIndex = this.cart.findIndex(item =>
            item.product_id == product.id && item.variant_id == size
        );

        if (existingIndex > -1) {
            this.cart[existingIndex].quantity += quantity;
        } else {
            this.cart.push({
                cart_item_id: 'item_' + Date.now(),
                product_id: product.id,
                variant_id: size,
                quantity: quantity,
                name: product.name,
                price: product.price,
                image: product.image
            });
        }

        this.saveCart();
        return true;
    }

    async remove(cartItemId) {
        this.cart = this.cart.filter(item => item.cart_item_id !== cartItemId);
        this.saveCart();
    }

    async updateQuantity(cartItemId, newQty) {
        if (newQty < 1) return this.remove(cartItemId);

        const item = this.cart.find(i => i.cart_item_id === cartItemId);
        if (item) {
            item.quantity = newQty;
            this.saveCart();
        }
    }

    async clear() {
        this.cart = [];
        this.saveCart();
    }

    saveCart() {
        sessionStorage.setItem(this.STORAGE_KEY, JSON.stringify(this.cart));
        this.notify();
        this.updateCartCount();
    }

    updateCartCount() {
        const count = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        const badges = document.querySelectorAll('.cart-count-badge');
        badges.forEach(el => el.textContent = count);
    }
}

window.cartManager = new CartManager();


