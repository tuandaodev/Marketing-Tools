<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of redirection_ajax
 *
 * @author MT
 */
add_action( 'wp_enqueue_scripts', 'global_admin_ajax' );

function ja_ajax_search() {
    
	$results = new WP_Query( array(
		'post_type'     => array( 'post', 'coupon' ),
		'post_status'   => 'publish',
		'nopaging'      => true,
		'posts_per_page'=> 100,
		's'             => stripslashes( $_POST['search'] ),
	) );
        
	$items = array();
	if ( !empty( $results->posts ) ) {
		foreach ( $results->posts as $result ) {
                    $item['ID'] = $result->ID;
                    $item['post_title'] = $result->post_title;
                    $items[] = $item;
		}
	}
	wp_send_json_success( $items );
        
}

add_action( 'wp_ajax_search_site',        'ja_ajax_search' );
add_action( 'wp_ajax_nopriv_search_site', 'ja_ajax_search' );

function ja_ajax_search_store() {
        
        $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        $store_name = $_POST['search'];
	$results = $dbModel->getAllCouponStore($store_name);
        
	$items = array();
	if ( !empty($results) ) {
		foreach ( $results as $result ) {
                    $item['ID'] = $result['term_id'];
                    $item['post_title'] = $result['name'];
                    $items[] = $item;
		}
	}
	wp_send_json_success( $items );
        
}

add_action( 'wp_ajax_search_store',        'ja_ajax_search_store' );
add_action( 'wp_ajax_nopriv_search_store', 'ja_ajax_search_store' );

function ja_ajax_set_active_redirection() {
    
    $re_id = $_POST['id'];
    $re_active = $_POST['value'];
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $result = $dbModel->update_active_redirection($re_id, $re_active);
    
    $return['status'] = 'ok';
    $return['re_id'] = $re_id;
    $return['re_active'] = $re_active;
    $return['result'] = $result;
    
    wp_send_json_success( $return );
}

add_action( 'wp_ajax_active_redirection', 'ja_ajax_set_active_redirection' );
add_action( 'wp_ajax_nopriv_active_redirection', 'ja_ajax_set_active_redirection' );

function ja_ajax_delete_redirection() {
    
    $re_id = $_POST['id'];
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $result = $dbModel->delete_redirection($re_id);
    
    $return['data'] = $result;

    wp_send_json_success( $return );
}

add_action( 'wp_ajax_delete_redirection', 'ja_ajax_delete_redirection' );
add_action( 'wp_ajax_nopriv_delete_redirection', 'ja_ajax_delete_redirection' );

function ja_ajax_update_redirection() {
    
    if (!isset($_POST['data'])) {
        return false;
    }
    
    foreach ($_POST['data'] as $data) {
        $input[$data['name']] = $data['value'];
    }
    
    $re_id = $input['reid'];
    $destination = $input['redirect_url'];
    $des_proxy = $input['proxy_url'];
    $active = $input['status'];
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $result = $dbModel->update_redirection_part($re_id, $destination, $des_proxy, $active);
    
    $return['data'] = $result;
    $return['input'] = $input;
    
    wp_send_json_success( $return );
}

add_action( 'wp_ajax_update_htmlfile', 'ja_ajax_update_htmlfile' );
add_action( 'wp_ajax_nopriv_update_htmlfile', 'ja_ajax_update_htmlfile' );

function ja_ajax_update_htmlfile() {
    
    if (!isset($_POST['data'])) {
        return false;
    }
    
    foreach ($_POST['data'] as $data) {
        $input[$data['name']] = $data['value'];
    }
    
    $file_name = $input['file_name'];
    
    // Change name
    if ($file_name != $input['file_name_old']) {
        unlink($input['file_path']);
    }

    // process file_name
    if (strpos($file_name, '.htm') !== false) {
    } else {
        $file_name = $file_name . '.html';
    }
    $file_name = str_replace(' ', '-', $file_name);
    
    write_redirection_2html($file_name, $input['redirect_url']);
    
    $return['no'] = $input['no'];
    $return['file_name'] = $file_name;
    $return['file_path'] = get_home_path() . '/' . $file_name;
    $return['file_url'] = home_url() . '/' . $file_name;
    $return['redirect_url'] = $input['redirect_url'];
    
    wp_send_json_success( $return );
}

add_action( 'wp_ajax_update_htmlfile', 'ja_ajax_update_htmlfile' );
add_action( 'wp_ajax_nopriv_update_htmlfile', 'ja_ajax_update_htmlfile' );

function ja_ajax_delete_htmlfile() {
    
    if (!isset($_POST['data'])) {
        return false;
    }
    
    if (isset($_POST['data']['file_path']) && !empty($_POST['data']['file_path'])) {
        unlink($_POST['data']['file_path']);
        $return = true;
    } else {
        $return = false;
    }
    
    wp_send_json_success( $return );

}

add_action( 'wp_ajax_delete_htmlfile', 'ja_ajax_delete_htmlfile' );
add_action( 'wp_ajax_nopriv_delete_htmlfile', 'ja_ajax_delete_htmlfile' );