<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'lernenMeta',
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
            [
                'label' => __('Date', 'flynt'),
                'name' => 'dateTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Date',
                'instructions' => '',
                'required' => 0,
                'name' => 'end_date',
                'type' => 'date_picker',
                'display_format' => 'F j, Y',
                'return_format' => 'Ymd',
                'first_day' => 1,
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => 'Start',
                'instructions' => '',
                'name' => 'start_date',
                'type' => 'date_picker',
                'display_format' => 'F j, Y',
                'return_format' => 'Ymd',
                'first_day' => 1,
            ],
            [
                'label' => 'Time',
                'name' => 'eventTime',
                'type' => 'time_picker',
                'display_format' => 'g:i a',
                'return_format' => 'g:i a',
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => 'Time',
                'name' => 'eventTime',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50,
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'lernen',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'lernenComponents',
        'title' => __('Lernen Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'lernenComponents',
                'label' => __('Lernen Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAnchor\getACFLayout(),
                    Components\BlockBannerCta\getACFLayout(),
                    Components\BlockCollapse\getACFLayout(),
                    Components\BlockImage\getACFLayout(),
                    Components\BlockImageText\getACFLayout(),
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockIframe\getACFLayout(),
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
                    'value' => 'lernen',
                ],
            ],
        ],
    ]);
});
