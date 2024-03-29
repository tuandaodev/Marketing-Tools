<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function prefix_enqueue() {       
    // JS
    wp_register_script('prefix_bootstrap', WC_PLUGIN_URL . 'assets/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');
    
    // CSS
    wp_register_style('prefix_bootstrap', WC_PLUGIN_URL . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    
    wp_enqueue_style('my-styles', WC_PLUGIN_URL . 'assets/styles.css' );
    
}

function load_assets_visitor_ip_tracking() {
    
    load_assets_common();
    
    load_assets_datetime_picker();
    
//    wp_register_script('prefix_iptracking', WC_PLUGIN_URL . 'assets/visitor_ip_tracking.js');
//    wp_enqueue_script('prefix_iptracking');
    
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
		WC_PLUGIN_URL . 'assets/visitor_ip_tracking.js',
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

function load_assets_datetime_picker() {
    
    wp_register_script('prefix_moment', WC_PLUGIN_URL . 'assets/moment.min.js');
    wp_enqueue_script('prefix_moment');
    
    wp_register_script('prefix_datetime', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js');
    wp_enqueue_script('prefix_datetime');
    
    wp_enqueue_style('prefix_datetime', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css' );
}

function load_assets_get_ip_info() {
    
    load_assets_common();
    
    wp_register_script('prefix_getipinfo', WC_PLUGIN_URL . 'assets/get_ip_info.js');
    wp_enqueue_script('prefix_getipinfo');
    
   
}

function load_assets_redirection() {
        // JS
    load_assets_common();   
    
//    wp_register_script('prefix_redirection', WC_PLUGIN_URL . 'assets/redirection.js');
//    wp_enqueue_script('prefix_redirection');
}

function load_assets_html_generator() {
    
    load_assets_common();
    
    wp_enqueue_script(
		'global',
		WC_PLUGIN_URL . 'assets/html_generator.js',
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
    
    
//    wp_register_script('prefix_htmlgenerator', WC_PLUGIN_URL . 'assets/html_generator.js');
//    wp_enqueue_script('prefix_htmlgenerator');
}

function load_assets_tool_options() {
    
    load_assets_common();
    
    wp_enqueue_script(
		'global',
		WC_PLUGIN_URL . 'assets/tool-options.js',
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

function load_assets_common() {
    
    // JS
    wp_register_script('prefix_bootstrap', WC_PLUGIN_URL . 'assets/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');
    wp_register_script('prefix_jquery', WC_PLUGIN_URL . 'assets/jquery-3.2.1.min.js');
    wp_enqueue_script('prefix_jquery'); 
    wp_register_script('prefix_datatable', WC_PLUGIN_URL . 'assets/js/jquery.dataTables.min.js');
    wp_enqueue_script('prefix_datatable');
    wp_register_script('prefix_toggle', WC_PLUGIN_URL . 'assets/bootstrap-toggle.js');
    wp_enqueue_script('prefix_toggle');
    
//    
    // CSS
    wp_register_style('prefix_bootstrap', WC_PLUGIN_URL . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
    wp_register_style('prefix_datatable', WC_PLUGIN_URL . 'assets/css/jquery.dataTables.min.css');
    wp_enqueue_style('prefix_datatable');
    wp_register_style('prefix_toggle', WC_PLUGIN_URL . 'assets/css/bootstrap-toggle.min.css');
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

function get_random($max, $different, $min = 1) {
    do {
      $num = rand($min, $max);
    } while ($num == $different);
    return $num;
}

function getIpSafe($ip) {
    
    $url = "http://v2.api.iphub.info/ip/{$ip}";
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Key: MTIzODpZQ3ExblV2ZFRjQzZ0QkN6NUtQUlRvUmJvM3p1dE1qSg=='
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
    $return = json_decode($response, true);
    
    if (!is_null($return) && isset($return['ip'])) {
        return $return;
    } else {
        return $return;
    }
}

function get_time2query($POST) {
    $return['query_timetype'] = $POST['query_timetype'];
    switch ($POST['query_timetype']) {
        case 'time_custom':
            if (isset($POST['time_start']) && !empty($POST['time_start'])) {
                $return['time_start'] = date('Y-m-d H:i:s', strtotime($POST['time_start']));
            }
            if (isset($POST['time_end']) && !empty($POST['time_end'])) {
                $return['time_end'] = date('Y-m-d H:i:s', strtotime($POST['time_end']));
            }
            break;
    }
    return $return;
}
?>