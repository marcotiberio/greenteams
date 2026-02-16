<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'eventMeta',
        'title' => 'Main Content',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'introTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Recurring Event',
                'instructions' => 'Check if this is a recurring event',
                'name' => 'is_recurring',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 0,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
            [
                'label' => __('Recurring Event Details', 'flynt'),
                'name' => 'recurringEventDetails',
                'type' => 'text',
                'instructions' => __('Optional text to describe the recurring schedule, e.g. "Every first Monday of the month" (shown only if Recurring Event is checked)', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 100,
                ],
                'conditional_logic' => [
                    [
                        [
                            'field' => 'is_recurring',
                            'operator' => '==',
                            'value' => '1',
                        ]
                    ]
                ],
            ],
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'postIntro',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
            [
                'label' => __('Reserve Button', 'flynt'),
                'name' => 'buttonReserve',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ],
            // [
            //     'label' => __('Main Text', 'flynt'),
            //     'name' => 'maintextTab',
            //     'type' => 'tab',
            //     'placement' => 'top',
            //     'endpoint' => 0,
            // ],
            // [
            //     'label' => __('Main Text', 'flynt'),
            //     'name' => 'mainText',
            //     'type' => 'wysiwyg',
            //     'tabs' => 'visual',
            //     'media_upload' => 0,
            //     'delay' => 1,
            //     'wrapper' => [
            //         'width' => 100,
            //     ]
            // ],
            [
                'label' => __('Date', 'flynt'),
                'name' => 'dateTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Date (Required)',
                'instructions' => '',
                'required' => 1,
                'name' => 'end_date',
                'type' => 'date_picker',
                'display_format' => 'F j, Y',
                'return_format' => 'Ymd',
                'first_day' => 1,
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => 'Start (Optional)',
                'instructions' => '',
                'name' => 'start_date',
                'type' => 'date_picker',
                'display_format' => 'F j, Y',
                'return_format' => 'Ymd',
                'first_day' => 1,
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => 'Time',
                'name' => 'eventTime',
                'type' => 'time_picker',
                'display_format' => 'g:i a',
                'return_format' => 'g:i a',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => 'Time',
                'name' => 'eventTime',
                'type' => 'text',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'event',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'eventComponents',
        'title' => __('Event Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'eventComponents',
                'label' => __('Event Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockBannerCta\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockWysiwygTwoCol\getACFLayout(),
                    Components\ListingBlog\getACFLayout(),
                    Components\GridVideos\getACFLayout(),
                    Components\ListingEvents\getACFLayout(),
                    Components\SliderBox\getACFLayout(),
                    Components\SliderHorizontal\getACFLayout(),
                    Components\SliderImages\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'event',
                ],
            ],
        ],
    ]);
});
