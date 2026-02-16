<?php

namespace Flynt\WpformsSeatable;

use Flynt\Utils\Options;

/**
 * WPForms to Seatable Integration
 * 
 * This integration sends WPForms submissions to Seatable using their API.
 * 
 * Setup Instructions:
 * 1. Get your Seatable API token:
 *    - For Team Workspace: Go to Seatable home → Hover over your base → Three-dot menu → Advanced → API Tokens → Create API Token (with write permissions)
 *    - For Personal Account: https://cloud.seatable.io/accounts/profile/
 * 2. Get your Seatable base URL (e.g., https://cloud.seatable.io)
 * 3. Get your workspace ID, dtable name, and table name from your Seatable URL
 *    Example URL: workspace/78590/dtable/CRM/?tid=VZ4p&vid=0000
 *    - Workspace ID: 78590
 *    - Dtable Name: CRM
 *    - Table Name: (the actual table name in Seatable)
 * 4. Configure the field mapping in the ACF options (Global Options > Default > Seatable Integration)
 * 
 * Field Mapping:
 * - Map WPForms field IDs to Seatable column names
 * - Format: "field_id:column_name" (e.g., "1:Name", "2:Email")
 */

/**
 * Hook into WPForms submission process
 */
add_action('wpforms_process_complete', __NAMESPACE__ . '\sendToSeatable', 10, 4);

/**
 * Send form submission to Seatable
 * 
 * @param array $fields Form fields data
 * @param array $entry Form entry data
 * @param array $form_data Form configuration
 * @param int $entry_id Entry ID
 */
function sendToSeatable($fields, $entry, $form_data, $entry_id)
{
    // Check if integration is enabled
    $enabled = Options::getGlobal('Seatable', 'enabled');
    if (!$enabled) {
        return;
    }

    // Get configuration
    $apiToken = Options::getGlobal('Seatable', 'apiToken');
    $baseUrl = Options::getGlobal('Seatable', 'baseUrl');
    $workspaceId = Options::getGlobal('Seatable', 'workspaceId');
    $dtableName = Options::getGlobal('Seatable', 'dtableName');
    $tableName = Options::getGlobal('Seatable', 'tableName');
    $fieldMapping = Options::getGlobal('Seatable', 'fieldMapping');

    // Validate required settings
    if (empty($apiToken) || empty($baseUrl) || empty($workspaceId) || empty($dtableName) || empty($tableName)) {
        error_log('Seatable Integration: Missing required configuration');
        return;
    }

    // Check if this form should be sent to Seatable
    $formId = isset($form_data['id']) ? $form_data['id'] : 0;
    $targetFormIds = Options::getGlobal('Seatable', 'formIds');
    
    if (!empty($targetFormIds) && !in_array($formId, explode(',', $targetFormIds))) {
        return; // This form is not configured to send to Seatable
    }

    // Prepare data for Seatable
    $seatableData = prepareSeatableData($fields, $fieldMapping);

    // Send to Seatable
    $result = sendToSeatableApi($baseUrl, $apiToken, $workspaceId, $dtableName, $tableName, $seatableData);

    // Log result
    if (is_wp_error($result)) {
        error_log('Seatable Integration Error: ' . $result->get_error_message());
    } else {
        error_log('Seatable Integration: Successfully sent entry #' . $entry_id);
    }
}

/**
 * Prepare data for Seatable based on field mapping
 * 
 * @param array $fields WPForms fields
 * @param string $fieldMapping Field mapping configuration
 * @return array Prepared data for Seatable
 */
function prepareSeatableData($fields, $fieldMapping)
{
    $data = [];

    if (empty($fieldMapping)) {
        // If no mapping, send all fields with their IDs as keys
        foreach ($fields as $fieldId => $field) {
            $value = isset($field['value']) ? $field['value'] : '';
            $data["Field_{$fieldId}"] = $value;
        }
        return $data;
    }

    // Parse field mapping (format: "field_id:column_name" or "field_id:column_name,field_id2:column_name2")
    $mappings = explode(',', $fieldMapping);
    
    foreach ($mappings as $mapping) {
        $mapping = trim($mapping);
        if (empty($mapping)) {
            continue;
        }

        $parts = explode(':', $mapping);
        if (count($parts) !== 2) {
            continue;
        }

        $fieldId = trim($parts[0]);
        $columnName = trim($parts[1]);

        if (isset($fields[$fieldId])) {
            $value = isset($fields[$fieldId]['value']) ? $fields[$fieldId]['value'] : '';
            $data[$columnName] = $value;
        }
    }

    // Add timestamp if not mapped
    if (!isset($data['Created'])) {
        $data['Created'] = current_time('mysql');
    }

    return $data;
}

