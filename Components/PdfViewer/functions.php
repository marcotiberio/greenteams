<?php

namespace Flynt\Components\PdfViewer;

use Flynt\Utils\Options;

Options::addTranslatable('PdfViewer', [
    [
        'label' => __('PDF Viewer', 'flynt'),
        'name' => 'pdfViewerTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    ],
    [
        'label' => __('Button labels', 'flynt'),
        'instructions' => __('Labels for the buttons shown in the in-page PDF overlay.', 'flynt'),
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('Open in new tab', 'flynt'),
                'name' => 'openInNewTab',
                'type' => 'text',
                'default_value' => __('Open in new tab', 'flynt'),
                'required' => 1,
                'wrapper' => ['width' => '50'],
            ],
            [
                'label' => __('Download', 'flynt'),
                'name' => 'download',
                'type' => 'text',
                'default_value' => __('Download', 'flynt'),
                'required' => 1,
                'wrapper' => ['width' => '50'],
            ],
            [
                'label' => __('Close (accessibility label)', 'flynt'),
                'name' => 'close',
                'type' => 'text',
                'default_value' => __('Close', 'flynt'),
                'required' => 1,
                'wrapper' => ['width' => '50'],
            ],
        ],
    ],
]);
