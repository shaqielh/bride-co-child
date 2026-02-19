/**
 * Enhanced Mega Menu JavaScript
 * Handles menu interactions and responsive behavior for nested submenus
 * Compatible with existing Bride Co integration
 */
(function($) {
    'use strict';
    
    // Run when the document is ready
    $(document).ready(function() {
        // Initialize the mega menu functionality
        initMegaMenu();
    });
    
    /**
     * Initialize mega menu functionality
     */
    function initMegaMenu() {
        // Setup desktop behavior
        setupDesktopMenu();
        
        // Setup mobile behavior
        setupMobileMenu();
        
        // Handle equal height columns for better appearance
        equalizeColumnHeights();
        
        // Initialize scroll behavior for mobile
        initScrollBehavior();
        
        // Set up accessibility attributes
        setupAccessibility();
        
        // Handle window resize events
        $(window).on('resize', function() {
            // Reset all menus on resize
            $('.dropdown-menu.show').removeClass('show');
            $('.dropdown.show').removeClass('show');
            $('.nav-link').attr('aria-expanded', 'false');
            
            // Re-equalize column heights after resize
            equalizeColumnHeights();
            
            // Adjust scroll behavior
            initScrollBehavior();
        });
        
        // Close mega menu when clicking outside
        $(document).on('click', function(e) {
            if (window.innerWidth >= 992) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu.show').removeClass('show');
                    $('.dropdown.show').removeClass('show');
                    $('.nav-link').attr('aria-expanded', 'false');
                }
            }
        });
    }
    
    /**
     * Setup desktop hover behavior
     */
    function setupDesktopMenu() {
        var $dropdownMenus = $('.navbar-nav .dropdown');
        
        // Add hover event for main dropdown menus
        $dropdownMenus.hover(
            function() {
                if (window.innerWidth >= 992) {
                    var $menu = $(this).find('.dropdown-menu');
                    $(this).addClass('show');
                    $menu.addClass('show');
                    $(this).find('> .nav-link').attr('aria-expanded', 'true');
                }
            },
            function() {
                if (window.innerWidth >= 992) {
                    var $menu = $(this).find('.dropdown-menu');
                    $(this).removeClass('show');
                    $menu.removeClass('show');
                    $(this).find('> .nav-link').attr('aria-expanded', 'false');
                }
            }
        );
        
        // Handle nested submenu interactions
        $('.dropdown-category.has-submenu').hover(
            function() {
                if (window.innerWidth >= 992) {
                    $(this).find('.submenu-list').first().addClass('show');
                    $(this).find('> .submenu-toggle').attr('aria-expanded', 'true');
                }
            },
            function() {
                if (window.innerWidth >= 992) {
                    $(this).find('.submenu-list').first().removeClass('show');
                    $(this).find('> .submenu-toggle').attr('aria-expanded', 'false');
                }
            }
        );
        
        // Prevent parent links with dropdown from navigating when clicked on desktop
        $('.navbar-nav .dropdown > .nav-link').on('click', function(e) {
            if (window.innerWidth >= 992) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
    
    /**
     * Setup mobile menu behavior
     */
    function setupMobileMenu() {
        // Toggle functionality for dropdown toggles on mobile
        $('.navbar-toggler').on('click', function() {
            if ($('.navbar-collapse').hasClass('show')) {
                // Reset all dropdowns when closing the mobile menu
                $('.dropdown-menu.show').removeClass('show');
                $('.dropdown.show').removeClass('show');
                $('.nav-link').attr('aria-expanded', 'false');
            }
        });
        
        // Handle main dropdown toggle clicks on mobile
        $('.dropdown-toggle').on('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();
                
                var $parent = $(this).parent();
                var $menu = $(this).next('.dropdown-menu');
                
                // Toggle this dropdown
                $parent.toggleClass('show');
                $menu.toggleClass('show');
                $(this).attr('aria-expanded', $parent.hasClass('show') ? 'true' : 'false');
            }
        });
        
        // Handle category header clicks on mobile
        $('.dropdown-category.has-submenu h4').on('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();
                
                var $parent = $(this).parent();
                var $submenu = $parent.find('.submenu-list').first();
                
                // Toggle this submenu
                $parent.toggleClass('show');
                $submenu.toggleClass('show');
            }
        });
        
        // Handle submenu toggle clicks on mobile
        $('.submenu-toggle').on('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();
                
                var $parent = $(this).parent();
                var $submenu = $parent.find('.submenu-list').first();
                
                // Toggle this submenu
                $parent.toggleClass('show');
                $submenu.toggleClass('show');
                $(this).attr('aria-expanded', $parent.hasClass('show') ? 'true' : 'false');
            }
        });
        
        // Handle nested submenu toggle clicks on mobile
        $('.nested-submenu-toggle').on('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();
                
                var $parent = $(this).parent();
                var $submenu = $parent.find('.submenu-list-nested').first();
                
                // Toggle this nested submenu
                $parent.toggleClass('show');
                $submenu.toggleClass('show');
                $(this).attr('aria-expanded', $parent.hasClass('show') ? 'true' : 'false');
            }
        });
    }
    
    /**
     * Make sure columns in the mega menu have equal heights
     */
    function equalizeColumnHeights() {
        // Only do this on desktop
        if (window.innerWidth >= 992) {
            $('.mega-menu').each(function() {
                var $columns = $(this).find('.mega-menu-col');
                var maxHeight = 0;
                
                // Reset heights
                $columns.css('height', 'auto');
                
                // Find tallest column
                $columns.each(function() {
                    maxHeight = Math.max(maxHeight, $(this).outerHeight());
                });
                
                // Set all columns to tallest height
                if (maxHeight > 0) {
                    $columns.outerHeight(maxHeight);
                }
            });
        } else {
            // Reset heights on mobile
            $('.mega-menu-col').css('height', 'auto');
        }
    }
    
    /**
     * Initialize scroll behavior for large menus on mobile
     */
    function initScrollBehavior() {
        // Add smooth scrolling for large menus on mobile
        if (window.innerWidth < 992) {
            $('.mega-menu').css('max-height', (window.innerHeight * 0.8) + 'px');
            $('.mega-menu').css('overflow-y', 'auto');
        } else {
            $('.mega-menu').css('max-height', '');
            $('.mega-menu').css('overflow-y', '');
        }
    }
    
    /**
     * Setup accessibility attributes for dropdown menus
     */
    function setupAccessibility() {
        // Add ARIA attributes to dropdown toggles
        $('.dropdown-toggle, .submenu-toggle, .nested-submenu-toggle').attr({
            'aria-haspopup': 'true',
            'aria-expanded': 'false'
        });
        
        // Preload menu images for smoother experience
        $('.mega-menu-image img').each(function() {
            var img = new Image();
            img.src = $(this).attr('src');
        });
        
        // Add transition classes after page load
        setTimeout(function() {
            $('.mega-menu').addClass('transitions-enabled');
        }, 100);
    }
    
})(jQuery);