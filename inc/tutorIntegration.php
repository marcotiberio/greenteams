<?php

/**
 * TutorLMS Integration
 *
 * TutorLMS initializes TinyMCE via JavaScript (wp.editor.initialize) bypassing
 * WordPress PHP filters. This injects a script that overrides the toolbar config
 * for all TinyMCE instances on TutorLMS pages.
 */

namespace Flynt\TutorIntegration;

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueueTutorEditorOverride');
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueueTutorEditorOverride');
add_action('wp_footer', __NAMESPACE__ . '\\tutorDashboardLabelOverrides');
add_filter('tutor_filter_course_archive_page', __NAMESPACE__ . '\\useLernenAsCourseArchivePage');

/**
 * Point Tutor's "Course Archive Page" at the Lernen page (/lernen-info) instead
 * of the unused "Modul" page. This drives course_archive_page_url(), which is
 * where the close (X) button on the password-protected course/bundle template
 * links to, as well as other Tutor "back to courses" links.
 *
 * @param int|string $archive_page_id Currently configured archive page ID.
 * @return int|string Lernen page ID when found, otherwise the original value.
 */
function useLernenAsCourseArchivePage($archive_page_id)
{
    $lernen_page = get_page_by_path('lernen-info');

    return $lernen_page ? $lernen_page->ID : $archive_page_id;
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
