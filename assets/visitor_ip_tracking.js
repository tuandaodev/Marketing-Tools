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
        
        if ($(this).text() == 'Hide Agent Info') {
            $('#log_agent').hide();
            $('td[name="log_agent"]').hide();
        } else {
            $('#log_agent').show();
            $('td[name="log_agent"]').show();
        }
        
      });
});

