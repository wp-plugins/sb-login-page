<?php
function sb_login_page_menu() {
    SB_Admin_Custom::add_submenu_page('SB Login Page', 'sb_login_page', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_login_page_menu');

function sb_login_page_tab($tabs) {
    $tabs['sb_login_page'] = array('title' => 'SB Login Page', 'section_id' => 'sb_login_page_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_login_page_tab');

function sb_login_page_add_admin_setting_field($id, $title, $callback) {
    SB_Admin_Custom::add_setting_field($id, $title, 'sb_login_page_section', $callback, 'sb_login_page');
}

function sb_login_page_setting_field() {
    SB_Admin_Custom::add_section('sb_login_page_section', __('SB Login Page options page', 'sb-login-page'), 'sb_login_page');
    sb_login_page_add_admin_setting_field('sb_login_page_user_can_register', __('Allow user register', 'sb-login-page'), 'sb_login_page_user_can_register_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_use_sb_login', __('Use SB Login', 'sb-login-page'), 'sb_login_page_use_sb_login_callback');
    SB_Admin_Custom::add_setting_field('sb_login_page_logo', 'Logo', 'sb_login_page_section', 'sb_login_page_logo_callback', 'sb_login_page');
    if(sb_login_page_use_sb_login()) {
        sb_login_page_add_admin_setting_field('sb_login_page_page_account', __('Account page', 'sb-login-page'), 'sb_login_page_page_account_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_login', __('Login page', 'sb-login-page'), 'sb_login_page_page_login_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_lost_password', __('Lost password page', 'sb-login-page'), 'sb_login_page_page_lost_password_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_register', __('Register page', 'sb-login-page'), 'sb_login_page_page_register_callback');
    }
    sb_login_page_add_admin_setting_field('sb_login_page_login_redirect', __('Login redirect', 'sb-login-page'), 'sb_login_page_login_redirect_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_logout_redirect', __('Logout redirect', 'sb-login-page'), 'sb_login_page_logout_redirect_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_social_login', __('Social login', 'sb-login-page'), 'sb_login_page_social_login_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_use_captcha', __('Use captcha', 'sb-login-page'), 'sb_login_page_use_captcha_callback');
}
add_action('sb_admin_init', 'sb_login_page_setting_field');

function sb_login_page_use_captcha_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_captcha']) ? intval($options['login_page']['use_captcha']) : 1;
    $args = array(
        'id' => 'sb_login_page_use_captcha',
        'name' => 'sb_options[login_page][use_captcha]',
        'value' => $value,
        'description' => __('Turn on or turn off the function for user must pass captcha when register.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_social_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['social_login']) ? intval($options['login_page']['social_login']) : 1;
    $args = array(
        'id' => 'sb_login_page_social_login',
        'name' => 'sb_options[login_page][social_login]',
        'value' => $value,
        'description' => __('Turn on or turn off the function for user connect account with social network.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_use_sb_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_sb_login']) ? intval($options['login_page']['use_sb_login']) : 1;
    $args = array(
        'id' => 'sb_login_page_use_sb_login',
        'name' => 'sb_options[login_page][use_sb_login]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to force using SB Login system.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_logout_redirect_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logout_redirect']) ? $options['login_page']['logout_redirect'] : 'home';
    $options = array(
        'home' => __('Home', 'sb-login-page'),
        'current' => __('Current', 'sb-login-page')
    );
    $args = array(
        'id' => 'sb_login_page_logout_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'logout_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => __('Choose the redirect page when user logout.', 'sb-login-page')
    );
    SB_Field::select($args);
}

function sb_login_page_login_redirect_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['login_redirect']) ? $options['login_page']['login_redirect'] : 'current';
    $options = array(
        'home' => __('Home', 'sb-login-page'),
        'profile' => __('Profile', 'sb-login-page'),
        'dashboard' => __('Dashboard', 'sb-login-page'),
        'current' => __('Current', 'sb-login-page')
    );
    $args = array(
        'id' => 'sb_login_page_login_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'login_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => __('Choose the redirect page when user login.', 'sb-login-page')
    );
    SB_Field::select($args);
}

function sb_login_page_user_can_register_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['users_can_register']) ? intval($options['login_page']['users_can_register']) : 0;
    $users_can_register = intval(get_option('users_can_register'));
    if($value != $users_can_register) {
        $value = $users_can_register;
    }
    $args = array(
        'id' => 'sb_login_page_user_can_register',
        'name' => 'sb_options[login_page][users_can_register]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to allow user can register.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_page_register_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_register']) ? $options['login_page']['page_register'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_register',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_register')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_lost_password_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_lost_password']) ? $options['login_page']['page_lost_password'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_lost_password',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_lost_password')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_account_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_account']) ? $options['login_page']['page_account'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_account',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_account')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_login']) ? $options['login_page']['page_login'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_login',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_login')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_logo_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logo']) ? $options['login_page']['logo'] : '';
    $args = array(
        'id' => 'sb_login_page_logo',
        'name' => 'sb_options[login_page][logo]',
        'value' => $value,
        'description' => __('You can enter url or upload new logo image file.', 'sb-login-page')
    );
    SB_Field::media_image($args);
}

function sb_login_page_sanitize($input) {
    $data = $input;
    $users_can_register = isset($input['login_page']['users_can_register']) ? (bool)$input['login_page']['users_can_register'] : false;
    if($users_can_register) {
        update_option('users_can_register', 1);
    } else {
        update_option('users_can_register', 0);
    }
    $data['login_page']['logo'] = SB_Core::sanitize(isset($input['login_page']['logo']) ? $input['login_page']['logo'] : '', 'url');
    return $data;
}
add_filter('sb_options_sanitize', 'sb_login_page_sanitize');