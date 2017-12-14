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
        
    $('li a').click(function(e) {
        
        if ($(this).text() == 'Hide Info') {
            $('th[name="ip_addition_info"]').hide();
            $('td[name="ip_addition_info"]').hide();
        } else {
            $('th[name="ip_addition_info"]').show();
            $('td[name="ip_addition_info"]').show();
        }
        
      });
});

