<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('DB_REDIRECTION')) {
    define('DB_REDIRECTION', 'wp_td_redirection');
}

if (!defined('DB_VISITOR_IP')) {
    define('DB_VISITOR_IP', 'wp_td_visitor_ip');
}

if (!defined('DB_AFFILIATE_ACCOUNT')) {
    define('DB_AFFILIATE_ACCOUNT', 'wp_td_affiliate');
}

function marketing_tools_admin_menu() {
    //Maketing Tools
    add_menu_page('Marketing Tools', 'Marketing Tools', 'manage_options', 'marketing-tools', 'function_redirection_page', 'dashicons-admin-multisite', 4);
    add_submenu_page('marketing-tools', __('Redirection'), __('Redirection'), 'manage_options', 'marketing-tools');
    add_submenu_page('marketing-tools', __('Visitor IP Tracking'), __('Visitor IP Tracking'), 'manage_options', 'visitor-ip-tracking', 'function_visitor_ip_tracking_page');
    add_submenu_page('marketing-tools', __('Get IP Info'), __('Get IP Info'), 'manage_options', 'get-ip-information', 'function_get_ip_information_page');
    add_submenu_page('marketing-tools', __('HTML Generator'), __('HTML Generator'), 'manage_options', 'html-generator', 'function_html_generator_page');
    add_submenu_page('marketing-tools', __('Marketing Options'), __('Marketing Options'), 'manage_options', 'marketing-tool-options', 'function_marketing_options_page');
    add_submenu_page('marketing-tools', __('Testing'), __('Testing'), 'manage_options', 'marketing-testing', 'function_testing_page');
}

function redirection_create_db() {
    global $wpdb;
    $db_name = DB_REDIRECTION;
    $charset_collate = $wpdb->get_charset_collate();
    
    // create the ECPT metabox database table
    if($wpdb->get_var("show tables like '$db_name'") != $db_name) 
    {
            $sql = 'CREATE TABLE ' . $db_name . ' (
            `re_id` mediumint NOT NULL AUTO_INCREMENT,
            `re_source` mediumint NOT NULL,
            `re_aff` mediumint NOT NULL,
            `re_destination` text NOT NULL,
            `re_type` tinytext NOT NULL,
            `re_parent` mediumint NOT NULL,
            `re_active` tinyint NOT NULL,
            `re_count_non` int NULL,
            `re_count_redirect` int NULL,
            `re_count` int NULL,
            UNIQUE KEY re_id (re_id)
            )' . $charset_collate . ';
                
            CREATE INDEX idx_postid ON ' . $db_name . ' (re_source);';

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
    }
}

function tracking_create_db() {
    global $wpdb;
    $db_name = DB_VISITOR_IP;
    $charset_collate = $wpdb->get_charset_collate();
    
    // create the ECPT metabox database table
    if($wpdb->get_var("show tables like '$db_name'") != $db_name) 
    {
            $sql = 'CREATE TABLE ' . $db_name . ' (
            `vi_id` mediumint NOT NULL AUTO_INCREMENT,
            `vi_ip` tinytext NOT NULL,
            `vi_url` mediumint NOT NULL,
            `vi_date` datetime NOT NULL,
            `vi_notes` text NULL,
            `vi_proxy` text NULL,
            `vi_redirected` tinyint NOT NULL,
            UNIQUE KEY vi_id (vi_id)
            )' . $charset_collate . ';
                
            CREATE INDEX idx_url ON ' . $db_name . ' (vi_url);';    // RE_ID: redirection ID to link to post

            // `vi_updated` datetime NOT NULL,
//            `vi_count` mediumint NOT NULL,
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
    }
}

function affiliate_create_db() {
    global $wpdb;
    $db_name = DB_AFFILIATE_ACCOUNT;
    $charset_collate = $wpdb->get_charset_collate();
    
    // create the ECPT metabox database table
    if($wpdb->get_var("show tables like '$db_name'") != $db_name) 
    {
            $sql = 'CREATE TABLE ' . $db_name . ' (
            `aff_id` mediumint NOT NULL AUTO_INCREMENT,
            `aff_name` text NOT NULL,
            `aff_code` text NOT NULL,
            `aff_default` tinyint NOT NULL,
            UNIQUE KEY aff_id (aff_id)
            )' . $charset_collate . ';';

            // `vi_updated` datetime NOT NULL,
//            `vi_count` mediumint NOT NULL,
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
    }
}

//function marketing_tools_uninstall_activate(){
//    register_uninstall_hook( __FILE__, 'marketing_tools_uninstall' );
//}
//register_activation_hook( __FILE__, 'marketing_tools_uninstall' );
// 
//function marketing_tools_uninstall_uninstall(){
//    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//    $query = 'DROP TABLE ' . DB_REDIRECTION . ';';
//    $dbModel->query($query);
//}

