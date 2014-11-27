<?php
function sb_login_page_check_core() {
    $activated_plugins = get_option('active_plugins');
    $sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);
    return $sb_core_installed;
}

function sb_login_page_activation() {
    if(!current_user_can('activate_plugins')) {
        return;
    }
    do_action('sb_login_page_activation');
}
register_activation_hook(SB_LOGIN_PAGE_FILE, 'sb_login_page_activation');

function sb_login_page_check_admin_notices() {
    if(!sb_login_page_check_core()) {
        unset($_GET['activate']);
        printf('<div class="error"><p><strong>' . __('Error', 'sb-login-page') . ':</strong> ' . __('The plugin with name %1$s has been deactivated because of missing %2$s plugin', 'sb-login-page') . '.</p></div>', '<strong>SB Banner Widget</strong>', sprintf('<a target="_blank" href="%s" style="text-decoration: none">SB Core</a>', 'https://wordpress.org/plugins/sb-core/'));
        deactivate_plugins(SB_LOGIN_PAGE_BASENAME);
    }
}
if(!empty($GLOBALS['pagenow']) && 'plugins.php' === $GLOBALS['pagenow']) {
    add_action('admin_notices', 'sb_login_page_check_admin_notices', 0);
}

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