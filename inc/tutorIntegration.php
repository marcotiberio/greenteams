<?php

/**
 * TutorLMS Integration
 *
 * TutorLMS initializes TinyMCE via JavaScript (wp.editor.initialize) bypassing
 * WordPress PHP filters. This injects a script that overrides the toolbar config
 * for all TinyMCE instances on TutorLMS pages.
 */

namespace Flynt\TutorIntegration;

const LERNEN_PAGE_URL = 'https://greenteams-netzwerk.eco/lernen-info/';

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueueTutorEditorOverride');
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueueTutorEditorOverride');
add_action('wp_footer', __NAMESPACE__ . '\\tutorDashboardLabelOverrides');
add_action('template_redirect', __NAMESPACE__ . '\\overridePasswordProtectedArchiveLink');
add_filter('hide_admin_bar_for_users', __NAMESPACE__ . '\\allowInstructorWpAdmin');

/**
 * Let instructors back into the WP admin area while keeping it restricted for
 * subscribers/students.
 *
 * Tutor Pro blocks non-admins from wp-admin when the `hide_admin_bar_for_users`
 * option is on (see Frontend::restrict_wp_admin_area / has_admin_area_access).
 * That check is role-based, not capability-based, so there is no capability to
 * grant — instead we force the option off for users with the instructor role,
 * leaving it enabled for everyone else.
 *
 * Tutor exposes each option through a filter named after the option key
 * (Utils::get_option runs `apply_filters( $key, $value )`), so we hook the
 * `hide_admin_bar_for_users` key and return false for instructors.
 *
 * @param mixed $value Current option value (boolean once on/off is normalised).
 * @return mixed
 */
function allowInstructorWpAdmin($value)
{
    if (!function_exists('tutor')) {
        return $value;
    }

    $user = wp_get_current_user();
    if ($user && in_array(tutor()->instructor_role, (array) $user->roles, true)) {
        return false;
    }

    return $value;
}

/**
 * Rewrite the course-archive link on the password-protected course/bundle screen
 * to the Lernen page.
 *
 * Tutor renders that screen from a hardcoded plugin template
 * (tutor/templates/single/password-protected.php) whose close (X) button links to
 * tutor_utils()->course_archive_page_url() — i.e. the unused "Modul" page. We can't
 * override that template from the theme, and repointing Tutor's "Course Archive
 * Page" setting would turn the Lernen page itself into a course-archive listing.
 *
 * So instead we buffer this specific screen's output and swap the archive URL for
 * the Lernen page URL, leaving Tutor's archive configuration (and the Lernen page's
 * own content) untouched.
 */
function overridePasswordProtectedArchiveLink()
{
    if (!function_exists('tutor') || !function_exists('tutor_utils')) {
        return;
    }

    $postTypes = [tutor()->course_post_type, tutor()->bundle_post_type];
    if (!is_singular($postTypes) || !post_password_required()) {
        return;
    }

    $archiveUrl = tutor_utils()->course_archive_page_url();
    if (empty($archiveUrl)) {
        return;
    }

    ob_start(function ($html) use ($archiveUrl) {
        return str_replace(esc_url($archiveUrl), esc_url(LERNEN_PAGE_URL), $html);
    });
}

function enqueueTutorEditorOverride()
{
    if (!function_exists('tutor')) {
        return;
    }

    $config = \Flynt\TinyMce\getConfig();

    $blockFormats = \Flynt\TinyMce\getBlockFormats($config['blockformats']);
    $styleFormats = json_encode($config['styleformats']);
    $textcolorMap = json_encode($config['textcolor_map']);
    $toolbar1 = implode(',', $config['toolbars']['default'][0]);

    wp_add_inline_script('wp-tinymce', "
        (function() {
            if (typeof tinymce === 'undefined') return;

            tinymce.on('AddEditor', function(e) {
                var editor = e.editor;

                editor.settings.toolbar1 = " . json_encode($toolbar1) . ";
                editor.settings.toolbar2 = '';
                editor.settings.block_formats = " . json_encode($blockFormats) . ";
                editor.settings.style_formats = " . $styleFormats . ";
                editor.settings.textcolor_map = " . $textcolorMap . ";
            });
        })();
    ");
}

function tutorDashboardLabelOverrides()
{
    if (!function_exists('tutor')) {
        return;
    }
    ?>
    <script>
        (function() {
            var row = document.querySelector('.tutor-dashboard-profile-data .tutor-row:nth-child(7)');
            if (row) {
                var label = row.querySelector('.tutor-fs-6.tutor-color-secondary');
                if (label) {
                    label.textContent = 'Organisation/Position';
                }
            }
        })();
    </script>
    <?php
}
