<?php

/**
 * Plugin Name: Marketing Tools
 * Plugin URI: http://minhtuanit.me
 * Description: A small tools to help you manage your WordPress - WooCommerce
 * Version: 2.00
 * Author: Tuan Dao
 * Author URI: http://minhtuanit.me
 * License: GPL2
 * Created On: 12-06-2017
 * Updated On: 12-09-2017
 */
// Define WC_PLUGIN_DIR.
if (!defined('WC_PLUGIN_DIR')) {
    define('WC_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// Define WC_PLUGIN_FILE.
if (!defined('WC_PLUGIN_URL')) {
    define('WC_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('DB_REDIRECTION')) {
    define('DB_REDIRECTION', 'wp_td_redirection');
}

if (!defined('DB_VISITOR_IP')) {
    define('DB_VISITOR_IP', 'wp_td_visitor_ip');
}

if (!defined('API_IP_PROVIDER_MAX')) {
    define('API_IP_PROVIDER_MAX', 7);
}

require_once('autoload.php');
require_once('includes/helper.php');

add_action('plugins_loaded', 'marketing_tools_plugin_init');

register_activation_hook(__FILE__, 'tracking_create_db');
register_activation_hook(__FILE__, 'redirection_create_db');

function marketing_tools_plugin_init() {
    add_action('admin_menu', 'marketing_tools_admin_menu');
    add_action('login_init', 'send_frame_options_header', 10, 0);
    add_action('admin_init', 'send_frame_options_header', 10, 0);
}





function function_testing_page() {
    
    $ip = '85.17.24.66';
    $test = getIpSafe($ip);
    
    echo "<pre>";
    print_r($test);
    echo "<pre>";
    exit;
}

function function_html_generator_page() {
    
    
}


?>