<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ip_information
 *
 * @author dmtuan
 */
function function_get_ip_information_page() {
    
       load_assets_get_ip_info();
//    global_admin_ajax();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    
     echo '<div class="wrap">';
     
    if (isset($_POST['process_checkIPInfo'])) {
        $ip_list = $_POST['ip-list'];
        $ip_provider = $_POST['ip-provider'];
        
        $ip_list = explode("\n", str_replace("\r", "", $ip_list));
        
        echo '<div class="row"> 
                <div class="col-lg-12">';
            echo '<div class="panel panel-default">
                            <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>
                                IP Information List
                                <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Options
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Show Info</a>
                                        </li>
                                        <li><a href="#">Hide Info</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">

                                </div></div>
                                <div class="row">
                                <div class="col-sm-12">
                                <table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;">
                                   <thead>
                                    <tr role="row">
                                       <th class="sorting_desc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;" aria-sort="descending" >No</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">IP</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 20px;">ISP / Organization</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 20px;">State/Region</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Lat</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Lon</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 50px;">Address</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Provider</th>
                                       
                                    </tr>
                                 </thead>
                                    <tbody>';


//        $ip_list = 

        $requestURLs = multi_call($ip_list, $ip_provider);
        
        $ip_details = runRequests($requestURLs);
        
        $ip_details = process_received_IP_API($ip_details);
        
        $ip_details = runRequests($ip_details);
        
        $ip_details = process_received_GEO_API($ip_details);
        
        $retry = [];
        foreach ($ip_details as $key => $ip) {
            if (!isset($ip['_ip']) || $ip['_ip'] != $ip['ip'] || $ip['status'] == 0 || empty($ip['address'])) {    
                $retry[] = $ip;
                unset($ip_details[$key]);
            }
        }

        // Try 1 more time
        
//        echo "<pre>";
//        print_r($retry);
//        echo "<pre>";
        
        $try_requestURLs = multi_call_try($retry);
        
        $try_ip_details = runRequests($try_requestURLs);
        
        $try_ip_details = process_received_IP_API($try_ip_details);
        
        $try_ip_details = runRequests($try_ip_details);
        
        $try_ip_details = process_received_GEO_API($try_ip_details);
        
        $ip_details = array_merge($ip_details, $try_ip_details);
        
        $count = 0;
        foreach ($ip_details as $key => $ip_detail) {
            
            $count++;

            if ($count % 2 == 0) {
                $row_color = "gradeA odd";
            } else {
                $row_color = "gradeA even";
            }
            
            echo '<tr class="' . $row_color . '" role="row">';
            
            if ($ip_detail['status'] == 1) {
                
                echo '<td class="center">' . $count . '</td>';
                echo '<td class="center">' . $ip_detail['ip'] . '</td>';
                echo '<td class="center">' . $ip_detail['isp'] . '</td>';
                echo '<td class="center">' . $ip_detail['region'] . '</td>';
                echo '<td class="center">' . $ip_detail['lat'] . '</td>';
                echo '<td class="center">' . $ip_detail['lon'] . '</td>';
                
                if (!empty($ip_detail['address'])) {
                    echo '<td class="center"><a href="' . $ip_detail['url'] . '" target="_blank" >' . $ip_detail['address'] . '</a></td>';
                } elseif (isset($ip_detail['message'])) {
                    echo '<td class="center">' . $ip_detail['message'] . '</td>';
                } else {
                    echo '<td class="center">Empty</td>';
                }
                
                echo '<td class="center"><a href="' . $ip_detail['api_url'] . '" target="_blank" >' . $ip_detail['provider'] . '</a> | <a href="' . $ip_detail['api_other']['url'] . '" target="_blank" >X</a></td>';
                
            } else {

                echo '<td class="center">' . $count . '</td>';
                echo '<td class="center">' . $ip_detail['ip'] . '</td>';
                echo '<td class="center">' . $ip_detail['message'] . '</td>';
                echo '<td class="center"></td>';
                echo '<td class="center"></td>';
                echo '<td class="center"></td>';
                echo '<td class="center"></td>';
                echo '<td class="center"></td>';
                
                }
//                echo '<td>  <button type="button" class="btn btn-success btn-xs button-edit" data-toggle="modal" data-target="#myEditModal" title="Edit this redirection"><i class="glyphicon glyphicon-edit"></i></button>';
//                echo '  <button type="button" class="btn btn-danger btn-xs button-delete" title="Delete this redirection"><i class="fa fa-times"></i></button>';
//                echo '</td>';
            echo '</tr>';
        }
                        echo '</tbody>
                                </table></div></div>
                                <!-- <div class="row"><div class="col-sm-6"><div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div></div><div class="col-sm-6"><div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">4</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">5</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">6</a></li><li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next"><a href="#">Next</a></li></ul></div></div></div></div> --> 
                                <!-- /.table-responsive -->

                            </div>
                            <!-- /.panel-body -->
                        </div>';
            echo '</div></div></div>';
    }
    else {
    echo '<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">Get IP Information</font></strong>
                            
                        </div>
                        <div class="panel-body">';
                        

    echo '<form role="form" method="post">
                                <div class="form-group">
                                            <label>Select Providers</label>
                                            <select class="form-control" id="ip-provider" name="ip-provider">
                                                <option value="ipapi.co">ipapi.co</option>
                                                <option value="ip-api.com">ip-api.com</option>
                                                <option value="ipdata.co">ipdata.co</option>
                                                <option value="random" selected>Random Provider</option>
                                                <option value="default">Default</option>
                                            </select>
                                        </div>
                                        
                                <div class="form-group">
                                    <textarea id="ip-list" name="ip-list" class="form-control" rows="10">125.234.98.126
103.11.173.6
54.243.31.232
107.23.255.8
176.34.159.232
54.228.16.8
45.76.189.200
54.251.31.174
10.42.66.15
103.7.38.16
10.42.102.98
45.126.97.32
10.42.64.106
172.17.0.1
27.79.87.196
115.77.234.78
54.232.40.72
177.71.207.168
54.255.254.240
54.250.253.240
123.25.190.33</textarea>
                                </div>
                                
                                <input type="hidden" id="process_checkIPInfo" name="process_checkIPInfo">

                                <button type="submit" class="btn btn-success">Get Info</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
    
    echo '</div></div></div></div>';
    }
        
    
    echo '<div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true" class="">×   </span><span class="sr-only">Close</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Edit Redirection</h4>

            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" id="close-update-modal" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>';
    
}

