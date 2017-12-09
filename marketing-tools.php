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

require_once('autoload.php');
require_once('includes/helper.php');

add_action('plugins_loaded', 'marketing_tools_plugin_init');
register_activation_hook(__FILE__, 'redirection_create_db');

function marketing_tools_plugin_init() {
    add_action('admin_menu', 'marketing_tools_admin_menu');
    add_action('login_init', 'send_frame_options_header', 10, 0);
    add_action('admin_init', 'send_frame_options_header', 10, 0);
}

function redirection_create_db() {
    global $wpdb;
    $db_name = DB_REDIRECTION;
    $charset_collate = $wpdb->get_charset_collate();
    
    // create the ECPT metabox database table
    if($wpdb->get_var("show tables like '$db_name'") != $db_name) 
    {
            $sql = 'CREATE TABLE ' . $db_name . ' (
            `re_id` mediumint NOT NULL AUTO_INCREMENT,
            `re_source` mediumint NOT NULL,
            `re_source_multi` text NOT NULL,
            `re_destination` text NOT NULL,
            `re_type` tinytext NOT NULL,
            `re_active` tinyint NOT NULL,
            `re_count_non` int NOT NULL,
            `re_count_redirect` int NOT NULL,
            `re_count` int NOT NULL,
            UNIQUE KEY re_id (re_id)
            )' . $charset_collate . ';
                
            CREATE INDEX idx_postid ON $db_name (re_source);';

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
    }
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

?>