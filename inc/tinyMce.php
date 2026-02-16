<?php

/**
 * Moves most relevant editor buttons to the first toolbar
 * and provides config for creating new toolbars, block formats, and style formats.
 * See the TinyMce documentation for more information: https://www.tiny.cloud/docs/
 *
 */

namespace Flynt\TinyMce;

use Flynt\Utils\Asset;

// Add tinyMce styles to editor
add_action('admin_init', function () {
    add_editor_style(Asset::requireUrl('assets/tinymce.scss'));
});

// First Toolbar
add_filter('mce_buttons', function ($buttons) {
    $config = getConfig();
    if ($config && isset($config['toolbars'])) {
        $toolbars = $config['toolbars'];
        if (isset($toolbars['default']) && isset($toolbars['default'][0])) {
            return $toolbars['default'][0];
        }
    }
    return $buttons;
});

// Second Toolbar
add_filter('mce_buttons_2', '__return_empty_array');

add_filter('tiny_mce_before_init', function ($init) {
    $config = getConfig();
    if ($config) {
        if (isset($config['blockformats'])) {
            $init['block_formats'] = getBlockFormats($config['blockformats']);
        }

        if (isset($config['styleformats'])) {
            // Send it to style_formats as true js array
            $init['style_formats'] = json_encode($config['styleformats']);
        }

        if (isset($config['textcolor_map'])) {
            // Send it to textcolor_map as true js array
            $init['textcolor_map'] = json_encode($config['textcolor_map']);
        }
    }
    return $init;
});

add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
    // Load Toolbars and parse them into TinyMCE
    $config = getConfig();
    if ($config && !empty($config['toolbars'])) {
        $toolbars = array_map(function ($toolbar) {
            array_unshift($toolbar, []);
            return $toolbar;
        }, $config['toolbars']);
    }
    return $toolbars;
});

function getBlockFormats($blockFormats)
{
    if (!empty($blockFormats)) {
        $blockFormatStrings = array_map(
            function ($tag, $label) {
                return "${label}=${tag}";
            },
            $blockFormats,
            array_keys($blockFormats)
        );
        return implode(';', $blockFormatStrings);
    }
    return '';
}

function getConfig()
{
    return [
        'textcolor_map' => [
            '303030', 'Black',
            'fffeeB', 'White',
            'ffdd47', 'yellow',
            'ff904f', 'Orange',
            '7bdb8f', 'Green',
            'f6f4ca', 'Beige',
            'b4d9e8', 'Lightblue',
            '3468cd', 'Blue',
        ],
        'blockformats' => [
            'Paragraph' => 'p',
            'Heading 1' => 'h1',
            'Heading 2' => 'h2',
            'Heading 3' => 'h3',
        ],
        'styleformats' => [
            // [
            //     'title' => 'Headings',
            //     'icon' => '',
            //     'items' => [
            //         [
            //             'title' => 'Title',
            //             'classes' => 'font-heroTitle',
            //             'selector' => '*'
            //         ],
            //         [
            //             'title' => 'Subtitle',
            //             'classes' => 'font-heroSubtitle',
            //             'selector' => '*'
            //         ],
            //         [
            //             'title' => 'Heading 3',
            //             'classes' => 'h3',
            //             'selector' => '*'
            //         ],
            //         [
            //             'title' => 'Heading 4',
            //             'classes' => 'h4',
            //             'selector' => '*'
            //         ],
            //     ]
            // ],
            // [
            //     'title' => 'Text',
            //     'icon' => '',
            //     'items' => [
            //         [
            //             'title' => 'Small',
            //             'classes' => 'font-bodySmall',
            //             'selector' => '*'
            //         ],
            //         [
            //             'title' => 'Large',
            //             'classes' => 'font-bodyLarge',
            //             'selector' => '*'
            //         ],
            //     ]
            // ],
            [
                'title' => 'Buttons',
                'icon' => '',
                'items' => [
                    [
                        'title' => 'Button Green Text / White Background',
                        'classes' => 'button button--fullGreen',
                        'selector' => 'a'
                    ],
                    [
                        'title' => 'Button Blue Text / White Background',
                        'classes' => 'button button--fullBlue',
                        'selector' => 'a'
                    ],
                    [
                        'title' => 'Button Orange Text / White Background',
                        'classes' => 'button button--fullOrange',
                        'selector' => 'a'
                    ],
                    [
                        'title' => 'Button Black Text / White Background',
                        'classes' => 'button button--fullBlack',
                        'selector' => 'a'
                    ],
                    [
                        'title' => 'Button White Text / Black Background',
                        'classes' => 'button button--fullWhite',
                        'selector' => 'a'
                    ],
                    [
                        'title' => 'Button Black Text / White Background',
                        'classes' => 'button button--outline',
                        'selector' => 'a'
                    ]
                ]
            ],
        ],
        'toolbars' => [
            'default' => [
                [
                    'formatselect',
                    'styleselect',
                    'bold',
                    'italic',
                    'blockquote',
                    'forecolor',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'alignjustify',
                    '|',
                    'hr',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'removeformat'
                ]
            ],
            'basic' => [
                [
                    'formatselect',
                    'styleselect',
                    'bold',
                    'italic',
                    'blockquote',
                    'forecolor',
                    '|',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'alignjustify',
                    '|',
                    'hr',
                    '|',
                    'bullist',
                    'numlist',
                    '|',
                    'link',
                    'unlink',
                    '|',
                    'removeformat'
                ]
            ]
        ]
    ];
}
