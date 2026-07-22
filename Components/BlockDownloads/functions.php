<?php

namespace Flynt\Components\BlockDownloads;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockDownloads',
        'label' => 'Block: Downloads',
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContent',
                'type' => 'text'
            ],
            [
                'label' => __('Downloads', 'flynt'),
                'name' => 'repeaterDownloads',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add File', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('File', 'flynt'),
                        'name' => 'file',
                        'type' => 'file',
                        'return_format' => 'array',
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Open PDF directly (no overlay)', 'flynt'),
                        'instructions' => __('Only affects PDF files. By default they open in an in-page overlay. Enable this to open the file directly in a new tab / download instead.', 'flynt'),
                        'name' => 'noModal',
                        'type' => 'true_false',
                        'ui' => 1,
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                ]
            ],
        ],
    ];
}
