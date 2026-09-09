<?php

namespace Flynt\Components\ListingProjects;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'project';

// Taxonomies offered as filter columns, in display order.
const FILTER_TAXONOMIES = [
    'projektthema' => 'Projektthema',
    'projektziel' => 'Projektziel',
    'projektbranche' => 'Branche',
];

add_filter('Flynt/addComponentData?name=ListingProjects', function ($data) {
    $postType = POST_TYPE;
    $data['taxonomies'] = $data['taxonomies'] ?? [];

    // Restrict the listing to the Projektthema terms picked by the editor (if any)
    $themaIds = !empty($data['taxonomies'])
        ? array_map(fn($taxonomy) => $taxonomy->term_id, $data['taxonomies'])
        : [];

    $queryArgs = [
        'post_status' => 'publish',
        'post_type' => $postType,
        'ignore_sticky_posts' => 1,
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    if (!empty($themaIds)) {
        $queryArgs['tax_query'] = [
            [
                'taxonomy' => 'projektthema',
                'field'    => 'term_id',
                'terms'    => $themaIds,
            ],
        ];
    }

    $posts = Timber::get_posts($queryArgs);
    $postsArray = iterator_to_array($posts);

    $data['posts'] = $postsArray;

    // Only offer filters for terms that are actually used by the listed posts
    $postIds = array_map(fn($post) => $post->ID, $postsArray);
    $data['filterGroups'] = [];

    foreach (FILTER_TAXONOMIES as $taxonomy => $label) {
        $terms = getUsedTerms($taxonomy, $postIds);

        if (empty($terms)) {
            continue;
        }

        $data['filterGroups'][] = [
            'taxonomy' => $taxonomy,
            'label' => $label,
            'terms' => $terms,
        ];
    }

    return $data;
});

/**
 * Return the terms of a taxonomy that are assigned to at least one of the given
 * posts, sorted by name.
 */
function getUsedTerms($taxonomy, $postIds)
{
    if (empty($postIds)) {
        return [];
    }

    $terms = wp_get_object_terms($postIds, $taxonomy, ['orderby' => 'name']);

    return is_wp_error($terms) ? [] : $terms;
}

function getACFLayout()
{
    return [
        'name' => 'ListingProjects',
        'label' => 'Projects',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'headlineTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Projektthema', 'flynt'),
                'instructions' => __('Select 1 or more Projektthema terms or leave empty to show all projects.', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
                'taxonomy' => 'projektthema',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object'
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                ]
            ]
        ],
    ];
}
