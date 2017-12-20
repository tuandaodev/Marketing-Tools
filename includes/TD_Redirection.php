<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Redirection
 *
 * @author dmtuan
 */
if (!defined('REDIRECTION_SOURCE')) {
    define('REDIRECTION_SOURCE', 'google.com');
}

//if (!defined('REDIRECTION_SOURCE')) {
//    define('REDIRECTION_SOURCE', 'shoppingstore02.ga');
//}

if (!class_exists('TD_Redirection')) {

    class TD_Redirection {

        function __construct() {
            add_action('redirection_check', array(&$this, 'redirection_check_redirection'));
        }

        function redirection_check_redirection() {

            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], REDIRECTION_SOURCE) != false) {
                $this->redirection(TRUE);
            } else {
                $this->redirection(FALSE);
            }
        }
        
        public function redirection($check_referer) {
            $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $post_id = get_the_ID();
            
            if ($post_id != 999999) {
                $exists = $dbModel->check_exists_redirection($post_id, 'coupon');
            } else {
                $queried_object = get_queried_object();
                if (isset($queried_object->taxonomy) && $queried_object->taxonomy == 'coupon_store') {
                    $exists = $dbModel->check_exists_redirection($queried_object->term_id, 'store');
                    }
            }
            
            if ($exists != false) {
                $ip = getClientIP();
                $agent = getClientAgent();
                
                // 0: Non Direct
                // 1: Success REDIRECTED
                // 2: Block by API
                // 3: Manual Block
                
                if ($exists['re_active'] == 0 || $check_referer) {
                    $ip_safe = getIpSafe($ip);
                    if (isset($ip_safe['ip'])) {
                        $proxy_log = 'Block: ' . $ip_safe['block'] . ', Country: ' . $ip_safe['countryName'] . ', ISP: ' . $ip_safe['isp'];
                    } else {
                        $proxy_log = $ip_safe;
                    }

                    if (isset($ip_safe['block']) && $ip_safe['block'] == 1) {
                        $dbModel->log_client_IP($exists['re_id'], $ip, $agent, 2, $proxy_log);
                        $this->redirection_by_url(urldecode($exists['re_destination']));
                    } else {
                        
                        
                        $ip_block = $dbModel->check_in_blacklist($ip);
                        
                        if ($ip_block == true) {    // Client in BACK LIST
                            
                            $dbModel->log_client_IP($exists['re_id'], $ip, $agent, 3, $proxy_log);
                            $this->redirection_by_url(urldecode($exists['re_destination']));
                            
                        } else {
                            
                            $dbModel->log_client_IP($exists['re_id'], $ip, $agent, 1, $proxy_log);
                            
                            $aff = $dbModel->getAffiliateAccountByID($exists['re_aff']);
                            $aff_link = $this->build_aff_url($aff['aff_code'], $exists['re_destination']);
                        
                            $this->redirection_by_url($aff_link);
                            
                        }
                    }
                    
                } else {
                    $dbModel->log_client_IP($exists['re_id'], $ip, $log, 0);
                }
            } 
        }


        public function redirection_by_url($redirect_url = '') {
            if (!empty($redirect_url)) {
                echo '<meta http-equiv="refresh" content="0; url=' . $redirect_url . '">';
                exit;
            }
        }

        public function redirection_by_post_id($post_id = '') {
            if (!empty($post_id)) {

                $redirect_url = get_post_meta($post_id, '_redirection_url', TRUE);

                if ($redirect_url) {
                    echo '<meta http-equiv="refresh" content="0; url=' . $redirect_url . '">';
                    exit;
                }
            }
        }
        
        public function build_aff_url($aff_code, $url) {
            
            $base_url = "http://go.masoffer.net/v0/{$aff_code}?url=";
            
            $builded_url = $base_url . urlencode($url);
            
            return $builded_url;
            
        }

    }

}

if (class_exists('TD_Redirection')) {
    $MyRedirection = new TD_Redirection();
}