/**
 * Send data to Seatable API
 * 
 * @param string $baseUrl Seatable base URL
 * @param string $apiToken API token
 * @param string $workspaceId Workspace ID
 * @param string $dtableName Dtable name (e.g., "CRM")
 * @param string $tableName Table name
 * @param array $data Data to insert
 * @return WP_Error|array Response from API
 */
function sendToSeatableApi($baseUrl, $apiToken, $workspaceId, $dtableName, $tableName, $data)
{
    // Clean up base URL (remove trailing slash)
    $baseUrl = rtrim($baseUrl, '/');

    // Seatable API endpoint for inserting rows
    // Format: /api/v2.1/workspace/{workspace_id}/dtable/{dtable_name}/rows/
    // Alternative: /dtable-server/api/v1/workspace/{workspace_id}/dtable/{dtable_name}/rows/
    $apiUrl = "{$baseUrl}/api/v2.1/workspace/{$workspaceId}/dtable/{$dtableName}/rows/";

    // Prepare request headers
    $headers = [
        'Authorization' => 'Token ' . $apiToken,
        'Content-Type' => 'application/json',
    ];

    // Prepare request body - Seatable API expects table_name and row data
    $body = [
        'table_name' => $tableName,
        'row' => $data,
    ];

    // Make API request
    $response = wp_remote_post($apiUrl, [
        'headers' => $headers,
        'body' => json_encode($body),
        'timeout' => 30,
    ]);

    // Check for errors
    if (is_wp_error($response)) {
        return $response;
    }

    $responseCode = wp_remote_retrieve_response_code($response);
    $responseBody = wp_remote_retrieve_body($response);

    // If first endpoint fails, try alternative endpoint format
    if ($responseCode !== 200 && $responseCode !== 201) {
        $altApiUrl = "{$baseUrl}/dtable-server/api/v1/workspace/{$workspaceId}/dtable/{$dtableName}/rows/";
        $altResponse = wp_remote_post($altApiUrl, [
            'headers' => $headers,
            'body' => json_encode($body),
            'timeout' => 30,
        ]);

        if (!is_wp_error($altResponse)) {
            $altResponseCode = wp_remote_retrieve_response_code($altResponse);
            $altResponseBody = wp_remote_retrieve_body($altResponse);

            if ($altResponseCode === 200 || $altResponseCode === 201) {
                return json_decode($altResponseBody, true);
            }
        }

        // Both endpoints failed, return error
        $errorMessage = sprintf(
            'Seatable API returned status code %d. Response: %s',
            $responseCode,
            $responseBody
        );
        return new \WP_Error('seatable_api_error', $errorMessage);
    }

    return json_decode($responseBody, true);
}

/**
 * Add ACF options for Seatable configuration
 * Register after ACF is initialized to ensure options page is available
 */
add_action('acf/init', function () {
    if (!function_exists('acf_add_options_page')) {
        return; // ACF not available
    }
    
    Options::addGlobal('Seatable', [
    [
        'name' => 'seatableTab',
        'label' => __('Seatable Integration', 'flynt'),
        'type' => 'tab'
    ],
    [
        'name' => 'enabled',
        'label' => __('Enable Seatable Integration', 'flynt'),
        'type' => 'true_false',
        'default_value' => 0,
        'ui' => 1,
        'instructions' => __('Enable sending WPForms submissions to Seatable', 'flynt')
    ],
    [
        'name' => 'apiToken',
        'label' => __('Seatable API Token', 'flynt'),
        'type' => 'text',
        'instructions' => __('Get your API token: 1) Go to Seatable home page, 2) Hover over your base (dtable), 3) Click three-dot menu → Advanced → API Tokens, 4) Create API Token with write permissions. For personal accounts: https://cloud.seatable.io/accounts/profile/', 'flynt'),
        'required' => 0,
    ],
    [
        'name' => 'baseUrl',
        'label' => __('Seatable Base URL', 'flynt'),
        'type' => 'text',
        'default_value' => 'https://cloud.seatable.io',
        'instructions' => __('Your Seatable instance URL (e.g., https://cloud.seatable.io)', 'flynt'),
        'required' => 0,
    ],
    [
        'name' => 'workspaceId',
        'label' => __('Workspace ID', 'flynt'),
        'type' => 'text',
        'instructions' => __('The workspace ID from your Seatable URL (e.g., 78590 from workspace/78590/dtable/CRM)', 'flynt'),
        'required' => 0,
    ],
    [
        'name' => 'dtableName',
        'label' => __('Dtable Name', 'flynt'),
        'type' => 'text',
        'instructions' => __('The dtable name from your Seatable URL (e.g., "CRM" from workspace/78590/dtable/CRM)', 'flynt'),
        'required' => 0,
    ],
    [
        'name' => 'tableName',
        'label' => __('Table Name', 'flynt'),
        'type' => 'text',
        'instructions' => __('The name of the table in Seatable where data should be inserted', 'flynt'),
        'required' => 0,
    ],
    [
        'name' => 'formIds',
        'label' => __('Form IDs', 'flynt'),
        'type' => 'text',
        'instructions' => __('Comma-separated list of WPForms IDs to send to Seatable. Leave empty to send all forms.', 'flynt'),
        'required' => 0,
    ],
    [
        'name' => 'fieldMapping',
        'label' => __('Field Mapping', 'flynt'),
        'type' => 'textarea',
        'instructions' => __('Map WPForms field IDs to Seatable column names. Format: "field_id:column_name,field_id2:column_name2". Example: "1:Name,2:Email,3:Message". Leave empty to send all fields with default names.', 'flynt'),
        'rows' => 5,
        'required' => 0,
    ],
    [
        'name' => 'testConnection',
        'label' => __('Test Connection', 'flynt'),
        'type' => 'message',
        'message' => '<button type="button" id="seatable-test-connection" class="button button-secondary">Test Seatable API Connection</button><div id="seatable-test-result" style="margin-top: 10px;"></div>',
        'instructions' => __('Click the button above to test if your Seatable API connection is working correctly.', 'flynt'),
    ],
    ]);
}, 10);

