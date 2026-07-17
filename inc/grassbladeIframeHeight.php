<?php

/**
 * GrassBlade xAPI iframe height
 *
 * GrassBlade renders xAPI content inside an iframe (.grassblade_iframe) with a
 * fixed height taken from its plugin settings (default 640px). That height is
 * applied both to the iframe element and passed into the embedded content URL
 * (h=...), so overriding it purely in CSS would leave the content letterboxed.
 *
 * Filtering the shortcode attributes instead makes the iframe — and the content
 * inside it — fill the full viewport height. We use "100vh" rather than "100%":
 * a literal percentage triggers GrassBlade's aspect-ratio path, which sizes the
 * iframe square (height = width) instead of full height.
 */

namespace Flynt\GrassBlade;

add_filter('grassblade_shortcode_atts', __NAMESPACE__ . '\\fullHeightIframe', 10, 2);

function fullHeightIframe($atts, $rawAtts)
{
    // Respect an explicit height set on an individual [grassblade] shortcode.
    if (empty($rawAtts['height'])) {
        $atts['height'] = '100vh';
    }

    return $atts;
}
