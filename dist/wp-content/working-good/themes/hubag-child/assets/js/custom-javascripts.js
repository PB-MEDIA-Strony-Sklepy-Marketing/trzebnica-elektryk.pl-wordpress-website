// Voltmont Panel Enhanced Interactions
document.addEventListener('DOMContentLoaded', function() {
    const panel = document.querySelector('.voltmont-panel');
    const phoneLink = document.querySelector('.voltmont-phone');
    
    // Tracking otwarć panelu
    if (panel) {
        panel. addEventListener('mouseenter', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'panel_open', {
                    'event_category': 'engagement',
                    'event_label': 'emergency_panel'
                });
            }
        });
    }
    
    // Tracking kliknięć w telefon
    if (phoneLink) {
        phoneLink.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'contact',
                    'event_label': 'emergency_phone'
                });
            }
        });
    }
    
    // Automatyczne pokazanie panelu po 30 sekundach (pierwszy raz)
    if (! sessionStorage.getItem('voltmont_panel_shown')) {
        setTimeout(function() {
            if (panel) {
                panel. style.left = '0';
                setTimeout(function() {
                    panel.style.left = '';
                }, 3000);
                sessionStorage.setItem('voltmont_panel_shown', 'true');
            }
        }, 30000);
    }
});

/**
 * Voltmont Slideout Widgets Enhanced Interactions
 * 
 * @package Voltmont
 * @author PB MEDIA
 * @version 1. 0.0
 */

(function($) {
    'use strict';
    
    /**
     * Initialize Slideout Widgets
     */
    function initVoltmontSlideoutWidgets() {
        const $widgets = $('.voltmont-slideout');
        
        if (!$widgets.length) return;
        
        // Tracking otwarć widgetów
        $widgets.on('mouseenter', function() {
            const widgetType = $(this).attr('class').match(/voltmont-slideout-(\w+)/)[1];
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'widget_open', {
                    'event_category': 'engagement',
                    'event_label': widgetType
                });
            }
        });
        
        // Tracking kliknięć w linki
        $('. voltmont-widget-link').on('click', function(e) {
            const linkType = $(this).closest('.voltmont-slideout').attr('class').match(/voltmont-slideout-(\w+)/)[1];
            const linkHref = $(this).attr('href');
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'widget_click', {
                    'event_category': 'conversion',
                    'event_label': linkType,
                    'value': linkHref
                });
            }
        });
        
        // Dodanie tooltipów
        $widgets.each(function() {
            const $handler = $(this).find('.voltmont-slideout-handler');
            const widgetType = $(this).attr('class').match(/voltmont-slideout-(\w+)/)[1];
            
            let tooltipText = '';
            switch(widgetType) {
                case 'facebook':
                    tooltipText = 'Śledź nas na Facebook';
                    break;
                case 'email':
                    tooltipText = 'Napisz do nas';
                    break;
                case 'phone':
                    tooltipText = 'Zadzwoń teraz - 24/7';
                    break;
                case 'location':
                    tooltipText = 'Zobacz lokalizację';
                    break;
            }
            
            $handler.attr('data-tooltip', tooltipText);
        });
        
        // Auto-show po 10 sekundach (pierwsze odwiedziny)
        if (! sessionStorage.getItem('voltmont_widgets_shown')) {
            setTimeout(function() {
                $widgets. first().css('right', '0');
                setTimeout(function() {
                    $widgets.first().css('right', '');
                }, 2000);
                sessionStorage.setItem('voltmont_widgets_shown', 'true');
            }, 10000);
        }
        
        // Zamknij wszystkie inne przy otwarciu jednego
        $widgets.on('mouseenter', function() {
            const $this = $(this);
            $widgets.not($this).removeClass('is-open');
            $this. addClass('is-open');
        });
        
        // Dodaj klasę is-open
        $widgets.on('mouseleave', function() {
            $(this).removeClass('is-open');
        });
    }
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initVoltmontSlideoutWidgets();
    });
    
})(jQuery);