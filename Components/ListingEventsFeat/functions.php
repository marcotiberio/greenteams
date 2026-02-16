<?php

namespace Flynt\Components\ListingEventsFeat;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'event';

add_filter('Flynt/addComponentData?name=ListingEventsFeat', function ($data) {
    $postType = POST_TYPE;
    $data['taxonomies'] = $data['taxonomies'] ?? [];

    // Fetch all terms from the modus taxonomy
    $data['categories'] = Timber::get_terms([
        'taxonomy'   => 'modus',
        'hide_empty' => false,
    ]);

    // Fetch posts based on selected categories (if any)
    $categoryIds = !empty($data['taxonomies'])
        ? join(',', array_map(fn($taxonomy) => $taxonomy->term_id, $data['taxonomies']))
        : '';

    $today = date('Ymd');

    $queryArgs = [
        'post_status' => 'publish',
        'post_type' => $postType,
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 10,
        'meta_key' => 'end_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key' => 'end_date',
                'value' => $today,
                'compare' => '>=',
            ],
        ],
    ];

    if (!empty($categoryIds)) {
        $queryArgs['tax_query'] = [
            [
                'taxonomy' => 'modus',
                'field'    => 'term_id',
                'terms'    => explode(',', $categoryIds),
            ],
        ];
    }

    $posts = Timber::get_posts($queryArgs);
    
    // Filter out recurring events
    $filteredPosts = array_filter(iterator_to_array($posts), function($post) {
        $isRecurring = get_field('is_recurring', $post->ID);
        return !$isRecurring;
    });
    
    // Limit to 3 posts after filtering
    $data['posts'] = array_slice($filteredPosts, 0, 3);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingEventsFeat',
        'label' => 'Events (Latest 3)',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text'
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
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