/**
 * Register AJAX handler for testing Seatable connection
 */
add_action('wp_ajax_seatable_test_connection', __NAMESPACE__ . '\testSeatableConnection');

/**
 * Test Seatable API connection
 */
function testSeatableConnection()
{
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
        return;
    }

    // Verify nonce
    if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce($_POST['_ajax_nonce'], 'seatable_test_connection')) {
        wp_send_json_error(['message' => 'Security check failed']);
        return;
    }

    // Get configuration
    $apiToken = Options::getGlobal('Seatable', 'apiToken');
    $baseUrl = Options::getGlobal('Seatable', 'baseUrl');
    $workspaceId = Options::getGlobal('Seatable', 'workspaceId');
    $dtableName = Options::getGlobal('Seatable', 'dtableName');
    $tableName = Options::getGlobal('Seatable', 'tableName');

    // Validate required settings
    $missing = [];
    if (empty($apiToken)) {
        $missing[] = 'API Token';
    }
    if (empty($baseUrl)) {
        $missing[] = 'Base URL';
    }
    if (empty($workspaceId)) {
        $missing[] = 'Workspace ID';
    }
    if (empty($dtableName)) {
        $missing[] = 'Dtable Name';
    }
    if (empty($tableName)) {
        $missing[] = 'Table Name';
    }

    if (!empty($missing)) {
        wp_send_json_error([
            'message' => 'Missing required configuration: ' . implode(', ', $missing),
            'details' => 'Please fill in all required fields before testing the connection.'
        ]);
        return;
    }

    // Prepare test data
    $testData = [
        'Test_Connection' => 'WPForms Integration Test',
        'Test_Date' => current_time('mysql'),
        'Test_Status' => 'Testing API Connection'
    ];

    // Try to send test data to Seatable
    $result = sendToSeatableApi($baseUrl, $apiToken, $workspaceId, $dtableName, $tableName, $testData);

    if (is_wp_error($result)) {
        wp_send_json_error([
            'message' => 'Connection Failed',
            'details' => $result->get_error_message()
        ]);
        return;
    }

    // Success!
    wp_send_json_success([
        'message' => 'Connection Successful!',
        'details' => 'The API connection is working correctly. Test data was sent to Seatable.',
        'response' => $result
    ]);
}

/**
 * Enqueue admin script for test connection button
 */
add_action('acf/input/admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'globaloptions') === false) {
        return;
    }
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#seatable-test-connection').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $result = $('#seatable-test-result');
            
            $button.prop('disabled', true).text('Testing...');
            $result.html('<div class="notice notice-info inline"><p>Testing connection...</p></div>');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'seatable_test_connection',
                    _ajax_nonce: '<?php echo wp_create_nonce('seatable_test_connection'); ?>'
                },
                success: function(response) {
                    $button.prop('disabled', false).text('Test Seatable API Connection');
                    
                    if (response.success) {
                        $result.html(
                            '<div class="notice notice-success inline"><p><strong>' + 
                            response.data.message + '</strong><br>' + 
                            response.data.details + '</p></div>'
                        );
                    } else {
                        $result.html(
                            '<div class="notice notice-error inline"><p><strong>' + 
                            response.data.message + '</strong><br>' + 
                            (response.data.details || '') + '</p></div>'
                        );
                    }
                },
                error: function() {
                    $button.prop('disabled', false).text('Test Seatable API Connection');
                    $result.html(
                        '<div class="notice notice-error inline"><p><strong>Error:</strong> Failed to test connection. Please try again.</p></div>'
                    );
                }
            });
        });
    });
    </script>
    <?php
});

