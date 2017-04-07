jQuery(document).ready( function($) {
   
   // agent welcome modal
    var welcome_modal = $('#welcome-modal').modal();
    welcome_modal.on('hidden.bs.modal', function(e) {
        
        $('iframe', this ).remove();
        
    });
    
    
});