function multi_call($ip_list, $provider = '') {
    foreach ($ip_list as $ip) {
        $request_URLs[] = getIpRequestURL($ip, $provider);
    }
    return $request_URLs;
}

function multi_call_try($try_ip_list) {
    foreach ($try_ip_list as $ip) {
        $request_URLs[] = getIpRequestURL($ip['ip'], $ip['provider_id'], TRUE);
    }
    return $request_URLs;
}

function process_received_IP_API($ip_details) {

    foreach ($ip_details as $key => $ip) {
        $return = json_decode($ip['result'], true);
        $result = [];
        switch ($ip['provider_id']) {
            case 'ip-api.com':
            case 2: 
                if (!is_null($return) && isset($return['query'])) {
                    $result['_ip'] = $return['query'];
                    $result['region'] = $return['regionName'];
                    if (!empty($return['org'])) {
                        $result['isp'] = $return['org'];
                    } else {
                        $result['isp'] = $return['isp'];
                    }
                    $result['lat'] = $return['lat'];
                    $result['lon'] = $return['lon'];
                    $result['provider'] = 'ip-api.com';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 2));
                }
                break;
                
            case 'ipdata.co':
            case 3:
                if (!is_null($return) && isset($return['ip'])) {
                    $result['_ip'] = $return['ip'];
                    if (!empty($return['region'])) {
                        $result['region'] = $return['region'];
                    } else {
                        $result['region'] = $return['country_name'];
                    }
                    
                    $result['isp'] = $return['organisation'];
                    $result['lat'] = $return['latitude'];
                    $result['lon'] = $return['longitude'];
                    $result['provider'] = 'ipdata.co';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 3));
                }
                break;
                
            case 'ipapi.co':
            case 1:
                if (!is_null($return) && isset($return['ip'])) {
                    $result['_ip'] = $return['ip'];
                    $result['region'] = $return['region'];
                    $result['isp'] = $return['org'];
                    $result['lat'] = $return['latitude'];
                    $result['lon'] = $return['longitude'];
                    $result['provider'] = 'ipapi.co';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 1));
                } 
                break;
            
            case 'freegeoip.net':
            case 4:
                if (!is_null($return) && isset($return['ip'])) {
                    $result['_ip'] = $return['ip'];
                    if (!empty($return['region_name'])) {
                        $result['region'] = $return['region_name'];
                    } else {
                        $result['region'] = $return['city'];
                    }
                    
                    $result['isp'] = ''; // $return['org'];
                    $result['lat'] = $return['latitude'];
                    $result['lon'] = $return['longitude'];
                    $result['provider'] = 'freegeoip.net';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 4));
                } 
                break;
                
            case 'ipinfo.io':
            case 5:
                if (!is_null($return) && isset($return['ip'])) {
                    $result['_ip'] = $return['ip'];
                    if (!empty($return['region_name'])) {
                        $result['region'] = $return['region'];
                    } else {
                        $result['region'] = $return['city'];
                    }
                    
                    $result['isp'] = $return['org']; // $return['org'];
                    $temp = explode(',', $return['loc']);
                    $result['lat'] = $temp[0];
                    $result['lon'] = $temp[1];
                    $result['provider'] = 'ipinfo.io';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 5));
                } 
                break;
                
            case 'iplocate.io':
            case 6:
                if (!is_null($return) && isset($return['ip'])) {
                    $result['_ip'] = $return['ip'];
                    $result['region'] = $return['city'];
                    $result['isp'] = $return['org']; // $return['org'];
                    $result['lat'] = $return['latitude'];
                    $result['lon'] = $return['longitude'];
                    $result['provider'] = 'iplocate.io';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 6));
                } 
                break;
                
            case 'extreme-ip-lookup.com':
            case 7:
                if (!is_null($return) && isset($return['query'])) {
                    $result['_ip'] = $return['query'];
                    $result['region'] = $return['region'];
                    if (!empty($return['isp'])) {
                        $result['isp'] = $return['isp']; 
                    } else {
                        $result['isp'] = $return['org']; // $return['org'];
                    }
                    
                    $result['lat'] = $return['lat'];
                    $result['lon'] = $return['lon'];
                    $result['provider'] = 'extreme-ip';
                    $result['api_url'] = $ip['url'];
                    $result['api_other'] = getIpRequestURL($result['_ip'], get_random(API_IP_PROVIDER_MAX, 7));
                } 
                break;
        }
        
        if (!empty($result)) {
            $result['status'] = 1;
            if (!empty($result['lat']) && !empty($result['lon'])) {
                $result['url'] = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$result['lat']},{$result['lon']}&key=***REMOVED***";
            } else {
                $result['url'] = '';
            }
            $ip_details[$key] = array_merge($ip_details[$key], $result);
        } else {
            if (!is_null($return) && !empty($return['message'])) {
               $result['status'] = 0;
               $result['message'] = $return['message'];
               $ip_details[$key] = array_merge($ip_details[$key], $result);
            } else {
               $result['status'] = 0;
               $result['message'] = $return;
               $ip_details[$key] = array_merge($ip_details[$key], $result);
            }
        }
        
        unset($ip_details[$key]['result']);
        unset($ip_details[$key]['handle']);
        
    }
    
    return $ip_details;
}

