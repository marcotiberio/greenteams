<?php

/**
 * WP Pusher / Tutor LMS update-check crash guard
 *
 * WP Pusher hooks 'http_request_args' (priority 5) to strip its own packages out
 * of the plugin update-check payload, and does so with:
 *
 *     unset($plugins['active'][array_search($plugin, $plugins['active'])]);
 *
 * It assumes the payload always looks like the one WordPress core sends, which
 * carries both a 'plugins' and an 'active' key. Tutor LMS builds its own, much
 * smaller request to api.wordpress.org/plugins/update-check (to render the
 * "What's New" menu badge) and only sends 'plugins'. On PHP 8 the missing
 * 'active' key makes array_search() throw a TypeError, which fatals wp-admin.
 *
 * Running at priority 4 — just ahead of WP Pusher — we normalise the payload so
 * both keys are always arrays. This lives in the theme rather than as a patch to
 * wppusher.php so it survives plugin updates.
 */

namespace Flynt\WpPusherUpdateCheckFix;

add_filter('http_request_args', __NAMESPACE__ . '\\normaliseUpdateCheckPayload', 4, 2);

function normaliseUpdateCheckPayload($args, $url)
{
    if (0 !== strpos($url, 'https://api.wordpress.org/plugins/update-check')) {
        return $args;
    }

    if (!isset($args['body']['plugins']) || !is_string($args['body']['plugins'])) {
        return $args;
    }

    $payload = json_decode($args['body']['plugins'], true);

    if (!is_array($payload)) {
        return $args;
    }

    foreach (['plugins', 'active'] as $key) {
        if (!isset($payload[$key]) || !is_array($payload[$key])) {
            $payload[$key] = [];
        }
    }

    $args['body']['plugins'] = json_encode($payload);

    return $args;
}
