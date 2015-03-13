<?php
/*
Plugin Name: SB Login Page
Plugin URI: http://hocwp.net/
Description: SB Login Page is a plugin that allows user to custom WordPress login page.
Author: SB Team
Version: 1.0.8
Author URI: http://hocwp.net/
Text Domain: sb-login-page
Domain Path: /languages/
*/

define('SB_LOGIN_PAGE_USE_CORE_VERSION', '1.6.0');

define('SB_LOGIN_PAGE_FILE', __FILE__);

define('SB_LOGIN_PAGE_PATH', untrailingslashit(plugin_dir_path(SB_LOGIN_PAGE_FILE)));

define('SB_LOGIN_PAGE_URL', plugins_url('', SB_LOGIN_PAGE_FILE));

define('SB_LOGIN_PAGE_INC_PATH', SB_LOGIN_PAGE_PATH . '/inc');

define('SB_LOGIN_PAGE_BASENAME', plugin_basename(SB_LOGIN_PAGE_FILE));

define('SB_LOGIN_PAGE_DIRNAME', dirname(SB_LOGIN_PAGE_BASENAME));

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-load.php';