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



if (!defined('API_IP_PROVIDER_MAX')) {
    define('API_IP_PROVIDER_MAX', 7);
}

require_once('autoload.php');
require_once('includes/helper.php');

add_action('plugins_loaded', 'marketing_tools_plugin_init');

register_activation_hook(__FILE__, 'tracking_create_db');
register_activation_hook(__FILE__, 'redirection_create_db');
register_activation_hook(__FILE__, 'affiliate_create_db');

function marketing_tools_plugin_init() {
    add_action('admin_menu', 'marketing_tools_admin_menu');
    add_action('login_init', 'send_frame_options_header', 10, 0);
    add_action('admin_init', 'send_frame_options_header', 10, 0);
}





function function_testing_page() {
    
    $test = read_redirection_html('C:/xampp/htdocs/limitcoupon//test.php');
    
//    if ($test == false) {
//        echo "PASS";
//    } else {
//        echo "FAIL";
//    }
    
}

function write_redirection_2html($file_name, $redirect_url, $aff_id) {
    
    $file_path = get_home_path() . '/' . $file_name;
    
    $file = fopen($file_path, "w");
    
    $body = '<?php $url=/*StartURL*/"' . $redirect_url . '"/*EndURL*/; $aff_id=/*StartAff*/' . $aff_id . '/*EndAff*/;';
    $body .= "include 'wp-content/plugins/Marketing-Tools/autoload_html.php';";
    $body .= "include 'wp-config.php';";
    $body .= '$re = new TD_Redirection_HTML();';
    $body .= '$re->redirection($aff_id, $url);';
    $body .= '?>';
    
    fwrite($file, $body);
    fclose($file);
    
}

function read_redirection_html($file_name) {
    
    $contents = file_get_contents($file_name);
    
    $data['url_start'] = strpos($contents, '/*StartURL*/') + 13; 
    $data['url_end'] = strpos($contents, '/*EndURL*/') - 2;
    
    $return['redirect_url'] = substr($contents, $data['url_start'], $data['url_end'] - $data['url_start'] + 1);

    $data['aff_start'] = strpos($contents, '/*StartAff*/') + 12; 
    $data['aff_end'] = strpos($contents, '/*EndAff*/');
    
    $aff = substr($contents, $data['aff_start'], $data['aff_end'] - $data['aff_start']);
    
    $return['aff_id'] = $aff;
    
    return $return;
}


