<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbModel
 *
 * @author MT
 */
class DbModel {

    private $link;

    public function __construct($host, $user, $pass, $dbname) {
        $this->link = mysqli_connect($host, $user, $pass, $dbname);
    }
    
    public function query($query) {
        $result = mysqli_query($this->link, $query);
        return $result;
    }


//    public function getAllRedirection($type = '') {
//        
//        $query = "SELECT * FROM " . DB_REDIRECTION;
//        
//        if (!empty($type)) {
//            $query .= 'WHERE re_type = "' . $type . '"';
//        }
//        
//        $result = mysqli_query($this->link, $query);
//
//        $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
//
//        return $return;
//        
//    }
    
    public function getAllStoreRedirection_IpCount() {
        
        $query = 'SELECT 
                    re_id, 
                    re_source, 
                    concat(aff_name, ": ", aff_code) as aff,
                    re_destination, 
                    re_type, 
                    re_active, 
                    name, 
                    (SELECT count(*)
                                            FROM wp_td_visitor_ip
                            WHERE vi_url = wp_td_redirection.re_id
                            AND vi_redirected = 0
                    ) as re_count_non, 
                    (SELECT count(*)
                                            FROM wp_td_visitor_ip
                            WHERE vi_url = wp_td_redirection.re_id
                            AND vi_redirected = 1
                    ) as re_count_redirect
                FROM wp_td_redirection 
                INNER JOIN wp_terms ON re_source = wp_terms.term_id
                INNER JOIN wp_td_affiliate ON re_aff = aff_id
                WHERE re_type = "store"';
        
        $result = mysqli_query($this->link, $query);

        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $return = [];
        }

