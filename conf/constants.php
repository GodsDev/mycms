<?php

/**
 * Predefining constants for PHPSTAN analysis as recommanded by
 * https://phpstan.org/user-guide/discovering-symbols
 */
//define('DEBUG_VERBOSE', false);
define('DEFAULT_LANGUAGE', 'cs');
// ... use conf/config.php instead



//define('EMAIL_ADMIN' => 'rejthar@stanislavrejthar.com', // email used by Tracy\Debugger
define('FORCE_301', true); //, // enforce 301 redirect to the most friendly URL available
define('FRIENDLY_URL', false);//, // default = do not generate friendly URL
//    'GA_UID' => 'UA-39642385-1',
define('HOME_TOKEN', ''); //, // If the web runs in the root of the domain, then the default token `PATHINFO_FILENAME` is an empty string; if the web does not run in the root directory, set its parent folder name (not the whole path) here.
//    'NOTIFY_FROM_ADDRESS' => 'notifier-MYCMSPROJECTSPECIFIC@godsapps.eu', // @todo založit příslušnou schránku
//    'NOTIFY_FROM_NAME' => 'Notifikátor',
//    'PAGE_RESOURCE_VERSION' => 12,
////    'PAGINATION_LIMIT' => 10,
////    'PAGINATION_SEARCH' => 10,
////    'PAGINATION_NEWS' => 2,
define('REDIRECTOR_ENABLED', false); //, // table redirector with columns old_url, new_url, active exists
//    'SMTP_HOST' => 'localhost',
//    'SMTP_PORT' => 25,
//define('UNDER_CONSTRUCTION', false);
//    'USE_CAPTCHA' => false,
define('DIR_TEMPLATE', __DIR__ . '/../template'); // for Latte
define('L_UCFIRST', max(MB_CASE_UPPER, MB_CASE_LOWER, MB_CASE_TITLE) + 1);

//define('DIR_TEMPLATE', '');

define('MYCMS_SECRET', 'abc');
define('PAGE_RESOURCE_VERSION', 1);
define('DIR_ASSETS', 'abc');
define('TAB_PREFIX', 'abc');
define('EXPAND_INFIX', "\t"); // infix for JSON-exapandable values
//
// xTODO:
//112    Constant FORCE_301 not found.
//  112    Constant FRIENDLY_URL not found.
//  112    Constant REDIRECTOR_ENABLED not found.
//  246    Constant HOME_TOKEN not found.

//die('Cx');

