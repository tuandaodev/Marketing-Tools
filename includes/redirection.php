<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of redirection
 *
 * @author dmtuan
 */
function function_redirection_page() {
    
    load_assets_redirection();
    global_admin_ajax();
    
    $dbModel = new DbModel(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $aff_accounts = $dbModel->getAllAffiliateAccount();
    
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
        
        foreach ($aff_accounts as $aff) {
            if ($aff['aff_id'] == $_POST['post_aff_account']) {
                $aff_account = $aff;
                break;
            }
        }
        $add = $dbModel->add_redirection($_POST['post_id'], $_POST['post_aff_account'], $_POST['post_redirect_url'], 'coupon');
        
        if ($add) {
            $string_add = '<font color="red">Added</font>';
        } else {
            $string_add = '<font color="red">Updated</font>';
        }
        
        echo '<div class="alert alert-success">
                        <strong>' . $string_add . ' the redirection successful. Coupon ID: <font color="red">' . $_POST['post_id'] . '</font><br/>
                            URL: <font color="blue">' . $_POST['post_redirect_url'] . '</font> <br/>
                            Aff ID: '. $aff_account['aff_id'] .' | Name: '. $aff_account['aff_name'] .' | Code: <font color="blue">' . $aff_account['aff_code'] . '</font> <br/>
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
                                </div>';
    
    echo '<div class="form-group">
                                        <label>Affiliate Account</label>
                                            <select class="form-control" id="post_aff_account" name="post_aff_account">';

                            foreach ($aff_accounts as $aff) {
                                                    echo '<option value="' . $aff['aff_id'] . '">' . $aff['aff_view'] . '</option>';
                                                }
                                            echo '</select>
                                        </div>';

            echo '<div class="form-group">
                                    <label>Redirect URL</label>
                                    <input type="text" class="form-control" id="post_redirect_url" name="post_redirect_url" value="https://google.com.vn" required>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Proxy Redirect URL</label>
                                    <input type="text" class="form-control" id="post_proxy_redirect_url" name="post_proxy_redirect_url" value="' . home_url() . '/blocked.html" required>
                                </div> -->
                                
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
        
        $add = $dbModel->add_redirection($_POST['store_id'], $_POST['store_aff_account'], $_POST['store_redirect_url'], 'store');
        
        if ($add) {
            $string_add = '<font color="red">Added</font>';
        } else {
            $string_add = '<font color="red">Updated</font>';
        }
        
        echo '<div class="alert alert-success">
                        <strong>' . $string_add . ' the redirection successful. Coupon ID: <font color="red">' . $_POST['store_id'] . '</font><br/>
                            URL: <font color="blue">' . $_POST['store_redirect_url'] . '</font> <br/>
                            Aff ID: <font color="blue">' . $_POST['store_aff_account'] . '</font>
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
                                </div>';
        
            echo '<div class="form-group">
                                        <label>Affiliate Account</label>
                                            <select class="form-control" id="store_aff_account" name="store_aff_account">';

                                            foreach ($aff_accounts as $aff) {
                                                    echo '<option value="' . $aff['aff_id'] . '">' . $aff['aff_name'] . ': ' . $aff['aff_code'] . '</option>';
                                                }
                                            echo '</select>
                                        </div>';
        
        
        echo '                       <div class="form-group">
                                    <label>Redirect URL</label>
                                    <input type="text" class="form-control" id="store_redirect_url" name="store_redirect_url" value="https://google.com.vn" required>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Proxy Redirect URL</label>
                                    <input type="text" class="form-control" id="store_proxy_redirect_url" name="store_proxy_redirect_url" value="' . home_url() . '/blocked.html" required>
                                </div> -->
                                
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
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 150px;">Title</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px; display:none;">AffID</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 150px;">Affiliate</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 150px;">Redirect URL</th>
                                   <!-- <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 150px;">Proxy URL</th> -->
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Status</th>
                                   <th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Non/Re/All</th>
                                   <th aria-controls="dataTables-example" rowspan="1" colspan="1" style="width: 5px;">Options</th>
                                </tr>
                             </thead>
                                <tbody>';
    
    $all_coupon_redirections = $dbModel->getAllCouponRedirection_IpCount();
    $all_store_redirections = $dbModel->getAllStoreRedirection_IpCount();
    
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
            
            echo '<td id="aff_id_' . $redirect['re_id'] . '" style="display:none;">' . $redirect['aff_id'] . '</td>';
            echo '<td id="aff_name_' . $redirect['re_id'] . '">' . $redirect['aff_view'] . '</td>';
            
            echo '<td id="redirect_url_' . $redirect['re_id'] . '">' . urldecode($redirect['re_destination']) . '</td>';
            
             if ($redirect['re_active']) {
                echo '<td><input id="re_active_' . $redirect['re_id'] . '" class="redirection-active" type="checkbox" data-toggle="toggle" data-size="mini" data-on="Enabled" data-off="Disabled" checked>';
            } else {
                echo '<td><input id="re_active_' . $redirect['re_id'] . '" class="redirection-active" type="checkbox" data-toggle="toggle" data-size="mini" data-on="Enabled" data-off="Disabled"></td>';
            }
            $redirection_total = $redirect['re_count_non'] + $redirect['re_count_redirect'];
            echo '<td class="center">' . $redirect['re_count_non'] . '/' . $redirect['re_count_redirect'] . '/' . $redirection_total . '</td>';
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