function function_html_generator_page() {
    
    load_assets_html_generator();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $aff_accounts = $dbModel->getAllAffiliateAccount();
    
    echo '<div class="wrap">';
    echo '<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">HTML Generator</font></strong>
                        </div>
                        <div class="panel-body">';
                        
    if (isset($_POST['process_addNewHTML'])) {
        
        if (strpos($_POST['file_name'], '.php') !== false) {
            $file_name = $_POST['file_name'];
        } else {
            $file_name = $_POST['file_name'] . '.php';
        }
        
        $file_name = str_replace(' ', '-', $file_name);
        
        if (check_file_name($file_name) == false) {
            
                echo '<div class="alert alert-danger">
                            <strong>The File Name can\'t be used. Please choose another name.</strong>
                </div>';
            
        } else {
            
            foreach ($aff_accounts as $aff) {
                if ($aff['aff_id'] == $_POST['aff_account']) {
                    $aff_account = $aff;
                    break;
                }
            }
            
            write_redirection_2html($file_name, $_POST['redirect_url'], $_POST['aff_account']);

//            if ($add) {
                $string_add = '<font color="red">Added</font>';
//            } else {
//                $string_add = '<font color="red">Updated</font>';
//            }

            echo '<div class="alert alert-success">
                            <strong>' . $string_add . ' the redirection successful. File name: <font color="red">' . $file_name . '</font><br/>
                                File URL: <font color="blue">' . home_url() . '/' . $file_name . '</font> <br/>
                                Redirect URL: <font color="blue">' . $_POST['redirect_url'] . '</font> <br/>
                                Aff ID: '. $aff_account['aff_id'] .' | Name: '. $aff_account['aff_name'] .' | Code: <font color="blue">' . $aff_account['aff_code'] . '</font> <br/>
                            </strong>
                </div>';
        }
        
    }
    
    echo '          <form role="form" method="post">
                                <!-- <div class="form-group" id="generate_mode_group">
                                        <label>Generate Mode</label>
                                            <select class="form-control" id="generate_mode" name="generate_mode">
                                                <option value="mode_custom">Custom Link</option>
                                                <option value="mode_auto">Auto-Generated Affiliate Link</option>
                                            </select>
                                        </div> -->
                                <div class="form-group">
                                    <label>File Name</label>
                                    <input type="text" class="form-control" id="file_name" name="file_name" placeholder="Automatically replace spaces with \'-\' characters" required>
                                </div>';
    
    echo '<div class="form-group">
                                        <label>Affiliate Account</label>
                                            <select class="form-control" id="aff_account" name="aff_account">';

                            foreach ($aff_accounts as $aff) {
                                                    echo '<option value="' . $aff['aff_id'] . '">' . $aff['aff_view'] . '</option>';
                                                }
                                            echo '</select>
                                        </div>';
                                            
                            echo '<div class="form-group">
                                    <label>Redirect URL</label>
                                    <input type="text" class="form-control" id="redirect_url" name="redirect_url" value="https://google.com.vn" required>
                                </div>
                                
                                <input type="hidden" id="process_addNewHTML" name="process_addNewHTML">

                                <button type="submit" class="btn btn-success">Create New</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
    
    echo '</div></div></div></div>';
    
    
        echo '<div class="row"> 
            <div class="col-lg-12">';
        echo '<div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                            HTML Files
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
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">File Name</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px; display: none;">File Path</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px; display: none;">Aff ID</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Affiliate</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">File URL</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Redirect URL</th>
                                   <!-- <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Last Modified</th> -->
                                   <th aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Options</th>
                                </tr>
                             </thead>
                                <tbody>';
    
    
        
        
    
    $count = 0;
    foreach(glob(get_home_path().'/*.php', GLOB_BRACE) as $file) {

        if ( check_file_name($file, false) != false ) {
//            print_r($file);
            
            
            $file_path = $file;
            $file_data = read_redirection_html($file);
            
            foreach ($aff_accounts as $aff) {
                if ($aff['aff_id'] == $file_data['aff_id']) {
                    $aff_account = $aff;
                    break;
                }
            }
            
            
            $count++;
            
            echo '<tr role="row" row_id="'. $count .'">';
            echo '<td class="sorting_1">' . $count . '</td>';
            
            echo '<td id="file_name_' . $count . '">' . basename($file_path) . '</td>';
            echo '<td id="file_path_' . $count . '" style="display: none;">' . $file_path . '</td>';
            echo '<td id="aff_id_' . $count . '" style="display: none;">' . $aff_account['aff_id'] . '</td>';
            echo '<td id="aff_name_' . $count . '" >' . $aff_account['aff_view'] . '</td>';
            echo '<td id="file_url_' . $count . '">' . home_url() . '/' . basename($file_path) . '</td>';
            echo '<td id="redirect_url_' . $count . '">' . urldecode($file_data['redirect_url']) . '</td>';
            
            echo '<td>  <button type="button" class="btn btn-success btn-xs button-edit" data-toggle="modal" data-target="#myEditModal" title="Edit"><i class="glyphicon glyphicon-edit"></i></button>';
            echo '  <button type="button" class="btn btn-danger btn-xs button-delete" title="Delete"><i class="fa fa-times"></i></button>';
            echo '</td>';
            echo '</tr>';
        }
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
                 <h4 class="modal-title" id="myModalLabel">Edit HTML Files</h4>

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

function function_marketing_options_page() {
    
    load_assets_tool_options();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    echo '<div class="wrap">';
    echo '<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">Affiliate Manager</font></strong>
                        </div>
                        <div class="panel-body">';
                        
    if (isset($_POST['process_addNewAffiliate'])) {
        
        $exist_aff_code = $dbModel->getAffiliateAccountByCode($_POST['aff_code']);
        
        if ($exist_aff_code != false) {
            $dbModel->UpdateAffiliateAccount($exist_aff_code['aff_id'], $_POST['aff_name'] , $_POST['aff_code']);
            $add = false;
        } else {
            $dbModel->addNewAffiliateAccount($_POST['aff_name'] , $_POST['aff_code']);
            $add = true;
        }
        
        if ($add) {
            $string_add = '<font color="red">Added</font>';
        } else {
            $string_add = '<font color="red">Updated</font>';
        }
        
        echo '<div class="alert alert-success">
                        <strong>' . $string_add . ' the affiliate account successful.<br/>
                            Aff Name: <font color="blue">' . $_POST['aff_name'] . '</font> <br/>
                            Aff Code: <font color="blue">' .  $_POST['aff_code'] . '</font> <br/>
                            
                            </strong>
            </div>';
        
    }
    
    echo '<form role="form" method="post">
                                <div class="form-group">
                                    <label>Affiliate Name</label>
                                    <input type="text" class="form-control" id="aff_name" name="aff_name" placeholder="" value="Test" required>
                                </div>
                                <div class="form-group">
                                    <label>Affiliate Code</label>
                                    <input type="text" class="form-control" id="aff_code" name="aff_code" value="AAAAAAAAAA" required>
                                </div>
                                
                                <input type="hidden" id="process_addNewAffiliate" name="process_addNewAffiliate">

                                <button type="submit" class="btn btn-success">Create New</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
    
    echo '</div></div></div></div>';
    
    
        echo '<div class="row"> 
            <div class="col-lg-12">';
        echo '<div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                            HTML Files
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
                                   <th class="sorting_desc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 10px;" aria-sort="descending" >AffID</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 50px;">Affiliate Name</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 150px;">Affiliate Code</th>
                                   <th aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 20px;">Options</th>
                                </tr>
                             </thead>
                                <tbody>';
    
    $all_affiliate = $dbModel->getAllAffiliateAccount();
    
//    echo '<pre>';
//    print_r($all_affiliate);
//    echo '</pre>';
//    exit;
    
    $count = 0;
    foreach($all_affiliate as $aff) {

            echo '<tr role="row" aff_id="'. $aff['aff_id'] .'">';
            echo '<td class="sorting_1">' . $aff['aff_id'] . '</td>';
            
            echo '<td id="aff_name_' . $aff['aff_id'] . '">' . $aff['aff_name'] . '</td>';
            echo '<td id="aff_code_' . $aff['aff_id'] . '">' . $aff['aff_code'] . '</td>';
            
            echo '<td>  <button type="button" class="btn btn-success btn-xs button-edit" data-toggle="modal" data-target="#myEditModal" title="Edit"><i class="glyphicon glyphicon-edit"></i></button>';
            echo '  <button type="button" class="btn btn-danger btn-xs button-delete" title="Delete"><i class="fa fa-times"></i></button>';
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
                 <h4 class="modal-title" id="myModalLabel">Edit Affiliate Account</h4>

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

function check_file_name($file_name, $equal = true) {
    
    $nonfiles = array(  'index.php', 
                        'wp-activate.php', 
                        'wp-blog-header.php', 
                        'wp-comments-post.php', 
                        'wp-config-sample.php', 
                        'wp-config.php', 
                        'wp-cron.php', 
                        'wp-links-opml.php', 
                        'wp-load.php', 
                        'wp-login.php', 
                        'wp-mail.php', 
                        'wp-settings.php', 
                        'wp-signup.php', 
                        'wp-trackback.php', 
                        'xmlrpc.php'    );
    
    if ($equal == true) {
        foreach ($nonfiles as $non_name) {
            if ($file_name == $non_name) {
                return false;
            }
        }
        return true;
    } else {
        foreach ($nonfiles as $non_name) {
            if (strpos($file_name,$non_name) !== false) {
                return false;
            }
        }
        return true;
    }
}

?>