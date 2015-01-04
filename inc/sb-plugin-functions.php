<?php
$wp_rewrite = new WP_Rewrite();

function sb_login_page_create_page_templates() {
    $templates = array(
        array(
            'file_name' => 'page-template-account.php',
            'title' => __('Account', 'sb-login-page')
        ),
        array(
            'file_name' => 'page-template-login.php',
            'title' => __('Login', 'sb-login-page')
        ),
        array(
            'file_name' => 'page-template-lost-password.php',
            'title' => __('Lost password', 'sb-login-page')
        ),
        array(
            'file_name' => 'page-template-register.php',
            'title' => __('Register', 'sb-login-page')
        )
    );
    $args = array(
        'plugin_path' => SB_LOGIN_PAGE_PATH,
        'folder_path' => 'page-templates',
        'templates' => $templates
    );
    SB_Core::create_page_template($args);
}

function sb_login_page_get_page_lost_password_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_lost_password']) ? $options['login_page']['page_lost_password'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_lost_password_url() {
    $page_lost_password_id = sb_login_page_get_page_lost_password_id();
    $login_url = '';
    if($page_lost_password_id > 0) {
        $login_url = get_permalink($page_lost_password_id);
    }
    if(empty($login_url)) {
        $login_url = sb_login_page_get_page_account_url();
        $login_url = add_query_arg(array('action' => 'lostpassword'), $login_url);
    }
    return $login_url;
}

function sb_login_page_get_page_register_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_register']) ? $options['login_page']['page_register'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_login_redirect_url() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['login_redirect']) ? $options['login_page']['login_redirect'] : 'current';
    $url = SB_Core::get_current_url();
    switch($value) {
        case 'home':
            $url = home_url('/');
            break;
        case 'profile':
            $url = SB_User::get_profile_url();
            break;
        case 'dashboard':
            $url = admin_url();
            break;
    }
    return $url;
}

function sb_login_page_signup_required_fields($args = array()) {
    $result = apply_filters('sb_login_page_signup_required_fields', $args);
    $defaults = array(
        'email',
        'password'
    );
    $result = wp_parse_args($result, $defaults);
    return $result;
}

function sb_login_page_get_logout_redirect_url() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logout_redirect']) ? $options['login_page']['logout_redirect'] : 'home';
    $url = home_url('/');
    switch($value) {
        case 'current':
            $url = SB_Core::get_current_url();
            break;
    }
    return $url;
}

function sb_login_page_use_sb_login() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_sb_login']) ? intval($options['login_page']['use_sb_login']) : 1;
    $account_page_url = sb_login_page_get_page_account_url();
    if(empty($account_page_url)) {
        $value = false;
    }
    return (bool)$value;
}

function sb_login_page_get_page_register_url() {
    $page_register_id = sb_login_page_get_page_register_id();
    $login_url = '';
    if($page_register_id > 0) {
        $login_url = get_permalink($page_register_id);
    }
    if(empty($login_url)) {
        $login_url = sb_login_page_get_page_account_url();
        $login_url = add_query_arg(array('action' => 'register'), $login_url);
    }
    return $login_url;
}

function sb_login_page_get_page_account_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_account']) ? $options['login_page']['page_account'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_account_url() {
    $page_account_id = sb_login_page_get_page_account_id();
    $login_url = '';
    if($page_account_id > 0) {
        $login_url = get_permalink($page_account_id);
    }
    return $login_url;
}

function sb_login_page_get_page_login_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_login']) ? $options['login_page']['page_login'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_login_url() {
    $page_login_id = sb_login_page_get_page_login_id();
    $login_url = '';
    if($page_login_id > 0) {
        $login_url = get_permalink($page_login_id);
    }
    if(empty($login_url)) {
        $login_url = sb_login_page_get_page_account_url();
        $login_url = add_query_arg(array('action' => 'login'), $login_url);
    }
    return $login_url;
}

function sb_login_page_is_login_custom_page() {
    return is_page_template('page-template-login.php');
}

function sb_login_page_is_register_custom_page() {
    return is_page_template('page-template-register.php');
}

function sb_login_page_is_lost_password_custom_page() {
    return is_page_template('page-template-lost-password.php');
}

function sb_login_page_is_account_custom_page() {
    return is_page_template('page-template-account.php');
}

function sb_login_page_create_user_role() {

}