<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getIpInfo1($ip) {
    
    $url = "https://ipapi.co/{$ip}/json";
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
    $return = json_decode($response, true);
    if (!is_null($return) && isset($return['ip'])) {
        $result['ip'] = $return['ip'];
        $result['region'] = $return['region'];
        $result['isp'] = $return['org'];
        $result['lat'] = $return['latitude'];
        $result['lon'] = $return['longitude'];
        $result['provider'] = 'ipapi.co';
        $result['api_url'] = $url;
        return $result;
    } else {
        return false;
    }
}


function getIpInfo2($ip) {
    
    $url = "http://ip-api.com/json/{$ip}";
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
    $return = json_decode($response, true);
    
    if (!is_null($return) && isset($return['query'])) {
        $result['ip'] = $return['query'];
        $result['region'] = $return['regionName'];
        if (!empty($return['org'])) {
            $result['isp'] = $return['org'];
        } else {
            $result['isp'] = $return['isp'];
        }
        $result['lat'] = $return['lat'];
        $result['lon'] = $return['lon'];
        $result['provider'] = 'ip-api.com';
        $result['api_url'] = $url;
        return $result;
    } else {
        return false;
    }
}

function getIpInfo3($ip) {
    
    $url = "https://api.ipdata.co/{$ip}";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
    $return = json_decode($response, true);
    if (!is_null($return) && isset($return['ip'])) {
        $result['ip'] = $return['ip'];
        $result['region'] = $return['region'];
        $result['isp'] = $return['organisation'];
        $result['lat'] = $return['latitude'];
        $result['lon'] = $return['longitude'];
        $result['provider'] = 'ipdata.co';
        $result['api_url'] = $url;
        return $result;
    } else {
        return false;
    }
}


function getIpInfo($ip, $provider = 'default') {
    
    if ($provider == 'default') {
        $ip_info = getIpInfo2($ip);
        if ($ip_info == false) {
            $ip_info = getIpInfo1($ip);
            if ($ip_info == false) {
                $ip_info = getIpInfo3($ip);
            }
        }
        return $ip_info;
    } elseif ($provider == 'random') {
        
        $random = rand(1,3);
        
        switch ($random) {
            case 'ipapi.co':
            case 1:
                $ip_info = getIpInfo1($ip);
                break;
            
            case 'ipdata.co':
            case 3:
                $ip_info = getIpInfo3($ip);
                break;
            
            case 'ip-api.com':
            case 2:
            default:
                $ip_info = getIpInfo2($ip);
                break;
        }
        
        return $ip_info;
        
    } else {
        switch ($provider) {
            case 'ipapi.co':
            case 1:
                $ip_info = getIpInfo1($ip);
                break;
            
            case 'ipdata.co':
            case 3:
                $ip_info = getIpInfo3($ip);
                break;
            
            case 'ip-api.com':
            case 2:
            default:
                $ip_info = getIpInfo2($ip);
                break;
        }
        
        return $ip_info;
    }
}

function getAddressGoogleAPI($lat,$lon) {
    $details = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lon}&key=***REMOVED***"), true);
    
    if (isset($details['results'])) {
        return $details['results'][0]['formatted_address'];
    } else {
        return false;
    }
}


function getAddressGoogleAPI2($lat,$lon) {
    
    if ( empty($lat) || empty($lon)) {
        return false;
    }
    
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lon}&key=***REMOVED***";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
    $return = json_decode($response, true);
    
    if (!is_null($return) && isset($return['results'])) {
        
        if (is_array($return['results'])) {
            return $return['results'][0]['formatted_address'];
        } else {
            echo '<pre>';
            print_r($return);
            echo '</pre>';
            exit;
        }
    } else {
        return getAddressGoogleAPI($lat, $lon);
    }
}