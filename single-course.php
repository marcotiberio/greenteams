<?php

use Timber\Timber;

/**
 * Override WordPress singular template with Flynt components
 * This ensures Flynt header/footer are used instead of WordPress defaults
 */
add_action('template_redirect', function() {
    global $post;
    
    // Check if this is a course post type (Tutor LMS uses 'tutor_course' or 'courses')
    $course_post_types = ['tutor_course', 'courses', 'course'];
    
    if ($post && in_array($post->post_type, $course_post_types)) {
        // Remove WordPress default header/footer calls
        remove_all_actions('get_header');
        remove_all_actions('get_footer');
        remove_all_actions('get_sidebar');
        
        // Disable Tutor LMS header and footer
        add_filter('tutor_disable_header', '__return_true', 999);
        add_filter('tutor_disable_footer', '__return_true', 999);
        
        // Remove Tutor LMS template hooks
        remove_action('wp_head', 'tutor_header_output', 10);
        remove_action('wp_footer', 'tutor_footer_output', 10);
        
        // Prevent WordPress from loading default singular template parts
        add_filter('get_template_part', function($slug, $name) {
            if (in_array($slug, ['header', 'footer', 'sidebar'])) {
                return false;
            }
            return $slug;
        }, 10, 2);
        
        // Override singular template to use Flynt components
        add_filter('single_template_hierarchy', function($templates) {
            // Ensure our template is used
            return $templates;
        });
    }
}, 1);

$context = Timber::context();

Timber::render('templates/single-course.twig', $context);
