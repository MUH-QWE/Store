window.renderNav = function () {
    const navLinks = document.querySelector('.nav-links');
    const mobileNavLinks = document.querySelector('.mobile-nav-links');

    if (!navLinks) {
        console.warn('Navigation links container not found.');
        return;
    }

    const user = store.getUser();

    let html = `
        <a href="index.html">Home</a>
        <a href="Products.html">Products</a>
        <a href="Cart.html" style="position: relative;">
            Cart
            <span class="cart-count-badge badge">0</span>
        </a>
    `;

    if (user) {
        html += `
            <a href="Orders.html">Orders</a>
            <a href="Wishlist.html">Wishlist</a>
            <a href="#" onclick="store.logout()" class="text-gradient">Logout [${user.name}]</a>
        `;
        if (user.role === 'admin') {
            html += `<a href="AdminPanel.html" style="color: var(--accent);">Admin</a>`;
        }
    } else {
        html += `
            <a href="LogIn.html">Login</a>
            <a href="Register.html" class="btn" style="padding: 0.4rem 1rem; border-radius: 8px;">Entry</a>
        `;
    }

    navLinks.innerHTML = html;
    if (mobileNavLinks) {
        mobileNavLinks.innerHTML = html;
    }

    if (window.cartManager) window.cartManager.updateCartCount();
};

document.addEventListener('DOMContentLoaded', window.renderNav);
window.addEventListener('auth-change', window.renderNav);


