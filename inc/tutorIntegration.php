<?php

namespace Flynt;

/**
 * Tutor LMS Integration
 * 
 * Ensures Tutor LMS uses the Flynt header.php template
 * The header.php file in the theme root will be used by Tutor's get_header() call
 * 
 * This file ensures proper integration between Tutor and Flynt theme
 */

// Ensure Tutor uses our header.php template
add_action('template_redirect', function() {
    // Only apply to Tutor pages
    if (!function_exists('tutor_utils')) {
        return;
    }
    
    // Tutor's tutor_custom_header() calls get_header() for non-block themes
    // Our header.php will be automatically used by WordPress's get_header() function
    // No additional action needed here - WordPress will find header.php automatically
}, 1);
