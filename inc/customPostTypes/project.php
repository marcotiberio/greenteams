<?php

/**
 * This is an example file showcasing how you can add custom post types to your Flynt theme.
 *
 * For a full list of parameters see https://developer.wordpress.org/reference/functions/register_post_type/ or use https://generatewp.com/post-type/ to generate the code for you.
 */

namespace Flynt\CustomPostTypes;

function registerProjectPostType()
{
    $labels = [
        'name'                  => _x('Projekte', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Projekt', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Projekte', 'flynt'),
        'name_admin_bar'        => __('Projekte', 'flynt'),
        'archives'              => __('Item Archives', 'flynt'),
        'attributes'            => __('Item Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Items', 'flynt'),
        'add_new_item'          => __('Add New Item', 'flynt'),
        'add_new'               => __('Add New', 'flynt'),
        'new_item'              => __('New Item', 'flynt'),
        'edit_item'             => __('Edit Item', 'flynt'),
        'update_item'           => __('Update Item', 'flynt'),
        'view_item'             => __('View Item', 'flynt'),
        'view_items'            => __('View Items', 'flynt'),
        'search_items'          => __('Search Item', 'flynt'),
        'not_found'             => __('Not found', 'flynt'),
        'not_found_in_trash'    => __('Not found in Trash', 'flynt'),
        'featured_image'        => __('Featured Image', 'flynt'),
        'set_featured_image'    => __('Set featured image', 'flynt'),
        'remove_featured_image' => __('Remove featured image', 'flynt'),
        'use_featured_image'    => __('Use as featured image', 'flynt'),
        'insert_into_item'      => __('Insert into item', 'flynt'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'flynt'),
        'items_list'            => __('Items list', 'flynt'),
        'items_list_navigation' => __('Items list navigation', 'flynt'),
        'filter_items_list'     => __('Filter items list', 'flynt'),
    ];
    $args = [
        'label'                 => __('Projekt', 'flynt'),
        'description'           => __('Projekt Description', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'thumbnail'],
        'taxonomies'            => ['format', 'modus', 'projektthema', 'projektziel', 'projektbranche'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 9,
        'menu_icon'             => 'dashicons-hammer',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        // Singles live under the overview page: /projekte/{slug}/
        'rewrite'               => ['slug' => 'projekte', 'with_front' => false],
    ];
    register_post_type('project', $args);
}

add_action('init', '\\Flynt\\CustomPostTypes\\registerProjectPostType');

// Flush rewrite rules once after the project slug changes.
const PROJECT_REWRITE_VERSION = 'projekte-1';

add_action('init', function () {
    if (get_option('flynt_project_rewrite_version') !== PROJECT_REWRITE_VERSION) {
        flush_rewrite_rules(false);
        update_option('flynt_project_rewrite_version', PROJECT_REWRITE_VERSION);
    }
}, 99);

// 301 the old /project/{slug}/ URLs to /projekte/{slug}/.
add_action('template_redirect', function () {
    if (!is_404()) {
        return;
    }
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
    if (!preg_match('#^project/([^/]+)$#', $path, $matches)) {
        return;
    }
    $project = get_page_by_path(sanitize_title($matches[1]), OBJECT, 'project');
    if ($project && $project->post_status === 'publish') {
        wp_safe_redirect(get_permalink($project), 301);
        exit;
    }
});
