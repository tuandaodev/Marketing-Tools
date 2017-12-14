/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function($) {
    
    $('#dataTables-example').DataTable({
            responsive: true,
            "bDestroy": true,
            "autoWidth": false
        });

//     var table = $('#dataTables-example').DataTable();
//     table.columns( [ 4, 5, 7 ] ).visible( false, false );
        
    $('li a').click(function(e) {
        
        var table = $('#dataTables-example').DataTable();
        
        if ($(this).text() == 'Hide Info') {
            table.columns( [ 4, 5, 7 ] ).visible( false, false );
        } else {
            table.columns( [ 4, 5, 7 ] ).visible( true, true );
        }
        
      });
});

