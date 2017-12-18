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
    
    $('#dataTables-example').DataTable({
            responsive: true,
            "bDestroy": true,
            "autoWidth": false
    });
    
    $(".btn[data-target='#myEditModal']").click(function() {
            var columnHeadings = $("thead th").map(function() {
                      return $(this).text();
                   }).get();
            columnHeadings.pop();
            var columnValues = $(this).parent().siblings().map(function() {
                      return $(this).text();
            }).get();
            
       var modalBody = $('<div id="modalContent"></div>');
       var modalForm = $('<form role="form" id="edit-aff-account" name="edit-aff-account" action="admin-ajax.php" method="post"></form>');
       $.each(columnHeadings, function(i, columnHeader) {
           var formGroup;
           var columnID = columnHeader.replace(/ /g, '_').toLowerCase();
           if (columnHeader == "AffID") {
               formGroup = $('<div class="form-group hidden"></div>');
           } else {
               formGroup = $('<div class="form-group"></div>');
           }
            formGroup.append('<label for="'+columnHeader+'">'+columnHeader+'</label>');
            
           formGroup.append('<input class="form-control" name="'+columnID+'" id="'+columnID+'" value="'+columnValues[i]+'"/>'); 
            
            modalForm.append(formGroup);
       });
       modalBody.append(modalForm);
       $('.modal-body').html(modalBody);
     });
     
     $('.modal-footer .btn-primary').click(function() {
        var data = $('#edit-aff-account').serializeArray();
        $.post(
            global.ajax, 
            {   
                data: data,
                action: 'update_affaccount' 
            }, 
        function(data) {
                $('#close-update-modal').click();
                $("#aff_name_" + data.data.aff_id).html(data.data.aff_name);
                $("#aff_code_" + data.data.aff_id).html(data.data.aff_code);
        });
            
     });
     
      $('.button-delete').click(function() {
        
        var item = $(this);
        var row = item.parent().parent();
        item.prop('disabled', true);
        var aff_id = row.attr('aff_id');
        
         $.post(
            global.ajax, 
            {   
                "aff_id": aff_id,
                action: 'delete_affaccount' 
            }, 
            function(data) {
                console.log("Deleted Aff Account: " + aff_id);
                row.remove();
            });
    });
    
    
});

