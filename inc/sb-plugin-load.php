<?php
require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-install.php';

if(!sb_login_page_check_core() || !sb_login_page_is_core_valid()) {
    return;
}

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-constant.php';

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-functions.php';

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-admin.php';

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-hook.php';

require SB_LOGIN_PAGE_INC_PATH . '/sb-plugin-ajax.php';