/**
 * Jocelyne Saab Theme - Main JavaScript with Hero Video Support
 * Modern ES6+ JavaScript with accessibility and performance in mind
 * Updated: 2025-06-21 04:44:04 UTC by karlserag
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTheme();
        initBackToTop();
    });

    /**
     * Initialize all theme functionality
     */
    function initializeTheme() {
        initHeader();
        initMobileMenu();
        initSmoothScroll();
        initLazyLoading();
        initRotatingText();
        initHeroVideo();
        initCarousels();
        initFormHandling();
        initAccessibility();
    }

    /**
     * Header scroll effects and sticky behavior
     */
    function initHeader() {
        const header = document.getElementById('site-header');
        if (!header) return;
        
        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateHeader() {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // Hide header on scroll down, show on scroll up
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
            
            lastScrollY = currentScrollY;
            ticking = false;
        }

        function requestHeaderUpdate() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestHeaderUpdate, { passive: true });
    }

    /**
     * Mobile menu functionality
     */
    function initMobileMenu() {
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const nav = document.getElementById('main-nav');
        
        if (!toggleBtn || !nav) return;

        toggleBtn.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            this.setAttribute('aria-expanded', !isExpanded);
            nav.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && nav.classList.contains('active')) {
                toggleBtn.setAttribute('aria-expanded', 'false');
                nav.classList.remove('active');
                document.body.classList.remove('menu-open');
                toggleBtn.focus();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!nav.contains(e.target) && !toggleBtn.contains(e.target)) {
                toggleBtn.setAttribute('aria-expanded', 'false');
                nav.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });

        // Close menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                toggleBtn.setAttribute('aria-expanded', 'false');
                nav.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    }

    /**
     * Smooth scrolling for anchor links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    
                    const header = document.getElementById('site-header');
                    const headerHeight = header ? header.offsetHeight : 80;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Lazy loading for images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Rotating text animation in hero
     */
    function initRotatingText() {
        const rotatingContainer = document.querySelector('.rotating-text');
        if (!rotatingContainer) return;

        const texts = rotatingContainer.querySelectorAll('span');
        if (texts.length <= 1) return;

        let currentIndex = 0;

        function rotateText() {
            // Hide all texts
            texts.forEach(text => {
                text.style.opacity = '0';
                text.style.transform = 'translateY(10px)';
            });

            // Show current text
            texts[currentIndex].style.opacity = '1';
            texts[currentIndex].style.transform = 'translateY(0)';

            // Move to next text
            currentIndex = (currentIndex + 1) % texts.length;
        }

        // Initialize first text
        rotateText();

        // Start rotation interval
        setInterval(rotateText, 3000);
    }

    /**
     * Hero Video Controls
     */
    function initHeroVideo() {
        const heroVideo = document.querySelector('.hero-video video');
        const toggleBtn = document.getElementById('hero-video-toggle');
        
        if (!heroVideo || !toggleBtn) return;
        
        const pauseIcon = toggleBtn.querySelector('.pause-icon');
        const playIcon = toggleBtn.querySelector('.play-icon');
        
        // Toggle video play/pause
        toggleBtn.addEventListener('click', function() {
            if (heroVideo.paused) {
                heroVideo.play();
                pauseIcon.style.display = 'inline';
                playIcon.style.display = 'none';
                this.setAttribute('aria-label', 'Pause background video');
            } else {
                heroVideo.pause();
                pauseIcon.style.display = 'none';
                playIcon.style.display = 'inline';
                this.setAttribute('aria-label', 'Play background video');
            }
        });
        
        // Handle video load errors
        heroVideo.addEventListener('error', function() {
            console.log('Hero video failed to load, falling back to background');
            const heroBackground = document.querySelector('.hero-background');
            if (heroBackground) {
                heroBackground.classList.remove('hero-video');
                heroBackground.classList.add('hero-fallback');
            }
            toggleBtn.style.display = 'none';
        });
        
        // Pause video when not in viewport (performance)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (heroVideo.paused && pauseIcon.style.display !== 'none') {
                        heroVideo.play().catch(e => console.log('Video play failed:', e));
                    }
                } else {
                    heroVideo.pause();
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(heroVideo);
        
        // Respect user's motion preferences
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            heroVideo.pause();
            pauseIcon.style.display = 'none';
            playIcon.style.display = 'inline';
            toggleBtn.setAttribute('aria-label', 'Play background video');
        }
    }

    /**
     * Initialize Swiper carousels (if Swiper is loaded)
     */
    function initCarousels() {
        if (typeof Swiper === 'undefined') return;

        // Stories carousel
        if (document.querySelector('.stories-swiper')) {
            new Swiper('.stories-swiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
                keyboard: {
                    enabled: true,
                },
                a11y: {
                    enabled: true,
                },
            });
        }

        // Film stills carousel
        if (document.querySelector('.film-stills-swiper')) {
            new Swiper('.film-stills-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                keyboard: { enabled: true },
                a11y: { enabled: true },
                rtl: document.dir === 'rtl',
            });
        }

        // Trainings/Workshops carousel
        if (document.querySelector('.workshop-gallery-swiper')) {
            new Swiper('.workshop-gallery-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                keyboard: { enabled: true },
                a11y: { enabled: true },
                rtl: document.dir === 'rtl',
            });
        }
    }

    /**
     * Form handling and validation
     */
    function initFormHandling() {
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = this.querySelector('input[type="email"]');
                const button = this.querySelector('button');
                
                if (!email.value || !isValidEmail(email.value)) {
                    showFormMessage(this, 'Please enter a valid email address.', 'error');
                    return;
                }
                
                // Disable button during submission
                button.disabled = true;
                button.textContent = 'Subscribing...';
                
                // Simulate form submission (replace with actual implementation)
                setTimeout(() => {
                    showFormMessage(this, 'Thank you for subscribing!', 'success');
                    email.value = '';
                    button.disabled = false;
                    button.textContent = 'Subscribe';
                }, 1500);
            });
        }
    }

    /**
     * Email validation helper
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Show form messages
     */
    function showFormMessage(form, message, type) {
        const existingMessage = form.querySelector('.form-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        const messageEl = document.createElement('div');
        messageEl.className = `form-message form-message-${type}`;
        messageEl.textContent = message;
        messageEl.setAttribute('role', type === 'error' ? 'alert' : 'status');
        
        form.appendChild(messageEl);

        // Remove message after 5 seconds
        setTimeout(() => {
            if (messageEl.parentNode) {
                messageEl.remove();
            }
        }, 5000);
    }

    /**
     * Accessibility enhancements
     */
    function initAccessibility() {
        // Focus management for modals and dropdowns
        document.addEventListener('keydown', function(e) {
            // Trap focus in mobile menu when open
            if (document.body.classList.contains('menu-open')) {
                const nav = document.getElementById('main-nav');
                if (nav) {
                    trapFocus(e, nav);
                }
            }
        });

        // Add focus indicators for keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Announce dynamic content changes to screen readers
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    const liveRegion = document.querySelector('[aria-live]');
                    if (liveRegion && mutation.target.contains(liveRegion)) {
                        // Content has been updated in a live region
                        console.log('Live region updated');
                    }
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Trap focus within an element
     */
    function trapFocus(e, element) {
        const focusableElements = element.querySelectorAll(
            'a[href], button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])'
        );
        
        const firstFocusableElement = focusableElements[0];
        const lastFocusableElement = focusableElements[focusableElements.length - 1];

        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstFocusableElement) {
                    lastFocusableElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastFocusableElement) {
                    firstFocusableElement.focus();
                    e.preventDefault();
                }
            }
        }
    }

    /**
     * Debounce function for performance
     */
    function debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Expose some functions globally for other scripts
    window.saabTheme = {
        debounce,
        showFormMessage,
        isValidEmail
    };

    // Back to top button
    function initBackToTop() {
        const btn = document.createElement('button');
        btn.className = 'back-to-top';
        btn.setAttribute('aria-label', 'Back to top');
        btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        document.body.appendChild(btn);
        btn.style.display = 'none';
        window.addEventListener('scroll', function() {
            btn.style.display = window.scrollY > 300 ? 'block' : 'none';
        });
        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Menu Overlay Logic
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const menuOverlay = document.getElementById('menu-overlay');
    const menuClose = document.getElementById('menu-overlay-close');
    let lastFocusedElement = null;

    function openMenu() {
        lastFocusedElement = document.activeElement;
        menuOverlay.setAttribute('aria-hidden', 'false');
        menuOverlay.classList.add('open');
        menuToggle.setAttribute('aria-expanded', 'true');
        menuOverlay.focus();
        document.body.classList.add('menu-overlay-open');
    }
    function closeMenu() {
        menuOverlay.setAttribute('aria-hidden', 'true');
        menuOverlay.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-overlay-open');
        if (lastFocusedElement) lastFocusedElement.focus();
    }
    if (menuToggle && menuOverlay && menuClose) {
        menuToggle.addEventListener('click', openMenu);
        menuClose.addEventListener('click', closeMenu);
        menuOverlay.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMenu();
            // Trap focus inside overlay
            if (e.key === 'Tab') {
                const focusable = menuOverlay.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });
    }
    // Close overlay on click outside inner content
    menuOverlay && menuOverlay.addEventListener('click', function(e) {
        if (e.target === menuOverlay) closeMenu();
    });

})();

