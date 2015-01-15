<?php
function sb_login_page_style_and_script() {
    wp_register_style('sb-login-style', SB_LOGIN_PAGE_URL . '/css/sb-login-style.css');
    wp_enqueue_style('sb-login-style');
    wp_register_script('sb-login', SB_LOGIN_PAGE_URL . '/js/sb-login-script.js', array('jquery'), false, true);
    wp_enqueue_script('sb-login');
    $logo_url = SB_Option::get_login_logo_url();
    if(!empty($logo_url)) {
        echo '<style>';
        echo 'body.login div#login h1 a{background-image:url("'.$logo_url.'");}';
        echo '</style>';
    } else {
        printf('<style>body.login div#login h1 a{display:none;}</style>');
    }
}
add_action('login_enqueue_scripts', 'sb_login_page_style_and_script');

function sb_login_page_custom_style_and_script() {
    wp_enqueue_style('sb-login-page-style', SB_LOGIN_PAGE_URL . '/css/sb-login-page-style.css');
    wp_enqueue_script('sb-login-page', SB_LOGIN_PAGE_URL . '/js/sb-login-page-script.js', array('jquery'), false, true);
    if(sb_login_page_is_lost_password_custom_page() || sb_login_page_is_account_custom_page()) {
        wp_enqueue_script('password-strength-meter');
        wp_localize_script('password-strength-meter', 'pwsL10n', array(
            'empty' => __('Độ mạnh mật khẩu', 'sb-login-page'),
            'short' => __('Rất yếu', 'sb-login-page'),
            'bad' => __('Yếu', 'sb-login-page'),
            'good' => _x('Trung bình', 'sb-login-page'),
            'strong' => __('Mạnh', 'sb-login-page'),
            'mismatch' => __('Không khớp', 'sb-login-page')
        ));
    }
}
add_action('wp_enqueue_scripts', 'sb_login_page_custom_style_and_script');

function sb_login_page_logo() {
    return home_url('/');
}
add_filter( 'login_headerurl', 'sb_login_page_logo');

function sb_login_page_logo_title() {
    return get_bloginfo('description');
}
add_filter('login_headertitle', 'sb_login_page_logo_title');

function sb_login_page_init() {
    if(SB_Core::is_login_page()) {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        if('logout' == $action) {
            return;
        }
        $login_url = sb_login_page_get_page_account_url();
        if(!empty($action)) {
            $login_url = add_query_arg(array('action' => $action), $login_url);
        }
        wp_redirect($login_url);
        exit();
    }
}
if(sb_login_page_use_sb_login()) add_action('init', 'sb_login_page_init');

function sb_login_page_custom_init() {
    if(SB_User::is_logged_in()) {
        if(sb_login_page_is_login_custom_page() || sb_login_page_is_lost_password_custom_page() || sb_login_page_is_register_custom_page()) {
            wp_redirect(sb_login_page_get_page_account_url());
            exit;
        }
    } else {
        if(!SB_User::can_register() && sb_login_page_is_register_custom_page()) {
            wp_redirect(home_url('/'));
            exit;
        }
        $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
        if(sb_login_page_is_account_custom_page()) {
            if('register' == $action && !SB_User::can_register()) {
                wp_redirect(home_url('/'));
                exit;
            }
            if('login' != $action && 'register' != $action && 'lostpassword' != $action && 'verify' != $action) {
                $account_url = sb_login_page_get_page_account_url();
                $account_url = add_query_arg(array('action' => 'login'), $account_url);
                wp_safe_redirect($account_url);
                exit();
            }
            if('verify' == $action) {
                $user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
                if($user_id > 0) {
                    $user = SB_User::get_by('id', $user_id);
                    if(SB_User::is($user)) {
                        $verify_email_session = SB_User::get_verify_email_session($user);
                        if(1 == $verify_email_session) {
                            SB_User::set_verify_email_cookie($user);
                        }
                    }
                }
            }
        } elseif(sb_login_page_is_lost_password_custom_page()) {
            $step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : '';
            $code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
            $id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
            if('verify' == $step && !empty($code) && $id > 0) {
                $user = SB_User::get_by('id', $id);
                if(!is_wp_error($user) && is_a($user, 'WP_User') && SB_User::check_activation_code($user, $code)) {
                    $lost_password_url = SB_User::get_lost_password_verify_url($code);
                    $lost_password_url = add_query_arg(array('step' => 'reset', 'user_id' => $id));
                    wp_safe_redirect($lost_password_url);
                    exit;
                }
            } elseif('reset' == $step) {
                $user = SB_User::get_by('id', $id);
                if(empty($code) || $id < 1 || 'lostpassword' != $action || !SB_User::is($user) || !SB_User::check_activation_code($user, $code)) {
                    wp_redirect(home_url('/'));
                    exit;
                }
            }
        }
    }
}
add_action('sb_login_page_init', 'sb_login_page_custom_init');