        return $return;
        
    }
    
    public function getAllCouponRedirection_IpCount() {
        
        $query = '  SELECT 	
                        re_id, 
                        re_source, 
                        concat(aff_name, ": ", aff_code) as aff,
                        re_destination,
                        re_type, 
                        re_active, 
                        post_title as name, 
                        (SELECT count(*)
                                    FROM wp_td_visitor_ip
                            WHERE vi_url = wp_td_redirection.re_id
                            AND vi_redirected = 0
                        ) as re_count_non, 
                        (SELECT count(*)
                                    FROM wp_td_visitor_ip
                            WHERE vi_url = wp_td_redirection.re_id
                            AND vi_redirected = 1
                        ) as re_count_redirect
                    FROM wp_td_redirection 
                    INNER JOIN wp_posts ON re_source = wp_posts.ID
                    INNER JOIN wp_td_affiliate ON re_aff = aff_id
                    WHERE re_type = "coupon"';
        
        $result = mysqli_query($this->link, $query);

        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $return = [];
        }

        return $return;
        
    }
    
    public function add_redirection($source, $aff_id, $destination, $type = 'post', $active = 1, $source_multi = '') {
        
        $exists = $this->check_exists_redirection($source, $type);
        
        if ($exists != false) {
            $this->update_redirection($exists['re_id'], $source, $aff_id, $destination, $type, $source_multi);
            return false;
        } else {
            
            if ($type == 'coupon') {
                $temp_query = '   select term_id as store_id
                                from wp_term_relationships 
                                INNER JOIN wp_term_taxonomy 
                                ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
                                where wp_term_relationships.object_id = ' . $source . '
                                and taxonomy = "coupon_store"';
                
                $temp_result = mysqli_query($this->link, $temp_query);

                $temp_return = mysqli_fetch_assoc($temp_result);

                if (count($temp_return) > 0) {
                    $parent = $temp_return['store_id'];
                } else {
                    $parent = 0;
                }
            } elseif ($type == 'store') {
                $parent = $source;
            }
            
            if (!$parent) {
                $parent = 0;
            }
            
            $query = '  INSERT INTO ' . DB_REDIRECTION . '(re_source, re_aff, re_destination, re_type, re_active, re_count_non, re_count_redirect, re_count, re_parent)
                        VALUES (
                        ' . $source . ',
                        ' . $aff_id . ',
                        "' . urlencode($destination) . '",
                        "' . $type . '",'
                        . $active . 
                        ', 0, 0, 0, ' . $parent . ')';

            $result = mysqli_query($this->link, $query);
            return true;
        }
    }
    
    public function check_exists_redirection($source, $type, $source_multi = '') {
        
        $query = 'SELECT * FROM ' . DB_REDIRECTION . ' WHERE re_source = ' . $source . ' AND re_type = "' . $type . '"';
        
        $result = mysqli_query($this->link, $query);
        
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            return $return;
        } else {
            return [];
        }
    }

    public function update_redirection($re_id, $source, $aff_id, $destination, $type = 'post', $source_multi = '') {
        
        $query = '  UPDATE ' . DB_REDIRECTION . '
                    SET 
                    re_source = ' . $source . ',
                    re_aff = ' . $aff_id . ',
                    re_destination = "' . urlencode($destination) . '",
                    re_type = "' . $type . '",
                    re_active = 1
                    WHERE re_id = ' . $re_id;

        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
    
    public function update_redirection_part($re_id, $aff_id, $destination, $active) {
        
        $query = '  UPDATE ' . DB_REDIRECTION . '
                    SET 
                    re_aff = ' . $aff_id . ',
                    re_destination = "' . urlencode($destination) . '",
                    re_active = ' . $active . '
                    WHERE re_id = ' . $re_id;

        $result = mysqli_query($this->link, $query);

        return $result;
        
    }

    public function update_active_redirection($re_id, $active = '1') {
        
        $query = '  UPDATE ' . DB_REDIRECTION . '
                    SET 
                    re_active = ' . $active . '
                    WHERE re_id = ' . $re_id;

        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
    
    public function delete_redirection($re_id) {
        
        $query = '  DELETE FROM ' . DB_REDIRECTION . ' WHERE re_id = '. $re_id;

        $result = mysqli_query($this->link, $query);

        return true;
        
    }
    
    public function update_count($re_id, $type_redirection = false) {
        
        if ($type_redirection) {
            $query = '  UPDATE ' . DB_REDIRECTION . '
                        SET 
                        re_count = re_count + 1,
                        re_count_redirect = re_count_redirect + 1
                        WHERE re_id = ' . $re_id;
        } else {
            $query = '  UPDATE ' . DB_REDIRECTION . '
                        SET 
                        re_count = re_count + 1,
                        re_count_non = re_count_non + 1
                        WHERE re_id = ' . $re_id;
        }
        
        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
    
    public function log_client_IP($re_id, $ip, $agent = '', $redirected = 0, $proxy_log = '') {
        
            $query = '  INSERT INTO ' . DB_VISITOR_IP . ' (vi_ip, vi_url, vi_date, vi_notes, vi_proxy, vi_redirected)
                        VALUES (
                        "' . $ip . '",
                        ' . $re_id . ',
                        Now(),
                        "' . $agent . '",
                        "' . $proxy_log . '",
                        ' . $redirected . '
                        )';
            
        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
    
    public function getAllCouponStore($store_name = '') {
        
        $query = "SELECT wp_terms.term_id, wp_terms.name FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE wp_term_taxonomy.taxonomy = 'coupon_store'";
        if (!empty($store_name)) {
            $query .= "AND wp_terms.name like '%" . $store_name . "%'";
        }
        $result = mysqli_query($this->link, $query);
        
        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $return;
        } else {
            return [];
        }
    }
    
    public function getStoreInfoByStoreID($store_id = '') {
        
        $query = 'SELECT * FROM wp_terms WHERE term_id = ' . $store_id;
        
        $result = mysqli_query($this->link, $query);
        
        if ($result) {
            $return = mysqli_fetch_assoc($result);
            return $return;
        } else {
            return false;
        }
    }
    
    public function getAllVistorIpTracking($query_type = 'default', $input = []) {
        
        $isand = false;
        $iswhere = true;
        
        switch ($query_type) {
            case 'query_all':
                $query = 'SELECT * 
                    FROM wp_td_visitor_ip 
                    INNER JOIN wp_td_redirection ON vi_url = re_id';
                break;
            
            case 'query_store':
                $query = 'SELECT * 
                    FROM wp_td_visitor_ip 
                    INNER JOIN wp_td_redirection ON vi_url = re_id';
                
                if (isset($input['store_id']) && !empty($input['store_id'])) {
                    $query .= ' WHERE re_parent = ' . $input['store_id'];
                    $iswhere = false;
                    $isand = true;
                }
                
                break;
            case 'query_coupon':
                $query = 'SELECT * 
                    FROM wp_td_visitor_ip 
                    INNER JOIN wp_td_redirection ON vi_url = re_id';
                
                if (isset($input['post_id']) && !empty($input['post_id'])) {
                    $query .= ' WHERE re_source = ' . $input['post_id'];
                    $iswhere = false;
                    $isand = true;
                }
                break;
                
            default:
                $query = 'SELECT * FROM wp_td_visitor_ip INNER JOIN wp_td_redirection ON vi_url = re_id ';
                break;
        }
        
        switch ($input['query_timetype']) {
            case 'time_all':
                break;
            case 'time_today':
                if ($iswhere == true) {
                        $query .= ' WHERE ';
                        $iswhere = false;
                        $isand = true;
                } elseif ($isand == true) {
                    $query .= ' AND ';
                }
                $query .= ' DATE(vi_date) = CURDATE() ';
                break;
            case 'time_7day':
                if ($iswhere == true) {
                        $query .= ' WHERE ';
                        $iswhere = false;
                        $isand = true;
                } elseif ($isand == true) {
                    $query .= ' AND ';
                }
                $query .= ' vi_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ';
                break;
            case 'time_1month':
                if ($iswhere == true) {
                        $query .= ' WHERE ';
                        $iswhere = false;
                        $isand = true;
                } elseif ($isand == true) {
                    $query .= ' AND ';
                }
                $query .= ' vi_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ';
                break;
            case 'time_3month':
                if ($iswhere == true) {
                        $query .= ' WHERE ';
                        $iswhere = false;
                        $isand = true;
                } elseif ($isand == true) {
                    $query .= ' AND ';
                }
                $query .= ' vi_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ';
                break;
            case 'time_custom':
                if (isset($input['time_start']) && !empty($input['time_start'])) {
                    if ($iswhere == true) {
                        $query .= ' WHERE ';
                        $iswhere = false;
                        $isand = true;
                    } elseif ($isand == true) {
                        $query .= ' AND ';
                    }
                    $query .= ' vi_date >= "' . $input['time_start'] . '"';
                }

                if (isset($input['time_end']) && !empty($input['time_end'])) {
                    if ($iswhere == true) {
                        $query .= ' WHERE ';
                        $iswhere = false;
                        $isand = true;
                    } elseif ($isand == true) {
                        $query .= ' AND ';
                    }
                    $query .= ' vi_date <= "' . $input['time_end'] . '"';
                }
                break;
            
        }
        
        $query .= ' ORDER BY vi_id DESC';
        
        $result = mysqli_query($this->link, $query);

        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $return = [];
        }

        return $return;
        
    }
    
    public function getAllAffiliateAccount() {
        
        $query = "SELECT * FROM wp_td_affiliate";
        
        $result = mysqli_query($this->link, $query);

        if ($result) {
            $return = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $return = [];
        }
        
        return $return;
        
    }
    
    public function getAffiliateAccountByID($id) {
        
        $query = "SELECT * FROM wp_td_affiliate WHERE aff_id = " . $id;
        
        $result = mysqli_query($this->link, $query);

        if ($result) {
            $return = mysqli_fetch_assoc($result);
        } else {
            $return = false;
        }
        
        return $return;
        
    }
    
    public function getAffiliateAccountByCode($code) {
        
        $query = "SELECT * FROM wp_td_affiliate WHERE aff_code = " . $code;
        
        $result = mysqli_query($this->link, $query);

        if ($result) {
            $return = mysqli_fetch_assoc($result);
        } else {
            $return = false;
        }
        
        return $return;
        
    }
    
    public function addNewAffiliateAccount($aff_name, $aff_code, $default = 0) {
        
        $query = '  INSERT INTO wp_td_affiliate(aff_name, aff_code)
                        VALUES (
                        "' . $aff_name . '",
                        "' . $aff_code . '")';
        
        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
    
    public function UpdateAffiliateAccount($aff_id, $aff_name, $aff_code, $default = 0) {
        
        $query = '  UPDATE wp_td_affiliate
                    SET 
                        aff_name = "' . $aff_name . '",
                        aff_code = "' . $aff_code . '"
                        WHERE aff_id = ' . $aff_id;
        
        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
    
    public function deleteAffiliateAccount($aff_id) {
        
        $query = '  DELETE FROM wp_td_affiliate WHERE aff_id = '. $aff_id;
        
        $result = mysqli_query($this->link, $query);

        return $result;
        
    }
}