// Additional functionality for AJAX filtering
if (typeof saabAjax !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        initFiltering();
    });

    function initFiltering() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                const type = this.getAttribute('data-type') || 'category';
                const grid = document.querySelector('#films-grid, #portfolio-grid, #news-grid');
                
                if (!grid) return;

                // Update active states
                document.querySelectorAll(`.filter-btn[data-type="${type}"]`).forEach(b => 
                    b.classList.remove('active')
                );
                this.classList.add('active');

                // Show loading state
                grid.classList.add('loading');

                // Prepare form data
                const formData = new FormData();
                formData.append('action', 'filter_films'); // or appropriate action
                formData.append('category', filter);
                formData.append('nonce', saabAjax.nonce);

                // AJAX request
                fetch(saabAjax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    grid.innerHTML = data;
                    grid.classList.remove('loading');
                    
                    // Announce to screen readers
                    const announcement = document.createElement('div');
                    announcement.setAttribute('aria-live', 'polite');
                    announcement.className = 'sr-only';
                    announcement.textContent = `Filtered content updated. Showing ${filter === 'all' ? 'all items' : filter + ' items'}.`;
                    document.body.appendChild(announcement);
                    
                    setTimeout(() => {
                        if (announcement.parentNode) {
                            document.body.removeChild(announcement);
                        }
                    }, 1000);
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    grid.classList.remove('loading');
                    
                    // Show error message
                    if (window.saabTheme && window.saabTheme.showFormMessage) {
                        window.saabTheme.showFormMessage(grid.parentElement, saabAjax.strings.error || 'An error occurred while filtering.', 'error');
                    }
                });
            });
        });
    }
}