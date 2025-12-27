const Layout = {
    header: `
        <header class="fade-in">
            <div class="container">
                <nav>
                    <a href="index.html" class="logo">
                        <span class="text-gradient">TAYEB</span> DJERBA
                    </a>
                    <div class="nav-links">
                    </div>
                    <button class="mobile-toggle" id="mobile-menu-toggle" aria-label="Toggle Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </nav>
            </div>
            <div class="mobile-menu" id="mobile-menu">
                <div class="mobile-nav-links"></div>
            </div>
        </header>
        <div class="gradient-overlay"></div>
    `,

    footer: `
        <footer style="padding: 4rem 0; border-top: 1px solid var(--glass-border); margin-top: 4rem;">
            <div class="container" style="text-align: center;">
                <h3 class="text-gradient" style="margin-bottom: 1rem;">Al-Taawon Street - Tersa - Giza</h3>
                <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto; font-weight: 300; font-size: 1.1rem;">
                    Your number one destination for modern fashion with an authentic Egyptian touch.
                </p>
                <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 2rem;">
                    <a href="#" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">Instagram</a>
                    <a href="#" style="color: var(--secondary); text-decoration: none; font-size: 0.9rem;">Facebook</a>
                    <a href="#" style="color: var(--accent); text-decoration: none; font-size: 0.9rem;">WhatsApp</a>
                </div>
                <p style="margin-top: 2rem; font-size: 0.8rem; color: var(--text-secondary);">
                    &copy; 2025 TAYEB FASHION. Al-Taawon Street.
                </p>
            </div>
        </footer>
        </footer>
    `,

    render: function () {
        document.body.insertAdjacentHTML('afterbegin', this.header);
        document.body.insertAdjacentHTML('beforeend', this.footer);

        if (window.renderNav) window.renderNav();

        const toggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (toggle && mobileMenu) {
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            });

            // Close menu when clicking a link
            mobileMenu.addEventListener('click', (e) => {
                if (e.target.tagName === 'A') {
                    toggle.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            });
        });

        document.addEventListener('click', (e) => {
            const card = e.target.closest('.card');
            if (card && !e.target.closest('button') && !e.target.closest('a')) {
                card.style.transition = 'transform 0.6s var(--cubic-ease)';
                const isTilted = card.classList.toggle('tilted');
                card.style.transform = isTilted ? 'translateY(-10px) rotateY(15deg) rotateX(5deg)' : 'translateY(-10px)';
                card.style.borderColor = isTilted ? 'var(--primary)' : 'var(--glass-border)';
            }
        });
    }
};


