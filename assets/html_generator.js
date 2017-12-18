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
       var modalForm = $('<form role="form" id="edit-html-file" name="edit-html-file" action="admin-ajax.php" method="post"></form>');
       $.each(columnHeadings, function(i, columnHeader) {
           var formGroup;
           var columnID = columnHeader.replace(/ /g, '_').toLowerCase();
           if (columnHeader == "No" || columnHeader == "File Path") {
               formGroup = $('<div class="form-group hidden"></div>');
           } else {
               formGroup = $('<div class="form-group"></div>');
           }
            formGroup.append('<label for="'+columnHeader+'">'+columnHeader+'</label>');
            
            var disableInput = "";
            if (columnHeader == "File URL") {
               disableInput = "disabled";
            } 
            
            if (columnHeader == "File Name") {
                formGroup.append('<input class="form-control hidden" name="'+columnID+'_old" id="'+columnID+'_old" value="'+columnValues[i]+'" '+ disableInput +'/>'); 
            }
            
           formGroup.append('<input class="form-control" name="'+columnID+'" id="'+columnID+'" value="'+columnValues[i]+'" '+ disableInput +'/>'); 
            
            modalForm.append(formGroup);
       });
       modalBody.append(modalForm);
       $('.modal-body').html(modalBody);
     });
     
     $('.modal-footer .btn-primary').click(function() {
        var data = $('#edit-html-file').serializeArray();
        $.post(
            global.ajax, 
            {   
                data: data,
                action: 'update_htmlfile' 
            }, 
            function(data) {
                $('#close-update-modal').click();
                $("#file_name_" + data.data.no).html(data.data.file_name);
                $("#file_path_" + data.data.no).html(data.data.file_path);
                $("#file_url_" + data.data.no).html(data.data.file_url);
                $("#redirect_url_" + data.data.no).html(data.data.redirect_url);
                
        });
            
     });
     
      $('.button-delete').click(function() {
        
        var item = $(this);
        var row = item.parent().parent();
        item.prop('disabled', true);
        var row_id = row.attr('row_id');
        
        console.log(row_id);
        
        console.log($("#file_path_" + row_id).html());
        
         $.post(
            global.ajax, 
            {   
                data: {"row_id": row_id, "file_path": $("#file_path_" + row_id).html()},
                action: 'delete_htmlfile' 
            }, 
            function(data) {
                console.log("Deleted HTML: " + row_id);
                row.remove();
            });
    });
    
    
});

