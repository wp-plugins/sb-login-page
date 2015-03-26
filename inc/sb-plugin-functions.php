<?php
function sb_login_page_page_template_arg() {
    $templates = array(
        array(
            'file_name' => SB_LOGIN_PAGE_ACCOUNT_TEMPLATE,
            'title' => __('Account', 'sb-login-page')
        ),
        array(
            'file_name' => SB_LOGIN_PAGE_LOGIN_TEMPLATE,
            'title' => __('Login', 'sb-login-page')
        ),
        array(
            'file_name' => SB_LOGIN_PAGE_LOST_PASSWORD_TEMPLATE,
            'title' => __('Lost password', 'sb-login-page')
        ),
        array(
            'file_name' => SB_LOGIN_PAGE_REGISTER_TEMPLATE,
            'title' => __('Register', 'sb-login-page')
        )
    );
    $args = array(
        'plugin_path' => SB_LOGIN_PAGE_PATH,
        'folder_path' => 'page-templates',
        'templates' => $templates
    );
    return $args;
}

function sb_login_page_create_page_templates() {
    $args = sb_login_page_page_template_arg();
    SB_Core::create_page_template($args);
}

function sb_login_page_delete_page_templates() {
    $args = sb_login_page_page_template_arg();
    SB_Core::delete_page_template($args);
}

function sb_login_page_get_user_page_url($args = array()) {
    $page_id = isset($args['page_id']) ? absint($args['page_id']) : 0;
    $page_template = isset($args['page_template']) ? $args['page_template'] : '';
    $action = isset($args['action']) ? $args['action'] : '';
    $url = '';
    if($page_id > 0) {
        $template_name = get_post_meta($page_id, '_wp_page_template', true);
        if('page-templates/' . $page_template == $template_name) {
            $url = get_permalink($page_id);
        }
    }
    if(empty($url)) {
        $url = sb_login_page_get_page_account_url();
        if(!empty($url)) {
            $url = add_query_arg(array('action' => $action), $url);
        }
    }
    return $url;
}

function sb_login_page_get_page_lost_password_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_lost_password']) ? $options['login_page']['page_lost_password'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_lost_password_url() {
    $args = array(
        'page_id' => sb_login_page_get_page_lost_password_id(),
        'page_template' => SB_LOGIN_PAGE_LOST_PASSWORD_TEMPLATE,
        'action' => 'lostpassword'
    );
    return sb_login_page_get_user_page_url($args);
}

function sb_login_page_get_page_register_id() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_register']) ? $options['login_page']['page_register'] : 0;
    $value = intval($value);
    return $value;
}

function sb_login_page_get_page_register_url() {
    $args = array(
        'page_id' => sb_login_page_get_page_register_id(),
        'page_template' => SB_LOGIN_PAGE_REGISTER_TEMPLATE,
        'action' => 'register'
    );
    return sb_login_page_get_user_page_url($args);
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
        $template_name = get_post_meta($page_account_id, '_wp_page_template', true);
        if('page-templates/' . SB_LOGIN_PAGE_ACCOUNT_TEMPLATE == $template_name) {
            $login_url = get_permalink($page_account_id);
        }
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
    $args = array(
        'page_id' => sb_login_page_get_page_login_id(),
        'page_template' => SB_LOGIN_PAGE_LOGIN_TEMPLATE,
        'action' => 'login'
    );
    return sb_login_page_get_user_page_url($args);
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

function sb_login_page_signup_captcha() {
    return apply_filters('sb_login_page_signup_captcha', sb_login_page_use_captcha());
}

function sb_login_page_use_captcha() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_captcha']) ? intval($options['login_page']['use_captcha']) : 1;
    return (bool)$value;
}

function sb_login_page_use_sb_login() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_sb_login']) ? intval($options['login_page']['use_sb_login']) : 1;
    return (bool)$value;
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

function sb_login_page_can_deactivate_account() {
    return apply_filters('sb_login_page_can_deactivate_account', true);
}

function sb_login_page_testing() {
    return apply_filters('sb_login_page_testing', false);
}

