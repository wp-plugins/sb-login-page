<?php
function sb_login_page_check_core() {
    $user_deactivate_sb_core = false;
    $sb_core_activated = intval(get_option('sb_core_activated'));
    if($sb_core_activated == 0) {
        $caller = get_option('sb_core_deactivated_caller');
        if('user' == $caller) {
            $user_deactivate_sb_core = true;
        }
    }
    if(is_admin() && !$user_deactivate_sb_core) {
        return true;
    }
    $activated_plugins = get_option('active_plugins');
    $sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);
    if(!$sb_core_installed) {
        $sb_plugins = array(SB_LOGIN_PAGE_BASENAME);
        $activated_plugins = get_option('active_plugins');
        $activated_plugins = array_diff($activated_plugins, $sb_plugins);
        update_option('active_plugins', $activated_plugins);
    }
    return $sb_core_installed;
}

function sb_login_page_activation() {
    if(!sb_login_page_check_core()) {
        wp_die(sprintf(__('You must install and activate plugin %1$s first! Click here to %2$s.', 'sb-login-page'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a href="%1$s">%2$s</a>', admin_url('plugins.php'), __('go back', 'sb-login-page'))));
    }
    do_action('sb_login_page_activation');
}
register_activation_hook(SB_LOGIN_PAGE_FILE, 'sb_login_page_activation');

if(!sb_login_page_check_core()) {
    return;
}

function sb_login_page_settings_link($links) {
    if(sb_login_page_check_core()) {
        $settings_link = sprintf('<a href="admin.php?page=sb_login_page">%s</a>', __('Settings', 'sb-login-page'));
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links_' . SB_LOGIN_PAGE_BASENAME, 'sb_login_page_settings_link');

function sb_login_page_textdomain() {
    load_plugin_textdomain( 'sb-login-page', false, SB_LOGIN_PAGE_DIRNAME . '/languages/' );
}
add_action('plugins_loaded', 'sb_login_page_textdomain');

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

function sb_login_page_logo() {
    return home_url('/');
}
add_filter( 'login_headerurl', 'sb_login_page_logo');

function sb_login_page_logo_title() {
    return get_bloginfo('description');
}
add_filter('login_headertitle', 'sb_login_page_logo_title');

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-load.php';