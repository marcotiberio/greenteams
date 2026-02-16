<?php

namespace Flynt\Components\BlockIframe;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'blockIframe',
        'label' => __('Block: Iframe Embed', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Iframe URL', 'flynt'),
                'name' => 'iframeUrl',
                'type' => 'url',
                'instructions' => __('Enter the URL of the iframe to embed (e.g., Seatable form link)', 'flynt'),
                'required' => 1,
            ],
            [
                'label' => __('Height', 'flynt'),
                'name' => 'height',
                'type' => 'number',
                'instructions' => __('Height of the iframe in pixels', 'flynt'),
                'default_value' => 600,
                'min' => 100,
                'max' => 2000,
                'step' => 50,
                'required' => 1,
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
                ]
            ]
        ]
    ];
}


