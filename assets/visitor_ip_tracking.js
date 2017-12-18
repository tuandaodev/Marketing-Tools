/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}

jQuery(document).ready(function($) {
    
    $(window).keydown(function(event){
    if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
    });
    
    $('#datetime_start').datetimepicker({
        showTodayButton: true,
        showClear: true
    });
    $('#datetime_end').datetimepicker({
        showTodayButton: true,  
        showClear: true,
        useCurrent: true
    });
    
    $("#datetime_start").on("dp.change", function (e) {
        $('#datetime_end').data("DateTimePicker").minDate(e.date);
    });
    $("#datetime_end").on("dp.change", function (e) {
        $('#datetime_start').data("DateTimePicker").maxDate(e.date);
    });
    
    $('#dataTables-example').DataTable({
            responsive: true,
            "bDestroy": true,
            "autoWidth": false
        });
    
//    $('#post_id_group').hide();
//    $('#store_id_group').hide();
//    $('#search_group').hide();

    query_all();

    function query_all() {
        $('#post_id_group').hide();
        $('#store_id_group').hide();
//        $('#search_group').hide();
        $('#search_store_group').hide();
        $('#search_post_group').hide();
        $("#post_id").prop('required',false);
        $("#store_id").prop('required',false);
    }
    

    
    $('#query_type').on('change', function() {
        switch (this.value) {
            
            case 'query_store':
                query_all();
                $('#store_id_group').show();
//                $('#search_group').show();
                $('#search_store_group').show();
                $("#store_id").prop('required',true);
                break;
            case 'query_coupon':
                query_all();
                $('#post_id_group').show();
//                $('#search_group').show();
                $('#search_post_group').show();
                $("#post_id").prop('required',true);
                break;
            case 'query_all':
//            default:
                query_all();
//                $('#post_id_group').hide();
//                $('#store_id_group').hide();
//                $('#search_group').hide();
                break;
        }
    });
    
    $('#query_timetype').on('change', function() {
        switch (this.value) {
//            case 'time_all':
//            case 'time_today':
//            case 'time_7day':
//            case 'time_1month':
//            case 'time_3month':
//                    break;
            case 'time_custom':
                $('#datetime_start_group').show();
                $('#datetime_end_group').show();      
                break;
            default:
                $('#datetime_start_group').hide();
                $('#datetime_end_group').hide();
                break;

        }
    });
    
    var table = $('#dataTables-example').DataTable();
    
    table.columns( [ 6, 7 ] ).visible( false, false );
    
    $('li a').click(function(e) {
        
//         var table = $('#dataTables-example').DataTable();
        
        if ($(this).text() == 'Hide More Info') {
            table.columns( [ 6, 7 ] ).visible( false, false );
        } else {
            table.columns( [ 6, 7 ] ).visible( true, true );
        }
        
      });
    
    $('#get_ip_list').click(function () {
       $('#ip-list').val(
            table
                .columns( 1 )
                .data()
                .eq( 0 )      // Reduce the 2D array into a 1D array of data
                .sort()       // Sort data alphabetically
                .unique()     // Reduce to unique values
                .join( '\r\n' )
        );
    });
    
    var searchRequest;
    $('#post_search').autoComplete({
            minChars: 2,
            source: function(term, suggest){
                    try { searchRequest.abort(); } catch(e){}
                    searchRequest = $.post(global.ajax, { search: term, action: 'search_site' }, function(res) {
                        if (isEmpty(res.data)) {
                            res.data = ["404"];
                        }
                        suggest(res.data);
                    });
            },
            renderItem: function (item){
                if (item === "404") {
                    return '<div class="autocomplete-suggestion" data-postid="0" data-val="Coupon/Post Not Found">Coupon/Post Not Found</div>';
                } 
                return '<div class="autocomplete-suggestion" data-postid="' + item['ID'] + '" data-val="' + item['post_title'] + '">' + item['post_title'] + '</div>';
            },
            onSelect: function(e, term, item){
                $('#post_id').val(item.data('postid'));
            }
    });
    
    var searchRequestStore;
    $('#store_search').autoComplete({
            minChars: 2,
            source: function(term, suggest){
                    try { searchRequestStore.abort(); } catch(e){}
                    searchRequestStore = $.post(global.ajax, { search: term, action: 'search_store' }, function(res) {
                        if (isEmpty(res.data)) {
                            res.data = ["404"];
                        }
                        suggest(res.data);
                    });
            },
            renderItem: function (item){
                
//                console.log(item);
                
                if (item === "404") {
                    return '<div class="autocomplete-suggestion" data-storeid="0" data-val="Store Not Found">Store Not Found</div>';
                } 
                return '<div class="autocomplete-suggestion" data-storeid="' + item['ID'] + '" data-val="' + item['post_title'] + '">' + item['post_title'] + '</div>';
            },
            onSelect: function(e, term, item){
                $('#store_id').val(item.data('storeid'));
            }
    });
    
    
    
});

