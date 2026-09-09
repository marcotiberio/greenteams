<?php

/**
 * Projektziel taxonomy — the goal a project pursues.
 *
 * For a full list of parameters see https://developer.wordpress.org/reference/functions/register_taxonomy/
 */

namespace Flynt\CustomTaxonomies;

const PROJEKTZIEL_DEFAULT_TERMS = [
    'Green-Team-Mitglieder gewinnen',
    'Bewusstsein & Haltung verändern',
    'Wissen & Kompetenzen stärken',
    'Daten & Transparenz schaffen',
    'Aktivieren & Erleben ermöglichen',
    'Unternehmensfußabdruck verbessern',
    'Ideen & Austausch fördern',
    'Prozesse & Strukturen verändern',
    'Angebote & Geschäftsmodelle verändern',
    'Arbeitsumfeld verändern',
    'Gesellschaft sensibilisieren',
    'Politisch einwirken',
];

function registerProjektzielTaxonomy()
{
    $labels = [
        'name'                       => _x('Projektziele', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Projektziel', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Projektziel', 'flynt'),
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
        'rewrite'                    => ['slug' => 'projektziel'],
    ];

    register_taxonomy('projektziel', ['project'], $args);

    seedDefaultTerms('projektziel', PROJEKTZIEL_DEFAULT_TERMS);
}

add_action('init', 'Flynt\\CustomTaxonomies\\registerProjektzielTaxonomy');
