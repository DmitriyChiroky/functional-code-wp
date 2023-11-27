<?php 

/*
* member_add_picture
*/
function member_add_picture() {
    $user    = wp_get_current_user();
    $post_id = $_POST['post_id'];
    $data    = [];

    if (!empty($_FILES['picture'])) {
        $picture_id = media_handle_upload('picture', $post_id);
        set_post_thumbnail($post_id, $picture_id);
        $data['submit'] = 'Інформація успішно оновлена';
    }

    echo json_encode($data);
    wp_die();
}
add_action('wp_ajax_member_add_picture', 'member_add_picture');
add_action('wp_ajax_nopriv_member_add_picture', 'member_add_picture');



/*
* member_remove_picture
*/
function member_remove_picture() {
    $post_id = $_POST['post_id'];
    $data    = [];

    delete_post_thumbnail($post_id);

    echo json_encode($data);
    wp_die();
}
add_action('wp_ajax_member_remove_picture', 'member_remove_picture');
add_action('wp_ajax_nopriv_member_remove_picture', 'member_remove_picture');

