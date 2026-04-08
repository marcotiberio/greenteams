<?php

add_action('template_redirect', function () {
    if (is_singular('lernen')) {
        $url = get_field('redirectUrl');
        if (!empty($url)) {
            wp_redirect($url, 301);
            exit;
        }
    }
});