function sb_login_page_user_signup($args = array()) {
    $email = isset($args['email']) ? $args['email'] : '';
    $password = isset($args['password']) ? $args['password'] : '';
    $user_args = array(
        'username' => $email,
        'email' => $email,
        'password' => $password
    );
    $user_id = SB_User::add($user_args);
    if($user_id > 0) {
        $user = SB_User::get_by('id', $user_id);
        $check_activation = isset($args['check_activation']) ? $args['check_activation'] : true;
        $name = isset($args['name']) ? $args['name'] : '';
        $name_arr = explode(' ', $name);
        $first_name = array_pop($name_arr);
        $last_name = trim(implode(' ', $name_arr));
        $nice_name = SB_PHP::remove_vietnamese($name);
        $nice_name = str_replace(' ', '-', $nice_name);
        $user_data = array(
            'user_nicename' => $nice_name,
            'display_name' => $name,
            'first_name' => $first_name,
            'last_name' => $last_name
        );
        SB_User::update($user, $user_data);
        $phone = isset($args['phone']) ? $args['phone'] : '';
        SB_User::update_meta($user_id, 'phone', $phone);
        $address = isset($args['address']) ? $args['address'] : '';
        SB_User::update_meta($user_id, 'address', $address);
        if($check_activation) {
            SB_User::update_status($user, 6);
            SB_User::generate_activation_code($user);
            SB_User::send_signup_verify_email($user);
        }
        $force_login = isset($args['force_login']) ? (bool)$args['force_login'] : false;
        if($force_login) {
            SB_User::login($email, $password, true);
        }
        return true;
    }
    return false;
}

function sb_login_page_signup_ajax($args = array()) {
    $result = array();
    $email = isset($args['email']) ? trim($args['email']) : '';
    $phone = isset($args['phone']) ? trim($args['phone']) : '';
    $name = isset($args['name']) ? trim($args['name']) : '';
    $password = isset($args['password']) ? trim($args['password']) : '';
    $address = isset($args['address']) ? trim($args['address']) : '';
    $result['valid'] = 1;
    $result['success_field'] = '<input type="hidden" value="1" class="success-field" name="singup-success">';
    $use_captcha = isset($args['use_captcha']) ? (bool)$args['use_captcha'] : true;
    $insert = isset($args['insert']) ? (bool)$args['insert'] : false;
    if(!SB_PHP::is_email_valid($email)) {
        $result['valid'] = 0;
        $result['message'] = __('Địa chỉ email của bạn không đúng', 'sb-login-page');
    } elseif(email_exists($email) || username_exists($email)) {
        $result['valid'] = 0;
        $result['message'] = __('Địa chỉ email của bạn đã tồn tại', 'sb-login-page');
    } elseif(sb_login_page_signup_captcha() && $use_captcha) {
        $captcha = isset($args['captcha']) ? $args['captcha'] : '';
        if(sb_login_page_use_captcha() && !SB_Core::check_captcha($captcha)) {
            $result['valid'] = 0;
            $result['message'] = __('Mã bảo mật bạn nhập không đúng', 'sb-login-page');
        }
    } elseif($insert) {
        $success = sb_login_page_user_signup($args);
        if(!$success) {
            $result['valid'] = 0;
            $result['message'] = __('Đã có lỗi xảy ra, xin vui lòng thử lại', 'sb-login-page');
        }
    }
    return $result;
}

function sb_login_page_login_ajax($args = array()) {
    $count_logged_in_fail = isset($args['count_logged_in_fail']) ? absint($args['count_logged_in_fail']) : 0;
    $cookie = isset($args['cookie']) ?  absint($args['cookie']) : 0;
    $result = array();
    if($count_logged_in_fail < 3) {
        $login_email = isset($args['email']) ? trim($args['email']) : '';
        $login_password = isset($args['password']) ? trim($args['password']) : '';
        $remember = isset($args['remember']) ? (bool)$args['remember'] : true;
        $check_activation = isset($args['check_activation']) ? (bool)$args['check_activation'] : true;
        $member = SB_User::get_by_email_or_login($login_email);
        if(SB_User::is($member) && SB_User::is_awaiting_activation($member->ID) && $check_activation) {
            $result['logged_in'] = false;
            $result['user_id'] = $member->ID;
            $result['message'] = __('Tài khoản của bạn chưa được kích hoạt', 'sb-login-page');
            $result['redirect'] = SB_User::get_account_verify_url($member->ID);
        } else {
            $user = SB_User::login($login_email, $login_password, $remember);
            if(is_wp_error($user) || !SB_User::is($user)) {
                $result['logged_in'] = false;
                $result['user_id'] = -1;
                $result['message'] = __('Tài khoản hoặc mật khẩu không đúng, xin vui lòng thử lại.', 'sb-login-page');
                SB_User::update_logged_in_fail_session();
            } else {
                $result['logged_in'] = true;
                $result['user_id'] = $user->ID;
            }
        }
    } else {
        $result['logged_in'] = false;
        $result['user_id'] = -1;
        if(1 != $cookie) {
            SB_User::update_logged_in_fail_session();
            SB_User::update_logged_in_fail_cookie();
            $result['block_login'] = true;
        }
    }
    return $result;
}