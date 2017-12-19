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

if (!class_exists('TD_Redirection_HTML')) {

    class TD_Redirection_HTML {

        function __construct() {
            
        }

        public function redirection($aff_id, $aff_link) {
            $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $ip = getClientIP();
            $note = $_SERVER['REQUEST_URI'];
            
            $ip_safe = getIpSafe($ip);
            if (isset($ip_safe['ip'])) {
                $proxy_log = 'Block: ' . $ip_safe['block'] . ', Country: ' . $ip_safe['countryName'] . ', ISP: ' . $ip_safe['isp'];
            } else {
                $proxy_log = $ip_safe;
            }

            if (isset($ip_safe['block']) && $ip_safe['block'] == 1) {
                $dbModel->log_client_IP(0 , $ip, $note, 2, $proxy_log);
                $this->redirection_by_url(urldecode($aff_link));
            } else {
                $dbModel->log_client_IP(0, $ip, $note, 1, $proxy_log);
                $aff = $dbModel->getAffiliateAccountByID($aff_id);
                $aff_link = $this->build_aff_url($aff['aff_code'], $aff_link);

                $this->redirection_by_url($aff_link);
            }

        }


        public function redirection_by_url($redirect_url = '') {
            
//            echo $redirect_url;
//            exit;
            
            if (!empty($redirect_url)) {
                echo '<meta http-equiv="refresh" content="0; url=' . $redirect_url . '">';
                exit;
            }
        }

        public function build_aff_url($aff_code, $url) {
            
            $base_url = "http://go.masoffer.net/v0/{$aff_code}?url=";
            
            $builded_url = $base_url . urlencode($url);
            
            return $builded_url;
            
        }

    }

}
