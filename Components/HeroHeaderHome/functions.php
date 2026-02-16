<?php

namespace Flynt\Components\HeroHeaderHome;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

Options::addTranslatable('HeroHeaderHome', [
    [
        'label' => __('Title', 'flynt'),
        'name' => 'titleTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Title', 'flynt'),
        'name' => 'pageTitle',
        'type' => 'text',
    ],
]);
