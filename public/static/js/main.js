/**
 * Education News Portal - High-Performance Interactive Logic
 * Zero dependencies, pure vanilla JS
 */

document.addEventListener('DOMContentLoaded', function () {
    // ---------------------------------------------------------
    // 1. Mobile Offcanvas Navigation Drawer
    // ---------------------------------------------------------
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
    const drawerSearchInput = document.getElementById('drawerSearchInput');

    function openDrawer(focusSearch = false) {
        if (mobileDrawer && drawerBackdrop) {
            mobileDrawer.classList.add('open');
            drawerBackdrop.classList.add('active');
            document.body.classList.add('drawer-locked');
            if (focusSearch && drawerSearchInput) {
                setTimeout(() => { drawerSearchInput.focus(); }, 300);
            }
        }
    }

    function closeDrawer() {
        if (mobileDrawer && drawerBackdrop) {
            mobileDrawer.classList.remove('open');
            drawerBackdrop.classList.remove('active');
            document.body.classList.remove('drawer-locked');
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openDrawer(false);
        });
    }

    if (mobileSearchTrigger) {
        mobileSearchTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            openDrawer(true);
        });
    }

    if (closeDrawerBtn) {
        closeDrawerBtn.addEventListener('click', function (e) {
            e.preventDefault();
            closeDrawer();
        });
    }

    if (drawerBackdrop) {
        drawerBackdrop.addEventListener('click', function () {
            closeDrawer();
        });
    }

    // Close drawer on ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDrawer();
        }
    });

    // ---------------------------------------------------------
    // 2. Desktop "More Categories ▾" Dropdown
    // ---------------------------------------------------------
    const dropdownBtn = document.getElementById('moreCategoriesBtn');
    const dropdownParent = dropdownBtn ? dropdownBtn.closest('.nav-dropdown') : null;

    if (dropdownBtn && dropdownParent) {
        dropdownBtn.addEventListener('click', function (e) {
            e.preventDefault();
            dropdownParent.classList.toggle('open');
            const isOpen = dropdownParent.classList.contains('open');
            dropdownBtn.setAttribute('aria-expanded', isOpen);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdownParent.contains(e.target)) {
                dropdownParent.classList.remove('open');
                dropdownBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ---------------------------------------------------------
    // 3. One-Click Copy & Share Article Link
    // ---------------------------------------------------------
    const copyBtns = document.querySelectorAll('.js-copy-link');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    const origText = btn.innerText;
                    btn.innerText = 'Copied!';
                    setTimeout(() => { btn.innerText = origText; }, 2000);
                });
            }
        });
    });

    // ---------------------------------------------------------
    // 4. Infinite Smooth Breaking News Ticker Loop
    // ---------------------------------------------------------
    const tickerItems = document.querySelector('.ticker-items');
    if (tickerItems && tickerItems.children.length > 0) {
        const clone = tickerItems.innerHTML;
        tickerItems.innerHTML += clone;
    }
});
