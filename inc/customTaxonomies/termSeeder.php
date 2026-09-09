<?php

/**
 * Helper to populate a taxonomy with a fixed set of default terms.
 *
 * The seeding runs once per taxonomy and version. Terms an editor removes later
 * are not recreated — bump the $version argument to roll out a changed list.
 */

namespace Flynt\CustomTaxonomies;

function seedDefaultTerms($taxonomy, $terms, $version = 1)
{
    $optionName = "flynt_seeded_terms_{$taxonomy}";

    if ((int) get_option($optionName) >= (int) $version) {
        return;
    }

    if (!taxonomy_exists($taxonomy)) {
        return;
    }

    foreach ($terms as $term) {
        if (!term_exists($term, $taxonomy)) {
            wp_insert_term($term, $taxonomy);
        }
    }

    update_option($optionName, (int) $version, false);
}
