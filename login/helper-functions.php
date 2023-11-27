<?php 



/*
* wcl_login_form
*/
function wcl_login_form() {
    $user_email    = $_POST['user_email'];
    $user_pass     = $_POST['user_pass'];
    $user_remember = $_POST['user_remember'];
    $data          = [];
    $data_auth     = array();

    if (email_exists($user_email)) {
        $user = get_user_by('email', $user_email);
        $data_auth['user_login'] = $user->user_login;
        $data_auth['user_password'] = $user_pass;

        if (wp_check_password($user_pass, $user->data->user_pass, $user->ID)) {
            $user = wp_signon($data_auth, $user_remember);
            $data['redirect'] = site_url('/') . 'blog';
        } else {
            $data['errors'] = 'This password is not valid';
        }
    } else {
        $data['errors'] = 'This email is not valid';
    }

    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_wcl_login_form', 'wcl_login_form');
add_action('wp_ajax_nopriv_wcl_login_form', 'wcl_login_form');






/*
* wcl_reset_pass
*/
function wcl_reset_pass() {
    $user_email   = $_POST['user_email'];
    $form_state   = $_POST['form_state'];
    $user_code    = $_POST['user_code'];
    $new_pass     = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $user_id      = email_exists($user_email);
    $data         = [];

    if ($form_state == 'reset-password') {
        if ($user_id != false) {
            $user = get_user_by('email', $user_email);

            $user       = new WP_User($user_id);
            $six_rand   = random_int(100000, 999999);
            $sender     = get_option('blogname');

            $to = $user->user_email;
            $subject = 'Code for reset your password';
            //  $sender_email  = get_option('blogname ');
            $message = 'Code: ' . $six_rand;

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "X-Mailer: PHP \r\n";
            $headers .= 'From: ' . $sender  . "\r\n";

            $mail = mail($to, $subject, $message, $headers);

            if ($mail) {
                set_transient($user_id  . '_reset_code', $six_rand, 60);
                setcookie("wcl_user_id", $user_id, time() + 6000, '/');
                $data['redirect'] = site_url('/login') . '/?action=code-conformation';
            } else {
                $data['errors'] = 'Password reset email not has been sent';
            }
        } else {
            $data['errors'] = 'This email is not valid';
        }
    } elseif ($form_state == 'code-conformation') {
        if (isset($_COOKIE['wcl_user_id'])) {
            $user_id = $_COOKIE['wcl_user_id'];
            $reset_code = get_transient($user_id  . '_reset_code');
            if (!empty($reset_code)) {
                if ($user_code == $reset_code) {
                    $data['redirect'] = site_url('/login') . '/?action=new-password';
                } else {
                    $data['errors'] = 'Code is not correct';
                }
            } else {
                $data['errors'] = 'Code is expired';
            }
        } else {
            $data['errors'] = 'Code is expired';
        }
    } elseif ($form_state == 'new-password') {
        if (strlen($new_pass) < 4) {
            $data['errors'] = 'Passwords password is very short, need at least 4 characters';
        } else {
            if ($new_pass == $confirm_pass) {
                $user_id = $_COOKIE['wcl_user_id'];
                wp_set_password($new_pass, $user_id);
                delete_transient($user_id  . '_reset_code');
                setcookie('wcl_user_id', null, strtotime('-1 day'), '/');
                setcookie("wcl_reset_pass", true, time() + 6000, '/');
                $data['redirect'] = site_url('/login');
            } else {
                $data['errors'] = 'Passwords do not match';
            }
        }
    }

    echo json_encode($data);
    wp_die();
}
add_action('wp_ajax_wcl_reset_pass', 'wcl_reset_pass');
add_action('wp_ajax_nopriv_wcl_reset_pass', 'wcl_reset_pass');
