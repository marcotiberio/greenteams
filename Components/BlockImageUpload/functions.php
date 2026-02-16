<?php

namespace Flynt\Components\BlockImageUpload;

use Flynt\FieldVariables;
use Flynt\Options;

function getACFLayout()
{
    return [
        'name' => 'BlockImageUpload',
        'label' => __('Block: Image Upload', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
                'wrapper' =>  [
                    'width' => 100,
                ],
            ],
            [
                'label' => __('Sharepic', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Explanation Text', 'flynt'),
                'name' => 'explanationText',
                'type' => 'text',
                'wrapper' =>  [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Color Selection Text', 'flynt'),
                'name' => 'selectionText',
                'type' => 'text',
                'wrapper' =>  [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Upload Button Text', 'flynt'),
                'name' => 'uploadButtonText',
                'type' => 'text',
                'default_value' => 'Upload',
                'wrapper' => [
                    'width' => 25
                ],
            ],
            [
                'label' => __('Download Button Text', 'flynt'),
                'name' => 'downloadButtonText',
                'type' => 'text',
                'default_value' => 'Herunterladen',
                'wrapper' => [
                    'width' => 25
                ],
            ],
            [
                'label' => __('Example Images', 'flynt'),
                'name' => 'exampleImagesTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Example Image 1', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG, GIF.', 'flynt'),
                'name' => 'exampleImage1',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,gif',
                'wrapper' =>  [
                    'width' => 33,
                ],
            ],
            [
                'label' => __('Example Image 2', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG, GIF.', 'flynt'),
                'name' => 'exampleImage2',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,gif',
                'wrapper' =>  [
                    'width' => 33,
                ],
            ],
            [
                'label' => __('Example Image 3', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG, GIF.', 'flynt'),
                'name' => 'exampleImage3',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,gif',
                'wrapper' =>  [
                    'width' => 33,
                ],
            ],
        ]
    ];
}