function process_received_GEO_API($ip_details) {

    foreach ($ip_details as $key => $ip) {
        
        $return = json_decode($ip['result'], true);
        
        if (!is_null($return) && is_array($return['results'])) {
            foreach ($return['results'] as $address) {
                if (isset($address['formatted_address']) && !empty($address['formatted_address'])) {
                    $ip_details[$key]['address'] = $address['formatted_address'];
                    break;
                }
            }
        } else {
            
        }
        
        unset($ip_details[$key]['result']);
        unset($ip_details[$key]['handle']);
        
    }
    
    return $ip_details;
}

function getIpRequestURL($ip, $provider = '', $different = false) {
    
    if ($different == false) {
        if ($provider == 'default' || $provider == '') {
            $api = 2;
        } elseif ($provider == 'random') {
            $api = rand(1, API_IP_PROVIDER_MAX);
        } else {
            $api = $provider;
        }
    } else {
        $api = get_random(API_IP_PROVIDER_MAX, $provider);
//        for ($i = 1; $i <= API_IP_PROVIDER_MAX; $i++) {
//            if ($i != $provider) {
//               $api = $i;
//               break;
//            }
//        }
    }
    
    switch ($api) {
            
            case 'ipdata.co':
            case 3:
                $url = "https://api.ipdata.co/{$ip}";
                break;
            
            case 'ipapi.co':
            case 1:
                $url = "https://ipapi.co/{$ip}/json";
                break;
            
            case 'ip-api.com':
            case 2:
                $url = "http://ip-api.com/json/{$ip}";
                break;
            
            case 'freegeoip.net':
            case 4:
                $url = "http://freegeoip.net/json/{$ip}";
                break;
            
            case 'ipinfo.io':
            case 5:
                $url = "http://ipinfo.io/{$ip}/json?token=aa50a2558978ba";
                break;
            
            case 'iplocate.io':
            case 6:
                $url = "https://www.iplocate.io/api/lookup/{$ip}";
                break;
            
            case 'extreme-ip':
            case 7:
                $url = "http://extreme-ip-lookup.com/json/{$ip}";
                break;
                
    }
    
    $return['ip'] = $ip;
    $return['url'] = $url;
    $return['provider_id'] = $api;
    
    return $return;
    
}

