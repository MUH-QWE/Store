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
    `;

    if (user) {
        if (user.role !== 'admin') {
            html += `
                <a href="Cart.html" style="position: relative;">
                    Cart
                    <span class="cart-count-badge badge">0</span>
                </a>
                <a href="Inbox.html">Inbox</a>
                <a href="Profile.html">My Profile</a>
                <a href="Orders.html">Orders</a>
                <a href="Wishlist.html">Wishlist</a>
            `;
        }

        html += `<a href="#" onclick="store.logout()" class="text-gradient">Logout [${user.name}]</a>`;

        if (user.role === 'admin') {
            html += `<a href="AdminDashboard.html" style="color: var(--accent);">Admin</a>`;
        }
    } else {
        html += `
            <a href="Cart.html" style="position: relative;">
                Cart
                <span class="cart-count-badge badge">0</span>
            </a>
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


