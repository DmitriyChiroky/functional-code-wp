<?php

/**
 * Get all post IDs
 * @return array All post IDs
 */
function bob_get_all_place_post_ids() {
    return get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1, // Get all posts
        'fields' => 'ids'
    ));
}


/**
 * Make Request call to get GOOGLE rating data on single post
 */
function bob_auto_get_post_google_rating_info($post_id) {
    
    $place_id = get_field('place_info_booking_place_google_id', $post_id);

    // Return in $place_id is empty and prevent run the request in vain
    if (empty($place_id)) {
        return "";
    }

    $base_url = 'https://maps.googleapis.com/maps/api/place/details/json';
    $api_key = get_field('bob_google_api_key', 'option');

    // Define Google API params
    $params = array(
        'place_id' => $place_id,
        'key' => $api_key,
        'fields' => array(
            'rating',
            'user_ratings_total',
        )
    );

    // Format param 'fields' in separate comma list
    $params['fields'] = implode(',', $params['fields']);

    // Add params to base URL
    $full_get_url = add_query_arg($params, $base_url);

    // Make request
    $response = wp_remote_get($full_get_url);

    if (is_array($response) && !is_wp_error($response)) {
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Format 'result' to get clean data
        if ($data && isset($data['result'])) {

            // Access the 'rating' and 'user_ratings_total' fields in data
            $result = $data['result'];
            
            // Return them as an array
            return array(
                'rating' => $result['rating'],
                'user_ratings_total' => $result['user_ratings_total'],
            );
        } else {
            return "";
        }
    } else {
        $error_message = is_wp_error($response) ? $response->get_error_message() : 'Failed Place data request';
    }
}


/**
 * Make Request call to get TRIP ADVISOR rating data on single post
 */
function bob_auto_get_post_trip_advisor_rating_info($post_id) {

    $place_id = get_field('place_info_booking_place_trip_advisor_id', $post_id);

    // Return in $place_id is empty and prevent run the request in vain
    if (empty($place_id)) {
        return "";
    }

    $base_url = "https://api.content.tripadvisor.com/api/v1/location/{$place_id}/details";
    $api_key = get_field('bob_trip_advisor_api_key', 'option');

    // Define Trip Advisor REQUIRED API params (We are getting all data, define values to work in JS file).
    $params = array(
        'key' => $api_key,
        'language' => 'en',
        'currency' => 'USD'
    );

    // Add params to base URL
    $full_get_url = add_query_arg($params, $base_url);

    // Make request
    $response = wp_remote_get($full_get_url);

    if (is_array($response) && !is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Format 'result' to get clean data
        if ($data && isset($data['rating']) && isset($data['num_reviews'])) {

            // Return them as an array
            return array(
                'rating' => $data['rating'],
                'num_reviews' => $data['num_reviews'],
            );
        } else {
            return "";
        }
    } else {
        $error_message = is_wp_error($response) ? $response->get_error_message() : 'Failed Place data request';
    }
}


/**
 * Get INTERNAL rating data on single post
 */
function bob_auto_get_post_internal_rating_info($post_id) {
    // Return them as an array
    return array(
        'rating' => get_post_meta($post_id, "rmp_avg_rating", true),
        'num_reviews' => get_post_meta($post_id, "rmp_vote_count", true)
    );
}


/**
 * Auto update ALL PROVIDERS rating info and then make AGGREGATED RATING update in single post.
 */
function bob_auto_update_posts_rating_info() {

    // Obtain all POST IDs of places
    $post_ids = bob_get_all_place_post_ids();

    foreach ($post_ids as $post_id) {

        // Fetch updated rating info from providers
        $google_data = bob_auto_get_post_google_rating_info($post_id);
        $trip_advisor_data = bob_auto_get_post_trip_advisor_rating_info($post_id);


        // Update GOOGLE rating post meta
        if (!empty($google_data)) {
            update_field('place_info_booking_place_google_rating', $google_data['rating'], $post_id);
            update_field('place_info_booking_place_google_rating_totals', $google_data['user_ratings_total'], $post_id);
        }

        // Update TRIP ADVISOR rating post meta
        if (!empty($trip_advisor_data)) {
            update_field('place_info_booking_place_trip_advisor_rating', $trip_advisor_data['rating'], $post_id);
            update_field('place_info_booking_place_trip_advisor_rating_totals', $trip_advisor_data['num_reviews'], $post_id);
        }


        // Run post update to trigger generation of AGGREGATED RATING value
        wp_update_post(array('ID' => $post_id));
    }
}


/**
 * Provide way to run 'bob_auto_update_posts_rating_info' with wget in Cpanel with security token
 */
function bob_activate_wget_auto_update_posts() {

    // Token provided in Admin options to prevent public wget run
    $cron_token = get_field('bob_cpanel_cron_token', 'option');

    if (
        isset($_GET['doing_wp_cron']) &&
        isset($_GET['action']) &&
        $_GET['action'] === 'bob_auto_update_posts_rating_info' &&
        isset($_GET['token']) &&
        $_GET['token'] === $cron_token
        ) {

        // Run function to update posts ratings
        bob_auto_update_posts_rating_info();

        exit;
    }
}

add_action('init', 'bob_activate_wget_auto_update_posts');
