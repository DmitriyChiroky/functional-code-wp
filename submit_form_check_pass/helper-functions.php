<?php 

/*
* enter_password_check
*/
function enter_password_check() {
    $password_user = $_POST['password'];
    $tree_id       = $_POST['tree_id'];

    $password_tree = get_post_meta($tree_id, 'wcl_password_tree', true);

    if (!empty($password_user)) {
        if ($password_user == $password_tree) {
            $data['submit'] = 'Пароль вірний';

            $expiration =  time() + 60 * 60 * 24; // 24 hours
            setcookie('wcl_allow_tree_' . $tree_id, $password_tree, $expiration, COOKIEPATH, COOKIE_DOMAIN);
        } else {
            $data['error'] = 'Неправильний пароль';
        }
    } else {
        $data['error'] = 'Будь ласка введіть пароль';
    }

    echo json_encode($data);
    wp_die();
}
add_action('wp_ajax_enter_password_check', 'enter_password_check');
add_action('wp_ajax_nopriv_enter_password_check', 'enter_password_check');
