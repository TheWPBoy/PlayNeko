// PlayNeko Navigation Handler - Debug Version
(function() {
    'use strict';

    console.log('PlayNeko navigation script loaded');

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavigation);
    } else {
        initNavigation();
    }

    function initNavigation() {
        console.log('Initializing PlayNeko navigation...');

        // Check if we have the required elements
        const mainElement = document.querySelector('main[data-wp-template-part="main"]');
        if (!mainElement) {
            console.warn('Main content area not found. Navigation will not work.');
            return;
        }

        // Initialize header functionality
        initHeaderFunctionality();

        // Add interactive attributes to all relevant links
        addInteractiveAttributes();

        const interactiveLinks = document.querySelectorAll('a[data-wp-interactive]');
        console.log(`Found ${interactiveLinks.length} interactive links`);

        // Add click event listener to the document
        document.addEventListener('click', handleLinkClick);

        // Handle browser back/forward buttons
        window.addEventListener('popstate', handlePopState);

        console.log('PlayNeko navigation initialized successfully');
    }

    function initHeaderFunctionality() {
        console.log('Initializing header functionality...');

        // Header search functionality
        const searchForm = document.querySelector('.search-container .search-form');
        const searchInput = document.querySelector('.search-container .search-field');

        if (searchForm && searchInput) {
            // Handle form submission
            searchForm.addEventListener('submit', function(e) {
                const searchQuery = searchInput.value.trim();
                if (!searchQuery) {
                    e.preventDefault();
                    console.log('Empty search prevented');
                    return;
                }
                console.log('Search form submitted:', searchQuery);
            });

            // Handle Enter key for better UX
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchQuery = this.value.trim();
                    if (!searchQuery) {
                        e.preventDefault();
                        console.log('Empty search prevented');
                    } else {
                        console.log('Search triggered via Enter key:', searchQuery);
                        searchForm.submit();
                    }
                }
            });
        }

        // Search overlay functionality
        const searchButton = document.getElementById('search-button');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.getElementById('search-close');

        if (searchButton && searchOverlay && searchClose) {
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                searchOverlay.classList.add('active');
                console.log('Search overlay opened');

                // Focus on search input
                const searchInput = searchOverlay.querySelector('.search-field');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });

            searchClose.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
                console.log('Search overlay closed');
            });

            // Close search overlay when clicking outside
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.remove('active');
                    console.log('Search overlay closed by clicking outside');
                }
            });

            // Close search overlay with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                    searchOverlay.classList.remove('active');
                    console.log('Search overlay closed with Escape key');
                }
            });
        }

        // Mobile menu dropdown functionality
        const menuButton = document.getElementById('menu-button');
        const mobileMenuDropdown = document.getElementById('mobile-menu-dropdown');

        if (menuButton && mobileMenuDropdown) {
            menuButton.addEventListener('click', function(e) {
                e.preventDefault();
                mobileMenuDropdown.classList.toggle('active');
                console.log('Mobile menu dropdown toggled');
            });

            // Close mobile menu dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!menuButton.contains(e.target) && !mobileMenuDropdown.contains(e.target)) {
                    mobileMenuDropdown.classList.remove('active');
                    console.log('Mobile menu dropdown closed by clicking outside');
                }
            });

            // Close mobile menu dropdown with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenuDropdown.classList.contains('active')) {
                    mobileMenuDropdown.classList.remove('active');
                    console.log('Mobile menu dropdown closed with Escape key');
                }
            });

            // Close mobile menu when a menu item is clicked
            const mobileMenuLinks = mobileMenuDropdown.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenuDropdown.classList.remove('active');
                    console.log('Mobile menu dropdown closed after menu item click');
                });
            });
        }

        console.log('Header functionality initialized');
    }

    function addInteractiveAttributes() {
        // Add data-wp-interactive to nav menu links if missing (backup solution)
        const navLinks = document.querySelectorAll('#site-navigation a, .main-navigation a, .mobile-menu-list a');
        navLinks.forEach(link => {
            if (!link.hasAttribute('data-wp-interactive')) {
                link.setAttribute('data-wp-interactive', '');
                console.log('Added data-wp-interactive to nav link:', link.href);
            }
        });

        // Add data-wp-interactive to pagination links if missing (backup solution)
        const paginationLinks = document.querySelectorAll('.nav-links a, .page-numbers, .pagination a');
        paginationLinks.forEach(link => {
            if (!link.hasAttribute('data-wp-interactive')) {
                link.setAttribute('data-wp-interactive', '');
                console.log('Added data-wp-interactive to pagination link:', link.href);
            }
        });
    }

    function handleLinkClick(event) {
        console.log('Link click event:', event.target);
        // Only handle clicks on links with data-wp-interactive
        const link = event.target.closest('a[data-wp-interactive]');
        if (!link) {
            console.log('No data-wp-interactive link found for click target:', event.target);
            return;
        }

        const targetUrl = link.href;
        console.log('🔗 Link clicked:', targetUrl);

        // Don't handle if:
        // - Link is not internal
        // - Right-click or modifier keys pressed
        if (!targetUrl ||
            !targetUrl.startsWith(window.location.origin) ||
            event.button !== 0 ||
            event.metaKey ||
            event.ctrlKey ||
            event.shiftKey) {
            console.log('⏭️ Navigation skipped - external link or modifier key');
            return;
        }

        // Prevent default link behavior
        event.preventDefault();
        console.log('🚀 Starting AJAX navigation...');

        // Add loading state
        document.body.classList.add('is-navigating');

        // Fetch the new page content
        fetch(targetUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('📡 Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(html => {
                console.log('📄 HTML content received, length:', html.length);

                // Create a temporary element to parse the HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update the main content area
                const newMain = doc.querySelector('main[data-wp-template-part="main"]');
                const currentMain = document.querySelector('main[data-wp-template-part="main"]');

                if (newMain && currentMain) {
                    currentMain.innerHTML = newMain.innerHTML;
                    console.log('✅ Main content updated successfully');
                } else {
                    console.error('❌ Main content areas not found');
                    console.log('New main:', !!newMain, 'Current main:', !!currentMain);
                    throw new Error('Main content areas not found');
                }

                // Update the URL without page reload
                window.history.pushState({ url: targetUrl }, '', targetUrl);

                // Update document title
                if (doc.title) {
                    document.title = doc.title;
                    console.log('📝 Title updated:', doc.title);
                }

                // Re-scan for interactive links in the new content
                addInteractiveAttributes();
                console.log('🔄 Re-scanned for interactive links in new content');

                // Scroll to top
                window.scrollTo(0, 0);

                // Remove loading state
                document.body.classList.remove('is-navigating');
                console.log('🎉 Navigation completed successfully');
            })
            .catch(error => {
                console.error('❌ Navigation failed:', error);
                // Remove loading state
                document.body.classList.remove('is-navigating');
                // Fallback to traditional navigation on error
                console.log('🔄 Falling back to traditional navigation');
                window.location.href = targetUrl;
            });
    }

    function handlePopState(event) {
        console.log('⬅️ Browser back/forward button pressed');
        window.location.reload();
    }

})();