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

function sb_login_page_setting_field() {
    SB_Admin_Custom::add_section('sb_login_page_section', __('SB Login Page options page', 'sb-login-page'), 'sb_login_page');
    SB_Admin_Custom::add_setting_field('sb_login_page_logo', 'Logo', 'sb_login_page_section', 'sb_login_page_logo_callback', 'sb_login_page');
}
add_action('sb_admin_init', 'sb_login_page_setting_field');

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
    $data['login_page']['logo'] = SB_Core::sanitize(isset($input['login_page']['logo']) ? $input['login_page']['logo'] : '', 'url');
    return $data;
}
add_filter('sb_options_sanitize', 'sb_login_page_sanitize');