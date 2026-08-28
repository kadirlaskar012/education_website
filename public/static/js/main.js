/**
 * Education News Portal - High-Performance Interactive Logic
 * Features: Mobile App Bottom Sheet, Smooth Category Tab Centering, Share, Dark/Light Mode
 */

document.addEventListener('DOMContentLoaded', function () {
    // ---------------------------------------------------------
    // 1. Eye-Comfort Dark / Light Theme Toggle & Persistence
    // ---------------------------------------------------------
    const themeToggles = document.querySelectorAll('.js-theme-toggle');
    const htmlEl = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlEl.setAttribute('data-theme', savedTheme);
    updateAllThemeButtons(savedTheme);

    themeToggles.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const currentTheme = htmlEl.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlEl.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateAllThemeButtons(newTheme);
        });
    });

    function updateAllThemeButtons(theme) {
        themeToggles.forEach(btn => {
            const label = btn.querySelector('.theme-label');
            if (label) {
                label.innerText = (theme === 'dark') ? 'Light Mode' : 'Eye Comfort';
            }
        });
    }

    // ---------------------------------------------------------
    // 2. Mobile App-Style Bottom Sheet / Drawer
    // ---------------------------------------------------------
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileExploreTrigger = document.getElementById('mobileExploreTrigger');
    const bottomMenuTrigger = document.getElementById('bottomMenuTrigger');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
    const drawerSearchInput = document.getElementById('drawerSearchInput');
    const drawerHandleBar = document.querySelector('.drawer-handle-bar');

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

    if (mobileExploreTrigger) {
        mobileExploreTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            openDrawer(false);
        });
    }

    if (bottomMenuTrigger) {
        bottomMenuTrigger.addEventListener('click', function (e) {
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

    if (drawerHandleBar) {
        drawerHandleBar.addEventListener('click', function () {
            closeDrawer();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDrawer();
        }
    });

    // ---------------------------------------------------------
    // 3. Auto-Scroll Active Category Tab into Center View
    // ---------------------------------------------------------
    const activeTab = document.querySelector('.smart-tab-pill.active');
    const scrollTrack = document.getElementById('categoryScrollTrack');
    if (activeTab && scrollTrack) {
        setTimeout(() => {
            const trackRect = scrollTrack.getBoundingClientRect();
            const tabRect = activeTab.getBoundingClientRect();
            const scrollLeft = activeTab.offsetLeft - (trackRect.width / 2) + (tabRect.width / 2);
            scrollTrack.scrollTo({
                left: Math.max(0, scrollLeft),
                behavior: 'smooth'
            });
        }, 100);
    }

    // ---------------------------------------------------------
    // 4. Desktop "More Categories ▾" Dropdown
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

        document.addEventListener('click', function (e) {
            if (!dropdownParent.contains(e.target)) {
                dropdownParent.classList.remove('open');
                dropdownBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ---------------------------------------------------------
    // 5. One-Click Copy & Share Article Link
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
    // 6. Infinite Smooth Breaking News Ticker Loop
    // ---------------------------------------------------------
    const tickerItems = document.querySelector('.ticker-items');
    if (tickerItems && tickerItems.children.length > 0) {
        const clone = tickerItems.innerHTML;
        tickerItems.innerHTML += clone;
    }
});
