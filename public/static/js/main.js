/**
 * EduGov News - High-Performance Interactive Logic & Micro-Interactions
 * Features: Mobile App Bottom Sheet, Smooth Category Tab Centering, Copy Toast, Dark/Light Mode
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
    // 2. Toast Notification Helper
    // ---------------------------------------------------------
    const toastEl = document.getElementById('toastNotification');
    let toastTimeout = null;

    function showToast(message) {
        if (!toastEl) return;
        toastEl.innerText = message;
        toastEl.classList.add('show');
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toastEl.classList.remove('show');
        }, 2800);
    }

    // ---------------------------------------------------------
    // 3. Mobile App-Style Bottom Sheet / Drawer
    // ---------------------------------------------------------
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileExploreTrigger = document.getElementById('mobileExploreTrigger');
    const bottomMenuTrigger = document.getElementById('bottomMenuTrigger');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const drawerHandleBar = document.querySelector('.drawer-handle-bar');

    // Dedicated Quick Search Modal Elements
    const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
    const quickSearchModal = document.getElementById('quickSearchModal');
    const searchModalBackdrop = document.getElementById('searchModalBackdrop');
    const closeSearchModalBtn = document.getElementById('closeSearchModalBtn');
    const quickSearchModalInput = document.getElementById('quickSearchModalInput');

    function openSearchModal() {
        if (quickSearchModal && searchModalBackdrop) {
            closeDrawer();
            quickSearchModal.classList.add('open');
            searchModalBackdrop.classList.add('active');
            document.body.classList.add('modal-locked');
            setTimeout(() => {
                if (quickSearchModalInput) quickSearchModalInput.focus();
            }, 120);
        }
    }

    function closeSearchModal() {
        if (quickSearchModal && searchModalBackdrop) {
            quickSearchModal.classList.remove('open');
            searchModalBackdrop.classList.remove('active');
            document.body.classList.remove('modal-locked');
        }
    }

    if (mobileSearchTrigger) {
        mobileSearchTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            openSearchModal();
        });
    }

    if (closeSearchModalBtn) {
        closeSearchModalBtn.addEventListener('click', function (e) {
            e.preventDefault();
            closeSearchModal();
        });
    }

    if (searchModalBackdrop) {
        searchModalBackdrop.addEventListener('click', function () {
            closeSearchModal();
        });
    }

    function openDrawer() {
        closeSearchModal();
        if (mobileDrawer && drawerBackdrop) {
            mobileDrawer.classList.add('open');
            drawerBackdrop.classList.add('active');
            document.body.classList.add('drawer-locked');
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
            openDrawer();
        });
    }

    if (mobileExploreTrigger) {
        mobileExploreTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            openDrawer();
        });
    }

    if (bottomMenuTrigger) {
        bottomMenuTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            openDrawer();
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
            closeSearchModal();
            closeDrawer();
        }
        // Instant Ctrl+K or Cmd+K or / search launcher
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openSearchModal();
        }
        if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            e.preventDefault();
            openSearchModal();
        }
    });

    // ---------------------------------------------------------
    // 4. Auto-Scroll Active Category Tab into Center View
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
    // 5. Desktop "More Categories ▾" Dropdown
    // ---------------------------------------------------------
    const dropdownBtn = document.getElementById('moreCategoriesBtn');
    const dropdownParent = dropdownBtn ? dropdownBtn.closest('.nav-dropdown') : null;

    if (dropdownBtn && dropdownParent) {
        dropdownBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
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
    // 6. One-Click Copy & Share Article Link with Toast
    // ---------------------------------------------------------
    const copyBtns = document.querySelectorAll('.js-copy-link');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = btn.getAttribute('data-url') || window.location.href;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    showToast('✓ Link copied to clipboard!');
                    const origText = btn.innerText;
                    btn.innerText = '✓ Copied!';
                    setTimeout(() => { btn.innerText = origText; }, 2000);
                }).catch(() => {
                    fallbackCopyText(url, btn);
                });
            } else {
                fallbackCopyText(url, btn);
            }
        });
    });

    function fallbackCopyText(text, btn) {
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand('copy');
            showToast('✓ Link copied to clipboard!');
            const origText = btn.innerText;
            btn.innerText = '✓ Copied!';
            setTimeout(() => { btn.innerText = origText; }, 2000);
        } catch (err) {
            showToast('Press Ctrl+C to copy link');
        }
        document.body.removeChild(tempInput);
    }

    // ---------------------------------------------------------
    // 7. Reading Progress Bar & Back-to-Top Floating Button
    // ---------------------------------------------------------
    const readingBar = document.getElementById('readingProgressBar');
    const backToTopBtn = document.getElementById('backToTopBtn');

    function handleScrollInteractions() {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;

        // Update Reading Progress Bar
        if (readingBar) {
            const progress = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
            readingBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
            readingBar.setAttribute('aria-valuenow', Math.round(progress));
        }

        // Toggle Back-To-Top Button
        if (backToTopBtn) {
            if (scrollTop > 350) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        }
    }

    window.addEventListener('scroll', handleScrollInteractions, { passive: true });
    handleScrollInteractions();

    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ---------------------------------------------------------
    // 8. Dynamic Hierarchical Table of Contents (Parent-Child H2 & H3) & ScrollSpy
    // ---------------------------------------------------------
    const articleBody = document.querySelector('.article-main-body') || document.getElementById('article-body');
    const tocBox = document.getElementById('articleTocBox');
    const tocRootList = document.getElementById('tocRootList');
    const tocToggleBtn = document.getElementById('tocToggleBtn');
    const tocContentWrap = document.getElementById('tocContentWrap');
    const tocCountBadge = document.getElementById('tocCountBadge');
    const tocHeaderToggle = document.getElementById('tocHeaderToggle');

    if (articleBody && tocBox && tocRootList) {
        // Collect all H2 and H3 headings inside article body
        const headings = Array.from(articleBody.querySelectorAll('h2, h3, .section-heading, .faqs-main-heading'));

        if (headings.length > 0) {
            tocRootList.innerHTML = '';
            let totalCount = 0;
            let currentParentLi = null;
            let currentChildOl = null;

            headings.forEach((heading, idx) => {
                // Ensure heading has an ID
                let headingId = heading.id;
                if (!headingId) {
                    const slugText = heading.innerText
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-');
                    headingId = (slugText ? slugText.slice(0, 45) : 'section') + '-' + (idx + 1);
                    heading.id = headingId;
                }

                // Add smooth scroll margin
                heading.style.scrollMarginTop = '90px';

                const headingText = heading.innerText.trim();
                const isH3 = heading.tagName.toLowerCase() === 'h3' && !heading.classList.contains('faqs-main-heading');

                if (isH3 && currentParentLi) {
                    // Child H3 Item
                    if (!currentChildOl) {
                        currentChildOl = document.createElement('ol');
                        currentChildOl.className = 'toc-child-list';
                        currentParentLi.appendChild(currentChildOl);
                    }
                    const childLi = document.createElement('li');
                    childLi.className = 'toc-child-item';
                    childLi.innerHTML = `<a href="#${headingId}" class="toc-link toc-link-child" data-target="${headingId}"><span class="toc-bullet">↳</span> <span class="toc-text">${headingText}</span></a>`;
                    currentChildOl.appendChild(childLi);
                } else {
                    // Parent H2 / Major Heading Item
                    currentChildOl = null;
                    const parentLi = document.createElement('li');
                    parentLi.className = 'toc-parent-item';
                    parentLi.innerHTML = `<a href="#${headingId}" class="toc-link toc-link-parent" data-target="${headingId}"><span class="toc-num">${++totalCount}.</span> <span class="toc-text">${headingText}</span></a>`;
                    tocRootList.appendChild(parentLi);
                    currentParentLi = parentLi;
                }
            });

            if (tocCountBadge) {
                tocCountBadge.innerText = `${totalCount} topics`;
            }

            // Smooth Scroll for TOC links with ample header clearance
            const tocLinks = tocBox.querySelectorAll('.toc-link');
            tocLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('data-target') || this.getAttribute('href').replace('#', '');
                    const targetEl = document.getElementById(targetId);
                    if (targetEl) {
                        e.preventDefault();
                        // 155px offset ensures heading sits with ~35px breathing space below the sticky header
                        const headerOffset = window.innerWidth <= 768 ? 135 : 155;
                        const elementPosition = targetEl.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        window.scrollTo({
                            top: Math.max(0, offsetPosition),
                            behavior: 'smooth'
                        });
                        history.replaceState(null, null, '#' + targetId);
                    }
                });
            });

            // ScrollSpy: Highlight active section in TOC
            const observerOptions = {
                root: null,
                rootMargin: '-155px 0px -55% 0px',
                threshold: 0
            };

            const headingObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const activeId = entry.target.id;
                        tocLinks.forEach(link => {
                            if (link.getAttribute('data-target') === activeId || link.getAttribute('href') === '#' + activeId) {
                                link.classList.add('toc-active');
                            } else {
                                link.classList.remove('toc-active');
                            }
                        });
                    }
                });
            }, observerOptions);

            headings.forEach(h => headingObserver.observe(h));
        }

        // Toggle Table of Contents Collapse/Expand
        function toggleToc() {
            tocBox.classList.toggle('collapsed');
            const isCollapsed = tocBox.classList.contains('collapsed');
            if (tocToggleBtn) {
                const textEl = tocToggleBtn.querySelector('.toc-toggle-text');
                const arrowEl = tocToggleBtn.querySelector('.toc-toggle-arrow');
                if (textEl) textEl.innerText = isCollapsed ? 'Show' : 'Hide';
                if (arrowEl) arrowEl.style.transform = isCollapsed ? 'rotate(-90deg)' : 'rotate(0deg)';
            }
        }

        if (tocHeaderToggle) {
            tocHeaderToggle.addEventListener('click', function (e) {
                toggleToc();
            });
        }
    }

    // ---------------------------------------------------------
    // 9. Progressive Web App (PWA) Service Worker & Install Prompt
    // ---------------------------------------------------------
    if ('serviceWorker' in navigator && window.location.protocol.startsWith('http')) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => {
                    console.log('EduGov PWA ServiceWorker Registered:', reg.scope);
                })
                .catch(err => {
                    console.log('SW Registration skipped/failed:', err);
                });
        });
    }

    let deferredInstallPrompt = null;
    const pwaInstallBtns = document.querySelectorAll('.js-pwa-install, #pwaInstallBtn');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredInstallPrompt = e;
        pwaInstallBtns.forEach(btn => {
            btn.style.display = 'inline-flex';
        });
    });

    pwaInstallBtns.forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!deferredInstallPrompt) {
                showToast('To install, open browser menu & select "Add to Home screen"');
                return;
            }
            deferredInstallPrompt.prompt();
            const { outcome } = await deferredInstallPrompt.userChoice;
            if (outcome === 'accepted') {
                showToast('✓ EduGov App successfully installed!');
            }
            deferredInstallPrompt = null;
            pwaInstallBtns.forEach(b => b.style.display = 'none');
        });
    });

    window.addEventListener('appinstalled', () => {
        deferredInstallPrompt = null;
        pwaInstallBtns.forEach(b => b.style.display = 'none');
        showToast('✓ App installed to your homescreen!');
    });

    // ---------------------------------------------------------
    // 10. Interactive Live Smart Filter Hub (Instant Notice Filtering)
    // ---------------------------------------------------------
    const smartFilterHub = document.getElementById('smartFilterHub');
    if (smartFilterHub) {
        const filterPills = smartFilterHub.querySelectorAll('.smart-filter-pill');
        const matchCountEl = document.getElementById('filterMatchCount');
        const resetBtn = document.getElementById('btnFilterReset');

        const activeFilters = {
            qual: 'all',
            sector: 'all',
            type: 'all'
        };

        const noticeRows = Array.from(document.querySelectorAll('.hero-notice-row, .stream-notice-row'));

        function applySmartFilters() {
            let visibleCount = 0;
            const hasActiveFilter = activeFilters.qual !== 'all' || activeFilters.sector !== 'all' || activeFilters.type !== 'all';

            if (resetBtn) {
                resetBtn.style.display = hasActiveFilter ? 'inline-flex' : 'none';
            }

            noticeRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                let matches = true;

                // 1. Qualification Check
                if (activeFilters.qual !== 'all') {
                    if (activeFilters.qual === '10th' && !text.includes('10th') && !text.includes('matric') && !text.includes('secondary')) matches = false;
                    if (activeFilters.qual === '12th' && !text.includes('12th') && !text.includes('hs') && !text.includes('higher secondary') && !text.includes('intermediate')) matches = false;
                    if (activeFilters.qual === 'graduate' && !text.includes('graduate') && !text.includes('degree') && !text.includes('cgl') && !text.includes('bachelor') && !text.includes('b.a') && !text.includes('b.sc')) matches = false;
                    if (activeFilters.qual === 'diploma' && !text.includes('diploma') && !text.includes('iti') && !text.includes('polytechnic') && !text.includes('apprentice')) matches = false;
                    if (activeFilters.qual === 'pg' && !text.includes('post graduate') && !text.includes('master') && !text.includes('m.a') && !text.includes('m.sc') && !text.includes('b.ed')) matches = false;
                }

                // 2. Sector / Board Check
                if (matches && activeFilters.sector !== 'all') {
                    if (activeFilters.sector === 'railway' && !text.includes('railway') && !text.includes('rrb') && !text.includes('rrc') && !text.includes('ntpc')) matches = false;
                    if (activeFilters.sector === 'ssc' && !text.includes('ssc') && !text.includes('staff selection') && !text.includes('cgl') && !text.includes('chsl') && !text.includes('mts')) matches = false;
                    if (activeFilters.sector === 'police' && !text.includes('police') && !text.includes('constable') && !text.includes('si ') && !text.includes('sub inspector') && !text.includes('defense') && !text.includes('army') && !text.includes('navy')) matches = false;
                    if (activeFilters.sector === 'banking' && !text.includes('bank') && !text.includes('ibps') && !text.includes('sbi') && !text.includes('rbi') && !text.includes('po ') && !text.includes('clerk')) matches = false;
                    if (activeFilters.sector === 'wbpsc' && !text.includes('bengal') && !text.includes('wbpsc') && !text.includes('wbprb') && !text.includes('wbcsc') && !text.includes('wbbse')) matches = false;
                    if (activeFilters.sector === 'upsc' && !text.includes('upsc') && !text.includes('civil services') && !text.includes('ias') && !text.includes('ips') && !text.includes('nda') && !text.includes('cds')) matches = false;
                }

                // 3. Notice Type Check
                if (matches && activeFilters.type !== 'all') {
                    if (activeFilters.type === 'recruitment' && !text.includes('recruitment') && !text.includes('vacancy') && !text.includes('apply') && !text.includes('posts') && !text.includes('jobs')) matches = false;
                    if (activeFilters.type === 'admit_card' && !text.includes('admit') && !text.includes('hall ticket') && !text.includes('call letter') && !text.includes('city slip')) matches = false;
                    if (activeFilters.type === 'result' && !text.includes('result') && !text.includes('merit') && !text.includes('score') && !text.includes('cutoff') && !text.includes('selected')) matches = false;
                    if (activeFilters.type === 'answer_key' && !text.includes('answer key') && !text.includes('response sheet') && !text.includes('key')) matches = false;
                }

                if (matches) {
                    row.style.display = '';
                    row.classList.remove('filter-hidden');
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    row.classList.add('filter-hidden');
                }
            });

            if (matchCountEl) {
                if (!hasActiveFilter) {
                    matchCountEl.innerText = `Showing All ${noticeRows.length} Updates`;
                } else {
                    matchCountEl.innerText = `Showing ${visibleCount} of ${noticeRows.length} Matches`;
                }
            }
        }

        filterPills.forEach(pill => {
            pill.addEventListener('click', function () {
                const group = this.getAttribute('data-group');
                const val = this.getAttribute('data-val');

                // Toggle active inside group
                const groupPills = smartFilterHub.querySelectorAll(`.smart-filter-pill[data-group="${group}"]`);
                groupPills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');

                activeFilters[group] = val;
                applySmartFilters();
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                activeFilters.qual = 'all';
                activeFilters.sector = 'all';
                activeFilters.type = 'all';

                filterPills.forEach(p => {
                    if (p.getAttribute('data-val') === 'all') {
                        p.classList.add('active');
                    } else {
                        p.classList.remove('active');
                    }
                });

                applySmartFilters();
                showToast('Filters cleared');
            });
        }
    }
});
