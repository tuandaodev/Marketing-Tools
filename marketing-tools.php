<?php

/**
 * Plugin Name: Marketing Tools
 * Plugin URI: http://minhtuanit.me
 * Description: A small tools to help you manage your WordPress - WooCommerce
 * Version: 2.00
 * Author: Tuan Dao
 * Author URI: http://minhtuanit.me
 * License: GPL2
 * Created On: 12-06-2017
 * Updated On: 12-09-2017
 */
// Define WC_PLUGIN_DIR.
if (!defined('WC_PLUGIN_DIR')) {
    define('WC_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// Define WC_PLUGIN_FILE.
if (!defined('WC_PLUGIN_URL')) {
    define('WC_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('DB_REDIRECTION')) {
    define('DB_REDIRECTION', 'wp_td_redirection');
}

if (!defined('DB_VISITOR_IP')) {
    define('DB_VISITOR_IP', 'wp_td_visitor_ip');
}

require_once('autoload.php');
require_once('includes/helper.php');

add_action('plugins_loaded', 'marketing_tools_plugin_init');

register_activation_hook(__FILE__, 'tracking_create_db');
register_activation_hook(__FILE__, 'redirection_create_db');

function marketing_tools_plugin_init() {
    add_action('admin_menu', 'marketing_tools_admin_menu');
    add_action('login_init', 'send_frame_options_header', 10, 0);
    add_action('admin_init', 'send_frame_options_header', 10, 0);
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



function function_redirection_page() {
    
    load_assets_redirection();
    global_admin_ajax();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    echo '<div class="wrap">';
    echo '<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">COUPON Redirection</font></strong>
                        </div>
                        <div class="panel-body">';
                        
    if (isset($_POST['process_addNewCouponRedirection'])) {
        
        $add = $dbModel->add_redirection($_POST['post_id'], $_POST['post_redirect_url'], 'coupon');
        
        if ($add) {
            $string_add = "Added";
        } else {
            $string_add = "Updated";
        }
        
        echo '<div class="alert alert-success">
                        <strong>' . $string_add . ' the redirection successful. Coupon ID: <font color="red">' . $_POST['post_id'] . '</font><br/>
                            URL: <font color="blue">' . $_POST['post_redirect_url'] . '</font>
                            </strong>
            </div>';
        
    }
    echo '<form role="search" method="get">
                    <div class="form-group input-group">
                                            <input type="text" id="post_search" name="s" class="form-control search-autocomplete" placeholder="Search">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" disabled><i class="fa fa-search" button></i>
                                                </button>
                                            </span>
                                        </div>
            </form>';
    echo '<form role="form" method="post">
                                <div class="form-group">
                                    <label>Coupon ID</label>
                                    <input type="number" class="form-control" id="post_id" name="post_id" value="400" required>
                                </div>
                                <div class="form-group">
                                    <label>Redirect URL</label>
                                    <input type="text" class="form-control" id="post_redirect_url" name="post_redirect_url" value="https://google.com.vn" required>
                                </div>
                                
                                <input type="hidden" id="process_addNewCouponRedirection" name="process_addNewCouponRedirection">

                                <button type="submit" class="btn btn-success">Add New</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
    
    echo '</div></div></div>';
    
    echo '
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">STORE Redirection</font></strong>
                        </div>
                        <div class="panel-body">';
    
    if (isset($_POST['process_addNewStoreRedirection'])) {
        
        $add = $dbModel->add_redirection($_POST['store_id'], $_POST['store_redirect_url'], 'store');
        
        if ($add) {
            $string_add = "Added";
        } else {
            $string_add = "Updated";
        }
        
        echo '<div class="alert alert-success">
                        <strong>' . $string_add . ' the redirection successful. Coupon ID: <font color="red">' . $_POST['store_id'] . '</font><br/>
                            URL: <font color="blue">' . $_POST['store_redirect_url'] . '</font>
                            </strong>
            </div>';
        
    }
        echo '<form role="search" method="get">
                    <div class="form-group input-group">
                                            <input type="text" id="store_search" name="s" class="form-control search-autocomplete" placeholder="Search">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" disabled><i class="fa fa-search" button></i>
                                                </button>
                                            </span>
                                        </div>
            </form>';
        echo '<form role="form" method="post">
                                <div class="form-group">
                                    <label>Store ID</label>
                                    <input type="number" class="form-control" id="store_id" name="store_id" value="34" required>
                                </div>
                                <div class="form-group">
                                    <label>Redirect URL</label>
                                    <input type="text" class="form-control" id="store_redirect_url" name="store_redirect_url" value="https://google.com.vn" required>
                                </div>
                                
                                <input type="hidden" id="process_addNewStoreRedirection" name="process_addNewStoreRedirection">

                                <button type="submit" class="btn btn-success">Add New</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
      
        echo '</div></div></div></div>';
        echo '<div class="row"> 
            <div class="col-lg-12">';
        echo '<div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                            Redirection List
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
                                   <th class="sorting_desc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px; display: none;" aria-sort="descending" >ReID</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">PID</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Type</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 200px;">Title</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 250px;">Redirect URL</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Status</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Non/Re/All</th>
                                   <th aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Options</th>
                                </tr>
                             </thead>
                                <tbody>';
    
    $all_coupon_redirections = $dbModel->getAllCouponRedirection();
    $all_store_redirections = $dbModel->getAllStoreRedirection();
    
    $all_redirections = array_merge($all_coupon_redirections, $all_store_redirections);
    
    $count = 0;
    foreach ($all_redirections as $redirect) {

        if ($redirect['re_type'] == 'store') {
            $url = get_term_link((int)$redirect['re_source']);
        } else {
            $url = get_permalink($redirect['re_source']);
        }

        if (isset($url->errors)) {
            $url = '';
        }

        $count++;
        if ($count % 2 == 0) {
            $row_color = "gradeA odd";
        } else {
            $row_color = "gradeA even";
        }
        echo ' <tr class="' . $row_color . '" role="row" re_id = "' . $redirect['re_id'] . '">
                        <td class="sorting_1" style="display:none;">' . $redirect['re_id'] . '</td>
                       <td class="center">' . $redirect['re_source'] . '</td>
                           <td class="center">' . $redirect['re_type'] . '</td>';
            if (!empty($url)) {
                echo '<td class="center"><a href="' . $url . '" target="_blank" >' . $redirect['name'] . ' </a></td>';
            } else {
                echo '<td class="center">' . $redirect['name'] . '</td>';
            }
                            
            echo '<td id="redirect_url_' . $redirect['re_id'] . '">' . urldecode($redirect['re_destination']) . '</td>';
                       
             if ($redirect['re_active']) {
                echo '<td><input id="re_active_' . $redirect['re_id'] . '" class="redirection-active" type="checkbox" data-toggle="toggle" data-size="mini" data-on="Enabled" data-off="Disabled" checked>';
            } else {
                echo '<td><input id="re_active_' . $redirect['re_id'] . '" class="redirection-active" type="checkbox" data-toggle="toggle" data-size="mini" data-on="Enabled" data-off="Disabled"></td>';
            }
            echo '<td class="center">' . $redirect['re_count_non'] . '/' . $redirect['re_count_redirect'] . '/' . $redirect['re_count'] . '</td>';
            echo '<td>  <button type="button" class="btn btn-success btn-xs button-edit" data-toggle="modal" data-target="#myEditModal" title="Edit this redirection"><i class="glyphicon glyphicon-edit"></i></button>';
            echo '  <button type="button" class="btn btn-danger btn-xs button-delete" title="Delete this redirection"><i class="fa fa-times"></i></button>';
            echo '</td>';
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

function function_visitor_ip_tracking_page() {
    
    load_assets_visitor_ip_tracking();
//    global_admin_ajax();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    echo '<div class="wrap">';
        echo '<div class="row"> 
            <div class="col-lg-12">';
        echo '<div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                            Visitor IP Tracking List
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Options
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Hide Agent Info</a>
                                        </li>
                                        <li><a href="#">Show Agent Info</a>
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
                                   <th class="sorting_desc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 2px;" aria-sort="descending" >No</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 10px;">IP</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 2px;">RID</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">URL</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 50px;">Last Access</th>
                                   <th id="log_agent" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 50px; display: none;">Note</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 2px;">Count</th>
                                </tr>
                             </thead>
                                <tbody>';
    
    $all_logs = $dbModel->getAllVistorIpTracking();
    
    $count = 0;
    foreach ($all_logs as $ip_log) {

        if ($ip_log['re_type'] == 'store') {
            $url = get_term_link((int)$ip_log['re_source']);
            $store_info = $dbModel->getStoreInfoByStoreID((int)$ip_log['re_source']);
            $title = isset($store_info['name']) ? $store_info['name'] : '';
        } else {
            $url = get_permalink($ip_log['re_source']);
            $title = get_the_title($ip_log['re_source']);
        }

        if (isset($url->errors)) {
            $url = '';
        }

        $count++;
        if ($count % 2 == 0) {
            $row_color = "gradeA odd";
        } else {
            $row_color = "gradeA even";
        }
        echo ' <tr class="' . $row_color . '" role="row">
                        <td class="sorting_1" >' . $ip_log['vi_id'] . '</td>
                       <td class="center">' . $ip_log['vi_ip'] . '</td>
                           <td class="center">' . $ip_log['vi_url'] . '</td>';
            if (!empty($url)) {
                echo '<td class="center"><a href="' . $url . '" target="_blank" >' . $title . ' </a></td>';
            } else {
                echo '<td class="center"> URL Redirect ID: ' . $ip_log['vi_url'] . '</td>';
            }
                            
            echo '<td class="center">' . $ip_log['vi_updated'] . '</td>';
            echo '<td name="log_agent" class="center" style="display: none;">' . $ip_log['vi_notes'] . '</td>';
            echo '<td class="center">' . $ip_log['vi_count'] . '</td>';
            
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
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 20px;">ISP/Org</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 20px;">State/Region</th>
                                       <th class="sorting" tabindex="0" id="ip_addition_info" name="ip_addition_info" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Lat</th>
                                       <th class="sorting" tabindex="0" id="ip_addition_info" name="ip_addition_info" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Lon</th>
                                       <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 50px;">Address</th>
                                       <th class="sorting" tabindex="0" id="ip_addition_info" name="ip_addition_info" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Provider</th>
                                       
                                    </tr>
                                 </thead>
                                    <tbody>';


//        $ip_list = 

        $count = 0;
        foreach ($ip_list as $ip) {
            
            $count++;

            if ($count % 2 == 0) {
                $row_color = "gradeA odd";
            } else {
                $row_color = "gradeA even";
            }
            
            echo '<tr class="' . $row_color . '" role="row">';
            
            
            
//            $ip_detail = getIpInfo($ip);
            $ip_detail = getIpInfo($ip, $ip_provider);
            
            if (isset($ip_detail['ip'])) {
                
                $ip_address = getAddressGoogleAPI2($ip_detail['lat'], $ip_detail['lon']);
                
                if ($ip_address == false) {
                    $ip_address = getAddressGoogleAPI2($ip_detail['lat'], $ip_detail['lon']);
                }
                
                echo '<td class="center">' . $count . '</td>';
                echo '<td class="center">' . $ip_detail['ip'] . '</td>';
                echo '<td class="center">' . $ip_detail['isp'] . '</td>';
                echo '<td class="center">' . $ip_detail['region'] . '</td>';
                echo '<td class="center" name="ip_addition_info" >' . $ip_detail['lat'] . '</td>';
                echo '<td class="center" name="ip_addition_info" >' . $ip_detail['lon'] . '</td>';
                
                $google_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$ip_detail['lat']},{$ip_detail['lon']}";
                
                if (!empty($ip_address)) {
                    echo '<td class="center"><a href="' . $google_url . '" target="_blank" >' . $ip_address . '</a></td>';
                } else {
                    echo '<td class="center"><a href="' . $google_url . '" target="_blank" >Check Address</a></td>';
                }
                
                
                echo '<td class="center" name="ip_addition_info" ><a href="' . $ip_detail['api_url'] . '" target="_blank" >' . $ip_detail['provider'] . '</a></td>';
                
            } else {

                echo '<td class="center">' . $count . '</td>';
                echo '<td class="center">' . $ip . '</td>';
                echo '<td class="center">FAIL</td>';
                echo '<td class="center"></td>';
                echo '<td class="center" name="ip_addition_info" ></td>';
                echo '<td class="center" name="ip_addition_info" ></td>';
                echo '<td class="center"></td>';
                echo '<td class="center" name="ip_addition_info" ></td>';
                
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
                                                <option value="random">Random Provider</option>
                                                <option value="default" selected>Default</option>
                                            </select>
                                        </div>
                                        
                                <div class="form-group">
                                    <textarea id="ip-list" name="ip-list" class="form-control" rows="10">125.234.98.126
103.11.173.6
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
    $details = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lon}"), true);
    
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
    
    $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lon}";

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
?>