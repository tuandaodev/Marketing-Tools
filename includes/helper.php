<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function prefix_enqueue() {       
    // JS
    wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');
    
    // CSS
    wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    
    wp_enqueue_style('my-styles', WC_PLUGIN_URL . 'assets/styles.css' );
    
}

function load_assets_visitor_ip_tracking() {
    
    load_assets_common();
    
    wp_register_script('prefix_iptracking', WC_PLUGIN_URL . 'assets/visitor_ip_tracking.js');
    wp_enqueue_script('prefix_iptracking');
}

function load_assets_redirection() {
        // JS
    load_assets_common();   
    
//    wp_register_script('prefix_redirection', WC_PLUGIN_URL . 'assets/redirection.js');
//    wp_enqueue_script('prefix_redirection');
}

function load_assets_common() {
        // JS
       
    wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');
    wp_register_script('prefix_jquery', WC_PLUGIN_URL . 'assets/jquery-3.2.1.min.js');
    wp_enqueue_script('prefix_jquery'); 
    wp_register_script('prefix_datatable', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
    wp_enqueue_script('prefix_datatable');
    wp_register_script('prefix_datatable', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
    wp_enqueue_script('prefix_datatable');
    wp_register_script('prefix_toggle', WC_PLUGIN_URL . 'assets/bootstrap-toggle.js');
    wp_enqueue_script('prefix_toggle');
    
//    
    // CSS
    wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    wp_register_style('prefix_datatable', '//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css');
    wp_enqueue_style('prefix_datatable');
    wp_register_style('prefix_toggle', '//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
    wp_enqueue_style('prefix_toggle');
    
    wp_enqueue_style('my-styles', WC_PLUGIN_URL . 'assets/styles.css' );
    wp_enqueue_style('font-awesome', WC_PLUGIN_URL . 'assets/font-awesome/css/font-awesome.min.css' );
}

function global_admin_ajax() {
    wp_enqueue_style(
            'jquery-auto-complete',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css',
            array(),
            '1.0.7'
    );
    wp_enqueue_script(
            'jquery-auto-complete',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js',
            array( 'jquery' ),
            '1.0.7',
            true
    );
    
    wp_enqueue_script(
		'global',
		WC_PLUGIN_URL . 'assets/redirection.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
    
    wp_localize_script(
		'global',
		'global',
		array(
			'ajax' => admin_url( 'admin-ajax.php' ),
		)
	);
}

function getClientIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getClientAgent()
{
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
}

?>