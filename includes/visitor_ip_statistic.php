<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of visitor_ip_tracking
 *
 * @author dmtuan
 */

function function_visitor_ip_statistic_page() {
    
    load_assets_visitor_ip_statistic();
//    global_admin_ajax();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    echo '<div class="wrap">';
    
    echo '<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">Custom Search</font></strong>
                        </div>
                        <div class="panel-body">';
                        
    
    
    echo '<form role="form" method="post">
                                    <div class="form-group">
                                            <label>Query by</label>
                                            <select class="form-control" id="query_type" name="query_type">
                                            <option value="query_all" selected>All</option>
                                                <option value="query_coupon">Coupon</option>
                                                <option value="query_store">Store</option>
                                            </select>
                                        </div>';
    
    echo '
        <div class="form-group input-group" id="search_store_group">
                                            <input type="text" id="store_search" name="s" class="form-control search-autocomplete" placeholder="Store Search">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" disabled><i class="fa fa-search" button></i>
                                                </button>
                                            </span>
                                        </div>
                    <div class="form-group input-group" id="search_post_group">
                        <input type="text" id="post_search" name="s" class="form-control search-autocomplete" placeholder="Coupon Search">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" disabled><i class="fa fa-search" button></i>
                            </button>
                        </span>
                    </div>
            ';

                                        

                             echo '   <div class="form-group" id="post_id_group">
                                    <label>Coupon ID</label>
                                    <input type="number" class="form-control" id="post_id" name="post_id" value="">
                                </div>
                                


                                <div class="form-group" id="store_id_group">
                                    <label>Store ID</label>
                                    <input type="number" class="form-control" id="store_id" name="store_id" value="">
                                </div>
                                
                                <div class="form-group">
                                        <label>Quick Time Select</label>
                                            <select class="form-control" id="query_timetype" name="query_timetype">
                                                <option value="time_all">All Time</option>
                                                <option value="time_today">Today</option>
                                                <option value="time_7day">Last 7 days</option>
                                                <option value="time_1month">Last month</option>
                                                <option value="time_3month">Last 3 months</option>
                                                <option value="time_custom" selected>Custom</option>
                                            </select>
                                        </div>
                                
                                <div class="form-group" id="datetime_start_group">
                                    <label>Start Time</label>
                                            <div class="input-group date" id="datetime_start">
                                                <input type="text" class="form-control" id="time_start" name="time_start"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                
                                        

                                <div class="form-group" id="datetime_end_group">
                                <label>End Time</label>
                                            <div class="input-group date" id="datetime_end">
                                                <input type="text" class="form-control" id="time_end" name="time_end"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                <input type="hidden" id="process_IpLogCustomSearch" name="process_IpLogCustomSearch">

                                <button type="submit" class="btn btn-success">Query</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
    
    echo '</div></div></div>';
    
    echo '
                <div class="col-lg-6">
                    <div class="panel panel-default" >
                        <div class="panel-heading">
                        <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">Get IP List</font></strong>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs" id="get_ip_list_collapse">
                                        Show/Hide
                                        <span class="caret"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-body" id="get_ip_list_group">';
    
           echo '<form role="form" method="post" action="admin.php?page=get-ip-information">
                                <div class="form-group">
                                            <label>Select Providers</label>
                                            <select class="form-control" id="ip-provider" name="ip-provider">
                                                <option value="ipapi.co">ipapi.co</option>
                                                <option value="ip-api.com">ip-api.com</option>
                                                <option value="ipdata.co">ipdata.co</option>
                                                <option value="random" selected>Random Provider</option>
                                                <!-- <option value="default">Default</option> -->
                                            </select>
                                        </div>
                                        
                                <div class="form-group">
                                    <textarea id="ip-list" name="ip-list" class="form-control" rows="9" placeholder="Click [Get IP List] to get the IPs in [Visitor IP Tracking List]. &#10;Data will be remove duplicate." required></textarea>
                                </div>
                                
                                <input type="hidden" id="process_checkIPInfo" name="process_checkIPInfo">
                                
                                <button type="button" class="btn btn btn-info" id="get_ip_list">Get IP List</button>
                                <button type="submit" class="btn btn-success" id="get_ip_info">Get Info</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
      
        echo '</div></div></div>';
        
        
        echo '
                <div class="col-lg-6">
                    <div class="panel panel-default" >
                        <div class="panel-heading">
                        <i class="fa fa-plus-circle fa-fw"></i>
                            <strong><font color="blue">Get IP Banned</font></strong>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs" id="get_ip_banned_collapse">
                                        Show/Hide
                                        <span class="caret"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-body" id="get_ip_banned_group">';
    
           echo '<form role="form" method="post" action="admin.php?page=get-ip-information">
                                <div class="form-group">
                                            <label>Select Providers</label>
                                            <select class="form-control" id="ip-provider" name="ip-provider">
                                                <option value="ipapi.co">ipapi.co</option>
                                                <option value="ip-api.com">ip-api.com</option>
                                                <option value="ipdata.co">ipdata.co</option>
                                                <option value="random" selected>Random Provider</option>
                                                <!-- <option value="default">Default</option> -->
                                            </select>
                                        </div>
                                        
                                <div class="form-group">
                                    <textarea id="ip-list" name="ip-list" class="form-control" rows="9" placeholder="Click [Get IP List] to get the IPs in [Visitor IP Tracking List]. &#10;Data will be remove duplicate." required></textarea>
                                </div>
                                
                                <input type="hidden" id="process_checkIPInfo" name="process_checkIPInfo">
                                
                                <button type="button" class="btn btn btn-info" id="get_ip_list">Get IP List</button>
                                <button type="submit" class="btn btn-success" id="get_ip_info">Get Info</button>
                                <button type="reset" class="btn btn-default">Reset</button>
        </form>';
      
        echo '</div></div></div>';
    
        echo '<div class="row"> 
            <div class="col-lg-12">';
        echo '<div class="panel panel-default">
                        <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                            Visitor IP Tracking Statistic
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Options
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Hide More Info</a>
                                        </li>
                                        <li><a href="#">Show More Info</a>
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
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 100px;">URL</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Source</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px; text-align: center;">Count</th>
                                   <th aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Options</th>
                                </tr>
                             </thead>
                                <tbody>';

    if (isset($_POST['process_IpLogCustomSearch'])) {
        switch ($_POST['query_type']) {
            
            case 'query_all':
                
                $input = get_time2query($_POST);
                
                $all_logs = $dbModel->getAllVistorIpTracking('query_all', $input);
                
                break;
                
            case 'query_store':
                
                $input = get_time2query($_POST);
                $input['store_id'] = $_POST['store_id'];
                
                $all_logs = $dbModel->getAllVistorIpTracking('query_store', $input);
                
                break;
                
            case 'query_coupon':
                
                $input = get_time2query($_POST);
                $input['post_id'] = $_POST['post_id'];
                
                $all_logs = $dbModel->getAllVistorIpTracking('query_coupon', $input);
                
                break;
            
            default:
                break;
        }
        
        // DO Search here
        
    } else {
        
        $logs = $dbModel->getAllVistorIpTracking_Group();
        $html_logs = $dbModel->getAllVistorIpTracking_Group('html');
        
        $all_logs = array_merge($logs, $html_logs);
       
    }
    
    
    if (count($all_logs) > 0) {
        $count = 0;
        foreach ($all_logs as $ip_log) {

            if (isset($ip_log['re_type']) && $ip_log['re_type'] == 'store') {
                $url = get_term_link((int)$ip_log['re_source']);
                $store_info = $dbModel->getStoreInfoByStoreID((int)$ip_log['re_source']);
                $title = isset($store_info['name']) ? $store_info['name'] : '';
//                $parent_title = $title;
                $parent_title = 'Store';
            } elseif (isset($ip_log['re_type']) && ($ip_log['re_type'] == 'coupon')) {
                $url = get_permalink($ip_log['re_source']);
                $title = get_the_title($ip_log['re_source']);
                
//                $store_info = $dbModel->getStoreInfoByStoreID((int)$ip_log['re_parent']);
//                $parent_title = isset($store_info['name']) ? $store_info['name'] : '';
                $parent_title = 'Coupon';
            } else {
                $url = $ip_log['vi_notes'];
                $title = $ip_log['vi_notes'];
                
                $parent_title = "Static Page";
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
            //<td class="sorting_1" >' . $ip_log['vi_id'] . '</td>
            echo ' <tr class="' . $row_color . '" role="row">
                            <td class="sorting_1" >' . $count . '</td>
                           <td class="center">' . $ip_log['vi_ip'] . '</td>';
//                               <td class="center">' . $ip_log['vi_url'] . '</td>';
                if (!empty($url)) {
                    echo '<td class="center"><a href="' . $url . '" target="_blank" >' . $title . ' </a></td>';
                } else {
                    echo '<td class="center"> URL Redirect ID: ' . $ip_log['vi_url'] . '</td>';
                }
                
                echo '<td class="center">' . $parent_title . '</td>';
                echo '<td class="center">' . $ip_log['count'] . '</td>';
                
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


