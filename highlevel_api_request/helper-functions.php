<?php


/* 
wcl_highlevel_api_request
 */
function wcl_highlevel_api_request($new_data) {

    $response = wcl_upsert_contact_highlevel($new_data);

    $response = json_decode($response, true);

    if (isset($response['error']) && $response['error'] == 'Unauthorized') {
        wcl_highlevel_update_token();

        $response = wcl_upsert_contact_highlevel($new_data);
    }

    //return $response;
}




/* 
wcl_upsert_contact_highlevel
 */
function wcl_highlevel_update_token() {
    $accessToken   = get_option('wcl_highlevel_access_token');
    $refreshToken  = get_option('wcl_highlevel_refresh_token');
    $client_id     = '';
    $client_secret = '';

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://services.leadconnectorhq.com/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "client_id=" . $client_id . "&client_secret=" . $client_secret . "&grant_type=refresh_token&code=&refresh_token=" . $refreshToken . "&user_type=Location&redirect_uri=",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // if ($err) {
    //     echo "cURL Error #:" . $err;
    // } else {
    //     echo $response;
    // }

    $response = json_decode($response, true);

    $accessToken = $response['access_token'];
    $refresh_token = $response['refresh_token'];

    update_option('wcl_highlevel_access_token', $accessToken);
    update_option('wcl_highlevel_refresh_token', $refresh_token);
}




/* 
wcl_upsert_contact_highlevel
 */
function wcl_upsert_contact_highlevel($data) {
    $accessToken = get_option('wcl_highlevel_access_token');

    if (empty($accessToken)) {
        $access_token = '';
        $refresh_token = '';

        update_option('wcl_highlevel_access_token', $access_token);
        update_option('wcl_highlevel_refresh_token', $refresh_token);
        
        $accessToken = get_option('wcl_highlevel_access_token');
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://services.leadconnectorhq.com/contacts/upsert",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'name' => $data['name'],
            'email' => $data['email'],
            'locationId' => '1sAAjTK5tMmvmsq64VL4',
            'customFields' => [
                [
                    'id' => '',
                    'key' => 'contact.manufacturer',
                    'field_value' => $data['manufacturer'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.model',
                    'field_value' => $data['model'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.length',
                    'field_value' => $data['length'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.working_end',
                    'field_value' => $data['working_end'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.quantity',
                    'field_value' => $data['quantity'],
                ]
            ],
            'tags' => [
                'by api',
            ],
            'source' => 'api',
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: Bearer " . $accessToken,
            "Content-Type: application/json",
            "Version: 2021-07-28"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    return $response;
}



/* 
wcl_custom_contact_form_submission_handler
 */
function wcl_custom_contact_form_submission_handler($contact_form) {
    // Get the form ID
    $form_id = $contact_form->id();

    // You can perform actions based on the form ID or other conditions
    if ($form_id == 6 || $form_id == 1165) { // Replace 123 with your actual form ID
        // Access form submission data
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $posted_data = $submission->get_posted_data();

            $new_data = [
                'name'         => $posted_data['your-name'],
                'email'        => $posted_data['your-email'],
                'manufacturer' => $posted_data['manufacturer'][0],
                'model'        => $posted_data['model'][0],
                'length'       => $posted_data['length-inches'][0],
                'working_end'  => $posted_data['workind-end'][0],
                'quantity'     => $posted_data['quantity'],
            ];


            wcl_highlevel_api_request($new_data);
        }
    }
}
add_action('wpcf7_mail_sent', 'wcl_custom_contact_form_submission_handler');





/* 
upsert_contact_highlevel_test
 */
function upsert_contact_highlevel_test() {
    $accessToken = get_option('wcl_highlevel_access_token');

    if (empty($accessToken)) {
        $access_token = '';
        $refresh_token = '';

        update_option('wcl_highlevel_access_token', $access_token);
        update_option('wcl_highlevel_refresh_token', $refresh_token);
        
        $accessToken = get_option('wcl_highlevel_access_token');
    }

    $data = [
        'name'         => 'Name',
        'email'        => 'test@gmail.com',
        'manufacturer' => 'manufacturer',
        'model'        => 'model',
        'length'       => 'length',
        'working_end'  => 'working_end',
        'quantity'     => '1',
    ];


    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://services.leadconnectorhq.com/contacts/upsert",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'name' => $data['name'],
            'email' => $data['email'],
            'locationId' => '1sAAjTK5tMmvmsq64VL4',
            'customFields' => [
                [
                    'id' => '',
                    'key' => 'contact.manufacturer',
                    'field_value' => $data['manufacturer'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.model',
                    'field_value' => $data['model'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.length',
                    'field_value' => $data['length'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.working_end',
                    'field_value' => $data['working_end'],
                ],
                [
                    'id' => '',
                    'key' => 'contact.quantity',
                    'field_value' => $data['quantity'],
                ]
            ],
            'source' => 'public api',
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: Bearer " . $accessToken,
            "Content-Type: application/json",
            "Version: 2021-07-28"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // if ($err) {
    //     echo "cURL Error #:" . $err;
    // } else {
    //     echo $response;
    // }

    return $response;
}
