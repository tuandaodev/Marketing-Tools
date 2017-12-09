<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function marketing_tools_admin_menu() {
    //Maketing Tools
    add_menu_page('Marketing Tools', 'Marketing Tools', 'manage_options', 'marketing-tools', 'function_redirection_page', 'dashicons-admin-multisite', 4);
    add_submenu_page('marketing-tools', __('Redirection'), __('Redirection'), 'manage_options', 'marketing-tools');
}