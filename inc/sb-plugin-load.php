<?php
require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-install.php';
if(!sb_login_page_check_core()) {
    return;
}
require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-functions.php';
require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-admin.php';
require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-hook.php';
require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-ajax.php';