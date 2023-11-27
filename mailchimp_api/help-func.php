<?php 
/*
* wcl_subscribe
*/
function wcl_subscribe() {
    $email     = $_POST["email"];
    $mailchimp = get_field('mailchimp', 'option');
    $list_id   = $mailchimp['list_id'];
    $api_key   = $mailchimp['api_key'];
    $data      = [];

    $data_center = substr($api_key, strpos($api_key, '-') + 1);
    $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members';
    $json = json_encode([
        'email_address' => $email,
        'status'        => 'subscribed',
    ]);

    try {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (200 == $status_code) {
            $data['code'] = 'success';
            $data['message'] = "You have successfully subscribed";
        } else {
            $val = json_decode($result);
            $data['code'] = 'error';
            $data['message'] = str_replace('Use PUT to insert or update list members.', '', $val->detail);
        }
    } catch (Exception $e) {
        $data[0] = $e->getMessage();
    }

    if (empty($data)) {
        $data['code'] = 'error';
        $data['message'] = 'An error has occurred';
    }

    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_wcl_subscribe', 'wcl_subscribe');
add_action('wp_ajax_nopriv_wcl_subscribe', 'wcl_subscribe');