function runRequests($url_array, $thread_width = 8) {
    $threads = 0;
    $master = curl_multi_init();
    $curl_opts = array(CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => TRUE);
    $results = array();

    $count = 0;
    foreach($url_array as $url) {

        $ch = curl_init();
        $curl_opts[CURLOPT_URL] = $url['url'];

        curl_setopt_array($ch, $curl_opts);
        curl_multi_add_handle($master, $ch); //push URL for single rec send into curl stack
        
        $temp_result = array("handle" => $ch);
        $temp_result = array_merge($temp_result, $url);
        
        $results[$count] = $temp_result;
        
        $threads++;
        $count++;
        if($threads >= $thread_width) { //start running when stack is full to width
            while($threads >= $thread_width) {
                usleep(100);
                while(($execrun = curl_multi_exec($master, $running)) === -1){}
                curl_multi_select($master);
                // a request was just completed - find out which one and remove it from stack
                while($done = curl_multi_info_read($master)) {
                    foreach($results as &$res) {
                        if($res['handle'] == $done['handle']) {
                            $res['result'] = curl_multi_getcontent($done['handle']);
                        }
                    }
                    curl_multi_remove_handle($master, $done['handle']);
                    curl_close($done['handle']);
                    $threads--;
                }
            }
        }
    }
    do { //finish sending remaining queue items when all have been added to curl
        usleep(100);
        while(($execrun = curl_multi_exec($master, $running)) === -1){}
        curl_multi_select($master);
        while($done = curl_multi_info_read($master)) {
            foreach($results as &$res) {
                if($res['handle'] == $done['handle']) {
                    $res['result'] = curl_multi_getcontent($done['handle']);
                }
            }
            curl_multi_remove_handle($master, $done['handle']);
            curl_close($done['handle']);
            $threads--;
        }
    } while($running > 0);
    curl_multi_close($master);
    return $results;
}
