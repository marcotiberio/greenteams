<?php

/**
 * Branche taxonomy for projects.
 *
 * Deliberately separate from the `branche` taxonomy used by blog posts, so the
 * project industry list and the blog industry list stay independent.
 *
 * For a full list of parameters see https://developer.wordpress.org/reference/functions/register_taxonomy/
 */

namespace Flynt\CustomTaxonomies;

const PROJEKTBRANCHE_DEFAULT_TERMS = [
    'Bau, Immobilien & Infrastruktur',
    'Bildung, Wissenschaft & Forschung',
    'Chemie & Rohstoffe',
    'Elektronik, Maschinenbau & Industrie',
    'Energie, Umwelt & Entsorgung',
    'Ernährung & Landwirtschaft',
    'Finanzen, Beratung & Recht',
    'Gastronomie, Tourismus & Freizeit',
    'Gesundheit & Soziales',
    'Handel, Textil & Konsumgüter',
    'Medien, PR & Kommunikation',
    'Mobilität, Transport & Logistik',
    'IT, Software & Telekommunikation',
    'Öffentlicher Sektor & Verbände',
    'Sonstige',
];

function registerProjektbrancheTaxonomy()
{
    $labels = [
        'name'                       => _x('Branchen', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Branche', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Branche', 'flynt'),
        'all_items'                  => __('All Items', 'flynt'),
        'parent_item'                => __('Parent Item', 'flynt'),
        'parent_item_colon'          => __('Parent Item:', 'flynt'),
        'new_item_name'              => __('New Item Name', 'flynt'),
        'add_new_item'               => __('Add New Item', 'flynt'),
        'edit_item'                  => __('Edit Item', 'flynt'),
        'update_item'                => __('Update Item', 'flynt'),
        'view_item'                  => __('View Item', 'flynt'),
        'separate_items_with_commas' => __('Separate items with commas', 'flynt'),
        'add_or_remove_items'        => __('Add or remove items', 'flynt'),
        'choose_from_most_used'      => __('Choose from the most used', 'flynt'),
        'popular_items'              => __('Popular Items', 'flynt'),
        'search_items'               => __('Search Items', 'flynt'),
        'not_found'                  => __('Not Found', 'flynt'),
        'no_terms'                   => __('No items', 'flynt'),
        'items_list'                 => __('Items list', 'flynt'),
        'items_list_navigation'      => __('Items list navigation', 'flynt'),
    ];
    $args = [
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'rewrite'                    => ['slug' => 'projektbranche'],
    ];

    register_taxonomy('projektbranche', ['project'], $args);

    seedDefaultTerms('projektbranche', PROJEKTBRANCHE_DEFAULT_TERMS);
}

add_action('init', 'Flynt\\CustomTaxonomies\\registerProjektbrancheTaxonomy');
