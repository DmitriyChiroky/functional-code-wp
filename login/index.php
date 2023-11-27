<?php

/**
 * Template Name: Login Page
 */


$action = '';


if (isset($_GET['logout'])) {
    $logout = $_GET['logout'];

    wp_logout();
    $redirect_url = home_url();
    wp_redirect($redirect_url);
    exit;
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_COOKIE['wcl_user_id'])) {
    $user_id = $_COOKIE['wcl_user_id'];
}

if (isset($_COOKIE['wcl_reset_pass'])) {
    $reset_pass = $_COOKIE['wcl_reset_pass'];
    setcookie('wcl_reset_pass', null, strtotime('-1 day'), '/');
    unset($_COOKIE['wcl_reset_pass']);
}

if (($action == 'code-conformation' || $action == 'new-password') && empty($user_id)) {
    wp_redirect(get_permalink());
    exit;
}

$image = get_the_post_thumbnail_url($post->ID, 'horizontal');

get_header();
?>
<div class="wrapper">
    <main class="page">
        <section class="login-section">
            <div class="login__body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-6 order-2 order-lg-1 align-self-center">
                            <div class="login__text">
                                <h1>login</h1>

                                <?php if (empty($action)) : ?>
                                    <form action="#" method="post" class="form__login" data-state="login">
                                        <div class="login-input__wrapper">
                                            <div class="form__input">
                                                <label for="loginEmail">Email</label>
                                                <input type="email" name="loginEmail" id="loginEmail" required>
                                            </div>

                                            <div class="form__input">
                                                <label for="loginPassword">Password</label>
                                                <input type="password" name="loginPassword" id="loginPassword" required>
                                            </div>
                                        </div>

                                        <div class="login-checkbox__wrapper">
                                            <div class="custom-checkbox login-custom-checkbox">
                                                <input type="checkbox" name="checkBox" id="checkBox">
                                                <label for="checkBox">Remember me</label>
                                            </div>

                                            <a href="<?php echo get_permalink() . '?action=reset-password'; ?>" class="login__link">Forgot password</a>
                                        </div>

                                        <div class="form__button">
                                            <button type="submit" class="btn-blue all-btn btn-login">Login</button>
                                        </div>

                                        <div class="data-form-error">
                                            <?php if (!empty($reset_pass)) : ?>
                                                Password changed successfully
                                            <?php endif; ?>
                                        </div>

                                        <p class="login__paragraph">Don’t have an account? <a class="login__link" href="/contact-us">Get in touch</a></p>
                                    </form>
                                <?php elseif ($action == 'reset-password') : ?>
                                    <form action="#" method="post" class="form__login" data-state="reset-password">
                                        <div class="data-form-title">
                                            Reset your password
                                            <br>
                                            Enter your email address and well send you a confirmation on to make sure its really you.
                                        </div>

                                        <div class="login-input__wrapper">
                                            <div class="form__input">
                                                <label for="loginEmail">Email</label>
                                                <input type="email" name="loginEmail" id="loginEmail" required>
                                            </div>
                                        </div>

                                        <div class="form__button">
                                            <button type="submit" class="btn-blue all-btn btn-login">Reset</button>
                                        </div>

                                        <div class="data-form-error">
                                        </div>

                                        <p class="login__paragraph">Don’t have an account? <a class="login__link" href="/contact-us">Get in touch</a></p>
                                    </form>
                                <?php elseif ($action == 'code-conformation') : ?>
                                    <form action="#" method="post" class="form__login" data-state="code-conformation">
                                        <div class="data-form-title">
                                            Enter a code conformation
                                            <br>
                                            Enter a 6-digit code which we sended on your email to secure your account
                                        </div>

                                        <div class="login-input__wrapper">
                                            <div class="form__input">
                                                <label for="code">Code</label>
                                                <input type="number" name="code" id="code" required>
                                            </div>
                                        </div>

                                        <div class="form__button">
                                            <button type="submit" class="btn-blue all-btn btn-login">Confirm</button>
                                        </div>

                                        <div class="data-form-error"></div>

                                        <p class="login__paragraph">Don’t have an account? <a class="login__link" href="/contact-us">Get in touch</a></p>
                                    </form>
                                <?php elseif ($action == 'new-password') : ?>
                                    <form action="#" method="post" class="form__login" data-state="new-password">
                                        <div class="data-form-title">
                                            Create a new password for your <?php echo get_option('blogname'); ?> account. Do not share this password with anyone
                                        </div>

                                        <div class="login-input__wrapper">
                                            <div class="form__input">
                                                <label for="new_password">Create a new password</label>
                                                <input type="password" name="new_password" id="new_password" required>
                                            </div>

                                            <div class="form__input">
                                                <label for="confirm_password">Confirm password</label>
                                                <input type="password" name="confirm_password" id="confirm_password" required>
                                            </div>
                                        </div>

                                        <div class="form__button">
                                            <button type="submit" class="btn-blue all-btn btn-login">Submit</button>
                                        </div>

                                        <div class="data-form-error"></div>

                                        <p class="login__paragraph">Don’t have an account? <a class="login__link" href="/contact-us">Get in touch</a></p>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 order-1 order-lg-2">
                            <?php if (!empty($image)) : ?>
                                <?php
                                $args_xml = array(
                                    "ssl" => array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                    ),
                                );
                                ?>
                                <div class="hero-section__image">
                                    <?php echo file_get_contents($image, false, stream_context_create($args_xml)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<?php
get_footer();