function sb_login_page_body_class($classes) {
    if(is_page() && is_page_template()) {
        global $post;
        $login_page_id = sb_login_page_get_page_login_id();
        $account_page_id = sb_login_page_get_page_account_id();
        $lost_password_page_id = sb_login_page_get_page_lost_password_id();
        $register_page_id = sb_login_page_get_page_register_id();
        if($post->ID == $login_page_id) {
            $classes[] = 'sb-login-page';
        } elseif($post->ID == $account_page_id) {
            $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
            if($action == 'verify') {
                $classes[] = 'sb-verify-account';
            }
            $classes[] = 'sb-account-page';
        } elseif($post->ID == $lost_password_page_id) {
            $classes[] = 'sb-lost-password-page';
        } elseif($post->ID == $register_page_id) {
            $classes[] = 'sb-register-page';
        }
        if(SB_User::is_logged_in()) {
            $classes[] = 'sb-user';
        } else {
            $classes[] = 'sb-guest';
        }
    }
    return $classes;
}
add_filter('body_class', 'sb_login_page_body_class');

function sb_login_page_plugin_loaded() {
    sb_login_page_create_page_templates();
}
add_action('plugins_loaded', 'sb_login_page_plugin_loaded');

function sb_login_page_custom_logout_redirect($logout_url, $redirect) {
    $redirect = SB_User::get_logout_redirect();
    $logout_url = add_query_arg(array('redirect_to' => $redirect), $logout_url);
    return $logout_url;
}
if(sb_login_page_use_sb_login()) add_filter('logout_url', 'sb_login_page_custom_logout_redirect', 10, 2);

function sb_login_page_custom_login_redirect($login_url, $redirect) {
    $redirect = SB_User::get_login_redirect();
    $login_url = add_query_arg(array('redirect_to' => $redirect), $login_url);
    return $login_url;
}
if(sb_login_page_use_sb_login()) add_filter('login_url', 'sb_login_page_custom_login_redirect', 10, 2);

function sb_login_page_user_contact_info($fields) {
    $fields['phone'] = __('Phone', 'sb-login-page');
    $fields['address'] = __('Address', 'sb-login-page');
    $fields['identity'] = __('Identity', 'sb-login-page');
    return $fields;
}
add_filter('user_contactmethods', 'sb_login_page_user_contact_info');

function sb_login_page_activation_callback() {
    sb_login_page_create_user_role();
}
register_activation_hook(SB_LOGIN_PAGE_FILE, 'sb_login_page_activation_callback');

function sb_login_page_editable_roles($roles){
    if(!current_user_can('administrator')) {
        unset($roles['administrator']);
    }
    return $roles;
}
add_filter('editable_roles', 'sb_login_page_editable_roles');

function sb_login_page_user_profile_extra_field($user) {
    $user_id = $user->ID;
    $gender = get_the_author_meta('gender', $user_id);
    $user_data = SB_User::get_data($user_id);
    ?>
    <h3><?php _e('Extra information', 'sb-login-page'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="gender"><?php _e('Gender', 'sb-login-page'); ?></label></th>
            <td>
                <?php
                $args = array(
                    'name' => 'gender',
                    'value' => $gender
                );
                SB_Field::select_gender($args);
                ?>
            </td>
        </tr>
        <tr>
            <th><label for="birthday"><?php _e('Birthday', 'sb-login-page'); ?></label></th>
            <td>
                <?php
                $birthday = SB_User::get_birthday_timestamp($user_id);
                $args = array(
                    'value' => $birthday
                );
                SB_Field::select_birthday($args);
                ?>
            </td>
        </tr>
        <?php if(SB_User::is_admin()) : ?>
            <tr>
                <th><label for="user_nicename"><?php _e('User nice name', 'sb-login-page'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo $user_data->user_nicename; ?>" id="user_nicename" name="user_nicename">
                </td>
            </tr>
            <tr>
                <th><label for="activation_code"><?php _e('Activation code', 'sb-login-page'); ?></label></th>
                <td>
                    <?php $code = SB_User::get_activation_code($user); ?>
                    <input type="text" class="regular-text" value="<?php echo $code; ?>" id="activation_code" name="activation_code" readonly>
                </td>
            </tr>
        <?php endif; ?>
    </table>
<?php
}
add_action('show_user_profile', 'sb_login_page_user_profile_extra_field');
add_action('edit_user_profile', 'sb_login_page_user_profile_extra_field');

function sb_login_page_save_profile($user_id) {
    update_user_meta($user_id, 'gender', isset($_POST['gender']) ? $_POST['gender'] : 0);
    $birth_day = isset($_POST['user_birth_day']) ? $_POST['user_birth_day'] : date('d');
    $birth_month = isset($_POST['user_birth_month']) ? $_POST['user_birth_month'] : date('m');
    $birth_year = isset($_POST['user_birth_year']) ? $_POST['user_birth_year'] : date('Y');
    $birthday = $birth_year . '-' . $birth_month . '-' . $birth_day;
    $birthday = strtotime($birthday);
    update_user_meta($user_id, 'birthday', $birthday);
    $user_nicename = isset($_POST['user_nicename']) ? $_POST['user_nicename'] : '';
    if(!empty($user_nicename)) {
        $user_data = array(
            'user_nicename' => $user_nicename
        );
        SB_User::update($user_id, $user_data);
    }
}
add_action('personal_options_update', 'sb_login_page_save_profile');
add_action('edit_user_profile_update', 'sb_login_page_save_profile');